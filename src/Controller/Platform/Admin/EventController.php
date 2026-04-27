<?php

namespace App\Controller\Platform\Admin;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Event;
use App\Entity\Platform\Location;
use App\Form\EventType;
use App\Repository\Platform\EventRepository;
use App\Entity\Platform\User;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Query\GeocodeQuery;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\File;

#[Route('/{_locale}/admin/v1/event')]
#[IsGranted(User::ROLE_ADMIN)]
final class EventController extends PlatformController
{
    #[Route('/', name: 'admin_v1_cms_event_index', methods: ['GET'])]
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
                'performer' => 'Performer',
                'title' => 'Title',
                'startAt' => 'Start',
                'endAt' => 'End',
                'location' => 'Location',
                'locationName' => 'Location Name',
                'description' => 'Description',
                'latitude' => 'Latitude',
                'longitude' => 'Longitude',
            ],
            'tableBody' => $data,
            'actions' => [
                'new',
                'edit',
                'duplicate',
                'delete',
            ],
        ]);
    }

    #[Route('/import', name: 'admin_v1_cms_event_import', methods: ['GET', 'POST'])]
    public function import(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class, [
                'label' => 'CSV File (UTF-8 CSV header with columns: startAt;performer;locationName;location;title;ticketUrl;description)',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'text/csv',
                            'text/plain',
                            'application/csv',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid CSV file',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Import',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $csvFile */
            $csvFile = $form->get('csvFile')->getData();

            if ($csvFile) {
                $content = file_get_contents($csvFile->getPathname());

                // remove UTF-8 BOM if present
                $bom = "\xEF\xBB\xBF";
                if (strncmp($content, $bom, 3) === 0) {
                    $content = substr($content, 3);
                }
                file_put_contents($csvFile->getPathname(), $content);

                if (!mb_check_encoding($content, 'UTF-8')) {
                    $this->addFlash('danger', 'The CSV file is not UTF-8 encoded. Please convert it to UTF-8 before importing.');
                    return $this->redirectToRoute('admin_v1_cms_event_import', [], Response::HTTP_SEE_OTHER);
                }
                $importedCount = $this->processCSVImport($csvFile, $em);
                $this->addFlash('success', sprintf('%d events imported successfully.', $importedCount));
                return $this->redirectToRoute('admin_v1_cms_event_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Import Events from CSV',
            'form' => $form,
        ]);
    }

    private function processCSVImport(UploadedFile $csvFile, EntityManagerInterface $em): int
    {
        $importedCount = 0;
        $handle = fopen($csvFile->getPathname(), 'r');

        if ($handle === false) {
            throw new \RuntimeException('Could not open CSV file');
        }

        // Read header row
        $header = fgetcsv($handle, 0, ';');
        if ($header === false) {
            fclose($handle);
            throw new \RuntimeException('Could not read CSV header');
        }

        // Normalize header names (trim whitespace)
        $header = array_map('trim', $header);

        // Get default website
        $defaultWebsite = null;
        $websites = $this->currentInstance->getWebsites();
        if (count($websites) > 0) {
            $defaultWebsite = $websites->first();
        }

        $headerCount = count($header);

        // Process each row
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            // Skip empty rows
            if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                continue;
            }

            $rowCount = count($row);

            // Accept rows with exact column count or missing last column (description)
            if ($rowCount === $headerCount || ($rowCount + 1) === $headerCount) {
                // Pad with empty string if last column is missing
                if ($rowCount < $headerCount) {
                    $row[] = '';
                }
            } else {
                // Skip rows with unexpected column count
                continue;
            }

            $data = array_combine($header, $row);

            $event = new Event();
            $event->setCreatedAt(new \DateTimeImmutable());
            $event->setInstance($this->currentInstance);

            // Map CSV columns to Event entity properties
            if (isset($data['startAt']) && !empty($data['startAt'])) {
                try {
                    $event->setStartAt($this->parseDateTime($data['startAt']));
                } catch (\Exception $e) {
                    continue; // Skip if date parsing fails
                }
            } else {
                continue; // startAt is required
            }

            if (isset($data['endAt']) && !empty($data['endAt'])) {
                try {
                    $event->setEndAt($this->parseDateTime($data['endAt']));
                } catch (\Exception $e) {
                    // endAt is optional, continue without it
                }
            }

            if (isset($data['performer'])) {
                $event->setPerformer($data['performer']);
            }

            if (isset($data['title']) && !empty($data['title'])) {
                $event->setTitle($data['title']);
            } else {
                continue; // title is required
            }

            if (isset($data['slug'])) {
                $event->setSlug($data['slug']);
            }

            if (isset($data['locationName'])) {
                $event->setLocationName($data['locationName']);
            }

            if (isset($data['location']) && !empty($data['location'])) {
                $event->setLocation($data['location']);

                // Try to geocode the location
                $locationObject = $this->getLocation($data['location']);
                if ($locationObject) {
                    $event->setLatitude($locationObject->getLatitude());
                    $event->setLongitude($locationObject->getLongitude());
                    $event->setLocationEntity($locationObject);
                }
            }

            if (isset($data['ticketUrl'])) {
                $event->setTicketUrl($data['url'] ?? $data['ticketUrl']);
            }

            if (isset($data['description'])) {
                $event->setDescription($data['description']);
            }

            if (isset($data['leadDescription'])) {
                $event->setLeadDescription($data['leadDescription']);
            }

            if (isset($data['imageUrl'])) {
                $event->setImageUrl($data['imageUrl']);
            }

            // Set default website
            $event->setWebsite($defaultWebsite);

            $em->persist($event);
            $importedCount++;
        }

        fclose($handle);
        $em->flush();

        return $importedCount;
    }

    private function parseDateTime(string $dateTimeString): \DateTime
    {
        $dateTimeString = trim($dateTimeString);

        $formats = [
            'Y.m.d. - H:i:s',
            'Y.m.d. - H:i',
            'Y.m.d. H:i:s',
            'Y.m.d. H:i',
            'Y.m.d H:i:s',
            'Y.m.d H:i',
            'Y.m.d.',
            'Y.m.d',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd/m/Y',
            'd.m.Y H:i:s',
            'd.m.Y H:i',
            'd.m.Y',
        ];

        foreach ($formats as $format) {
            $dt = \DateTime::createFromFormat($format, $dateTimeString);
            if ($dt !== false) {
                // Reset seconds/microseconds when format has no seconds
                if (!str_contains($format, ':s') && !str_contains($format, 'H:i:s')) {
                    $dt->setTime((int)$dt->format('H'), (int)$dt->format('i'), 0);
                }
                return $dt;
            }
        }

        return new \DateTime($dateTimeString);
    }

    #[Route('/new', name: 'admin_v1_cms_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event)
            ->add('saveAndCreateNew', SubmitType::class)
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setCreatedAt(new \DateTimeImmutable());
            $event->setInstance($this->currentInstance);

            $locationObject = $this->getLocation($event->getLocation());
            if ($locationObject)            {
                $event->setLatitude($locationObject->getLatitude());
                $event->setLongitude($locationObject->getLongitude());
                $event->setLocationEntity($locationObject);
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
                return $this->redirectToRoute('admin_v1_cms_event_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('admin_v1_cms_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Create New Event',
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id:event}', name: 'admin_v1_cms_event_show', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('platform/backend/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/edit/{id:event}', name: 'admin_v1_cms_event_edit', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        $originalEvent = clone $event;

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locationObject = null;
            // if location is changed, update latitude and longitude
            if ($event->getLocation()!==$originalEvent->getLocation()) {
                $locationObject = $this->getLocation($event->getLocation());
                if ($locationObject)            {
                    $event->setLatitude($locationObject->getLatitude());
                    $event->setLongitude($locationObject->getLongitude());
                    $event->setLocationEntity($locationObject);
                }
            }

            $event->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', $this->translator->trans('action.updated'));

            //return $this->redirectToRoute('admin_v1_cms_event_edit', ['id' => $event->getId()], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('admin_v1_cms_event_index', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/delete/{id:event}', name: 'admin_v1_cms_event_delete', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        /*
        $token = $request->getPayload()->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_v1_cms_event_index', [], Response::HTTP_SEE_OTHER);
        }
        */

        $em->remove($event);
        $em->flush();

        $this->addFlash('success', 'event.deleted_successfully');

        return $this->redirectToRoute('admin_v1_cms_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/duplicate/{id:event}', name: 'admin_v1_cms_event_duplicate', requirements: ['id' => Requirement::POSITIVE_INT], methods: ['GET'])]
    public function duplicate(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        $newEntity = clone $event;

        $em->persist($newEntity);
        $em->flush();

        return $this->redirectToRoute('admin_v1_cms_event_edit', ['id' => $newEntity->getId()], Response::HTTP_SEE_OTHER);
    }

    private function getLocation(?string $address): ?object
    {
        if ($address !== null) {

            // check if location exists in database, if exists return the Location entity, otherwise return null
            $location = $this->doctrine->getRepository(Location::class)->findOneBy(['name' => $address]);

            if (!$location) {
                //$APIKey = $this->currentInstance->getWebsites()->first()->getGoogleApiKey();
                $APIKey = $_ENV['GOOGLE_MAPS_API_KEY'] ?? getenv('GOOGLE_MAPS_API_KEY');

                $httpClient = new \Http\Discovery\Psr18Client();
                $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, null, $APIKey);
                $geocoder = new \Geocoder\StatefulGeocoder($provider, 'hu');

                try {
                    if ($address) {
                        $result = $geocoder->geocodeQuery(GeocodeQuery::create($address));
                        if ($result->count() === 0) {
                            return null;
                        }
                    }
                } catch (\Exception $e) {
                    return null;
                }

                $location = $this->addLocation($address, $result->first());
            }
            /*
            else {
                return (object) [
                    'coordinates' => (object) [
                        'latitude' => $location->getLatitude(),
                        'longitude' => $location->getLongitude(),
                    ],
                    'postalCode' => $location->getZip(),
                    'locality' => $location->getCity(),
                    'country' => (object) [
                        'name' => $location->getCountry(),
                    ],
                    'streetName' => $location->getAddress(),
                    'streetNumber' => '',
                ];
            }
            */

            return $location;
        }

        return null;
    }

    private function addLocation($address, $geocode): Location
    {
        $location = new Location();
        $location->setName($address);
        $location->setZip($geocode->getPostalCode());
        $location->setCity($geocode->getLocality());
        $location->setDistrict($geocode->getDistrict());
        $location->setAddress($geocode->getStreetName());
        $location->setNumber($geocode->getStreetNumber());
        $location->setCountry($geocode->getCountry()->getName());
        $location->setLatitude($geocode->getCoordinates()->getLatitude());
        $location->setLongitude($geocode->getCoordinates()->getLongitude());
        $this->doctrine->getManager()->persist($location);
        $this->doctrine->getManager()->flush();

        return $location;
    }
}
