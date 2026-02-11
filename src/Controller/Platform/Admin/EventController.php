<?php

namespace App\Controller\Platform\Admin;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Event;
use App\Form\EventType;
use App\Repository\Platform\EventRepository;
use App\Entity\Platform\User;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Query\GeocodeQuery;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/event')]
#[IsGranted(User::ROLE_ADMIN)]
final class EventController extends PlatformController
{
    #[Route('/', name: 'admin_event_index', methods: ['GET'])]
    public function index(EventRepository $events): Response
    {
        $data = $events->findBy([], ['startAt' => 'DESC']);

        $instance = $this->currentInstance;
        $data = array_filter($data, function (Event $event) use ($instance) {
            return $instance->getWebsites()->contains($event->getWebsite());
        });

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Events',
            'tableHead' => [
                'title' => 'Title',
                'startAt' => 'Start',
                'endAt' => 'End',
                'location' => 'Location',
                'locationName' => 'Location Name',
                'latitude' => 'Latitude',
                'longitude' => 'Longitude',
            ],
            'tableBody' => $data,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new', name: 'admin_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event)
            ->add('saveAndCreateNew', SubmitType::class)
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setCreatedAt(new \DateTimeImmutable());

            $geocode = $this->getAddressGeocode($event->getLocation());
            if ($geocode) {
                $event->setLatitude($geocode['latitude']);
                $event->setLongitude($geocode['longitude']);
            }

            // if website is not set, set to current instance's first website
            if (null === $event->getWebsite()) {
                $websites = $this->currentInstance->getWebsites();
                if (count($websites) > 0) {
                    $event->setWebsite($websites->first());
                }
            }

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'action.saved');

            /** @var SubmitButton $submit */
            $submit = $form->get('saveAndCreateNew');
            if ($submit->isClicked()) {
                return $this->redirectToRoute('admin_event_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Create New Event',
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id:event}', name: 'admin_event_show', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('platform/backend/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/edit/{id:event}', name: 'admin_event_edit', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        $originalEvent = clone $event;

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if location is changed, update latitude and longitude
            if ($event->getLocation()!==$originalEvent->getLocation()) {
                $geocode = $this->getAddressGeocode($event->getLocation());
                if ($geocode) {
                    $event->setLatitude($geocode['latitude']);
                    $event->setLongitude($geocode['longitude']);
                }
            }

            $event->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', $this->translator->trans('action.updated'));

            //return $this->redirectToRoute('admin_event_edit', ['id' => $event->getId()], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
        }

        /*
        return $this->render('platform/backend/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
        */

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id:event}', name: 'admin_event_delete', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        /*
        $token = $request->getPayload()->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
        }
        */

        $em->remove($event);
        $em->flush();

        $this->addFlash('success', 'event.deleted_successfully');

        return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
    }

    private function getAddressGeocode(string $address): ?array
    {
        try {
            $httpClient = new \Http\Discovery\Psr18Client();
            $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, null, 'AIzaSyDUUphBsnzUfbjC93pE8HF98zeKtgqizCM');
            $geocoder = new \Geocoder\StatefulGeocoder($provider, 'hu');

            $result = $geocoder->geocodeQuery(GeocodeQuery::create($address));

            $coordinates = $result->first()->getCoordinates();

            return [
                'latitude' => $coordinates->getLatitude(),
                'longitude' => $coordinates->getLongitude(),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}
