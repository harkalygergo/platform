<?php

namespace App\Controller\Platform\Backend\Newsletter;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Newsletter\NewsletterSubscriber;
use App\Entity\Platform\User;
use App\Form\Platform\Newsletter\NewsletterSubscriberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/newsletter/subscriber')]
#[IsGranted(User::ROLE_USER)]
class NewsletterSubscriberController extends PlatformController
{
    #[Route('/', name: 'admin_v1_newsletter_subscriber')]
    public function index(): Response
    {
        $newsletters = $this->doctrine->getRepository(NewsletterSubscriber::class)->findByInstance($this->currentInstance);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevél feliratkozók',
            'tableHead' => [
                'name' => 'Név',
                'email' => 'Email',
                'clientFullName' => 'Ügyfél',
                'status' => 'Státusz',
                'source' => 'Forrás',
                'createdAt' => 'Létrehozva',
            ],
            'tableBody' => $newsletters,
            'actions' => [
                'new',
                'edit',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_newsletter_subscriber_add')]
    public function new(): Response
    {
        $newsletterSubscriber = new NewsletterSubscriber();
        $form = $this->createForm(NewsletterSubscriberType::class, $newsletterSubscriber);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletterSubscriber->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($newsletterSubscriber);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_newsletter_subscriber');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevél feliratkozó hozzáadása',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_v1_newsletter_subscriber_edit')]
    public function edit(NewsletterSubscriber $newsletterSubscriber): Response
    {
        $form = $this->createForm(NewsletterSubscriberType::class, $newsletterSubscriber);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            // update updatedAt field
            $newsletterSubscriber->setUpdatedAt(new \DateTimeImmutable());
            $this->doctrine->getManager()->persist($newsletterSubscriber);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_newsletter_subscriber');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevél feliratkozó szerkesztése',
            'form' => $form->createView(),
        ]);
    }
}
