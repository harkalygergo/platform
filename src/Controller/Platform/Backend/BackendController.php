<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\Backend\Newsletter\NewsletterCron;
use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Instance\InstanceFeed;
use App\Entity\Platform\User;
use App\Form\Platform\Instance\InstanceFeedType;
use App\Repository\Platform\InstanceRepository;
use App\Repository\Platform\Newsletter\NewsletterRepository;
use App\Repository\Platform\Newsletter\NewsletterSubscriberRepository;
use App\Repository\Platform\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ROLE_USER)]
class BackendController extends PlatformController
{
    public function __construct(
        RequestStack $requestStack,
        \Doctrine\Persistence\ManagerRegistry $doctrine,
        TranslatorInterface $translator,
        KernelInterface $kernel,
        MailerInterface $mailer,
        LoggerInterface $logger
    )
    {
        parent::__construct($requestStack, $doctrine, $translator, $kernel, $mailer, $logger);
    }

    protected function init(): ?RedirectResponse
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $this->setUserLastActivation();

        return null;
    }

    private function setUserLastActivation(): void
    {
        $user = $this->getUser();
        $user->setLastActivation(new \DateTimeImmutable());
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();
    }

    #[Route('/{_locale}/admin/v1/access-denied', name: 'admin_v1_access-denied')]
    public function accessDenied(): Response
    {
        return $this->render('platform/backend/v1/access-denied.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hozzáférés megtagadva',
        ]);
    }

    #[Route('/{_locale}/admin/v1/dashboard/cron', name: 'admin_v1_cron')]
    public function cron(EntityManagerInterface $entityManager, NewsletterRepository $newsletterRepository)
    {
        (new NewsletterCron($this->requestStack, $this->doctrine, $this->translator, $this->kernel, $this->mailer, $this->logger, $entityManager, $newsletterRepository))->__invoke();

        return new Response('Kiküldve');
    }

    #[Route('/{_locale}/admin/v1/dashboard', name: 'admin_v1_dashboard')]
    public function index(newsletterSubscriberRepository $newsletterSubscriberRepository): Response
    {
        $this->init();

        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $instance = $_COOKIE['currentInstance'] ?? 1;
        $instance = (new InstanceRepository($this->doctrine))->find($instance);
        $instanceUsers = $instance->getUsers();
        $services = (new ServiceRepository($this->doctrine))->findBy(['instance' => $instance]);

        // get register URL for the instance
        $domain = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $slugger = new AsciiSlugger();
        $registerUrl = $domain . $this->generateUrl('register', [
                'instanceSlug' => $slugger->slug($instance->getName())->lower(),
                'instance' => $instance->getId()
            ]
        );

        $feed = new InstanceFeed();

        $form = $this->createForm(InstanceFeedType::class, $feed, [
            'action' => $this->generateUrl('admin_v1_instance_feed_add'),
            'method' => 'POST',
        ]);


        return $this->render('platform/backend/v1/dashboard.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu($instance->getType()),
            'title' => $this->translator->trans('aside.dashboard'),
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
            //'tableBody' => (new ServiceRepository($this->doctrine))->findAll(),
            'tableBody' => $services,
            'actions' => [
                'cart',
            ],
            'form' => $form->createView(),

            'instanceUsers' => $instanceUsers,
            'registerUrl' => $registerUrl,
            'clientsCount' => count($instance->getClients()),
            'newsletterSubscriberCount' => $newsletterSubscriberRepository->countByInstance($instance),
            'feed' => $this->doctrine->getRepository(InstanceFeed::class)->findBy(['instance' => $instance], ['createdAt' => 'DESC']),
        ]);
    }
}
