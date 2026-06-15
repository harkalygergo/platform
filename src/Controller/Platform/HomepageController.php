<?php

namespace App\Controller\Platform;

use App\Entity\Platform\Instance\InstanceFeed;
use App\Form\Platform\Instance\InstanceFeedType;
use App\Repository\Platform\CMS\VisitorLogRepository;
use App\Repository\Platform\InstanceRepository;
use App\Repository\Platform\ServiceRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\AsciiSlugger;

class HomepageController extends PlatformBackendController
{
    #[Route('/{_locale}/admin/v1/homepage', name: 'admin_v1_home_homepage', methods: ['GET'])]
    public function homepage(VisitorLogRepository $visitorLogRepository): Response
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
            'instanceMonthlyView' => $visitorLogRepository->getCurrentMonthVisitsSum($this->currentInstance),
            'clientsCount' => 0,
            'newsletterSubscriberCount' => 0,
            'form' => $form,
            'feed' => $this->doctrine->getRepository(InstanceFeed::class)->findBy(['instance' => $instance], ['createdAt' => 'DESC']),
            'instanceUsers' => $instanceUsers,
            'registerUrl' => $registerUrl,
        ]);
    }
}

