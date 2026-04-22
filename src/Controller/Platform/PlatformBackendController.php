<?php

namespace App\Controller\Platform;

use App\Entity\Platform\Instance\InstanceFeed;
use App\Entity\Platform\User;
use App\Form\Platform\Instance\InstanceFeedType;
use App\Repository\Platform\InstanceRepository;
use App\Repository\Platform\ServiceRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[IsGranted(User::ROLE_USER)]
class PlatformBackendController extends PlatformController
{
    protected function platformBackendIndex(string $tableTitle = '', array $tableHead = [], array $tableBody = [], array $actions = []): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => $tableTitle,
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => $actions,
        ]);
    }

    protected function platformBackendNew(Request $request, FormInterface $form, string $redirectToRoute = ''): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute($redirectToRoute);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $this->translator->trans('global.new'),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    protected function platformBackendEdit(Request $request, FormInterface $form, object $entity, string $redirectToRoute = ''): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', $this->translator->trans('action.edited') . ': ' . $entity->getName());

            return $this->redirectToRoute($redirectToRoute);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $this->translator->trans('action.edit') . ': ' . $entity->getName(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    protected function platformBackendDelete(object $entity, string $redirectToRoute): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $em = $this->doctrine->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash('success', $this->translator->trans('action.deleted') . ': ' . $entity->getName());

        return $this->redirectToRoute($redirectToRoute);
    }

    #[Route('/homepage', name: 'admin_v1_dashboard_homepage', methods: ['GET'])]
    public function dashboard(): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $instance = $this->currentInstance;
        $feed = new InstanceFeed();

        $instance = (new InstanceRepository($this->doctrine))->find($instance);
        $instanceUsers = $instance->getUsers();
        $services = (new ServiceRepository($this->doctrine))->findBy(['instance' => $instance]);

        $domain = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $slugger = new AsciiSlugger();
        $registerUrl = $domain . $this->generateUrl('register', [
                    'instanceSlug' => $slugger->slug($instance->getName())->lower(),
                    'instance' => $instance->getId()
                ]
            );

        $form = $this->createForm(InstanceFeedType::class, $feed, [
            'action' => $this->generateUrl('admin_v1_instance_feed_add'),
            'method' => 'POST',
        ]);

        return $this->render('platform/backend/v1/dashboard.html.twig', [
            'title' => 'Dashboard',
            'tableHead' => [
                'name' => 'Megnevezés',
                'description' => $this->translator->trans('data.description'),
                'type' => 'Típus',
                'fee' => 'Díj',
                'currency' => 'Pénznem',
                'frequencyOfPayment' => 'Fizetési gyakoriság',
                //'nextPaymentDate' => 'Következő fizetési dátum',
                'status' => 'Státusz',
            ],
            'tableBody' => $services,
            'currentInstance' => $this->currentInstance,
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'clientsCount' => 0,
            'newsletterSubscriberCount' => 0,
            'form' => $form,
            'feed' => $this->doctrine->getRepository(InstanceFeed::class)->findBy(['instance' => $instance], ['createdAt' => 'DESC']),
            'instanceUsers' => $instanceUsers,
            'registerUrl' => $registerUrl,
        ]);
    }
}
