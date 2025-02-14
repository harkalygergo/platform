<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Repository\Platform\InstanceRepository;
use App\Repository\Platform\Newsletter\NewsletterSubscriberRepository;
use App\Repository\Platform\ServiceRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ROLE_USER)]
class BackendController extends PlatformController
{
    public function __construct(RequestStack $requestStack, \Doctrine\Persistence\ManagerRegistry $doctrine, TranslatorInterface $translator, KernelInterface $kernel)
    {
        parent::__construct($requestStack, $doctrine, $translator, $kernel);
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

    #[Route('/{_locale}/admin/v1/dashboard', name: 'admin_v1_dashboard')]
    public function index(NewsletterSubscriberRepository $newsletterSubscriberRepository): Response
    {
        $this->init();

        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $instance = $_COOKIE['currentInstance'];
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

        return $this->render('platform/backend/v1/dashboard.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Szolgáltatások',
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

            'instanceUsers' => $instanceUsers,
            'registerUrl' => $registerUrl,
            'newsletterSubscriberCount' => $newsletterSubscriberRepository->countByInstance($instance),
        ]);
    }
}
