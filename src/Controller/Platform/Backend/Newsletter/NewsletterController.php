<?php

namespace App\Controller\Platform\Backend\Newsletter;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Newsletter\Newsletter;
use App\Entity\Platform\User;
use App\Form\Platform\NewsletterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/newsletter')]
#[IsGranted(User::ROLE_USER)]
class NewsletterController extends PlatformController
{
    #[Route('/', name: 'admin_v1_newsletter')]
    public function index(): Response
    {
        $newsletters = $this->doctrine->getRepository(Newsletter::class)->findBy(['instance' => $this->currentInstance]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevelek',
            'tableHead' => [
                'subject' => 'Tárgy',
                'sendAt' => 'Küldés ideje',
            ],
            'tableBody' => $newsletters,
            'actions' => [
                'edit'
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_newsletter_add')]
    public function new(): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletter->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($newsletter);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.saved'));

            return $this->redirectToRoute('admin_v1_newsletter');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevél hozzáadása',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{newsletter}', name: 'admin_v1_newsletter_edit')]
    public function edit(Newsletter $newsletter): Response
    {
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->persist($newsletter);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.updated'));

            return $this->redirectToRoute('admin_v1_newsletter_edit', [
                'newsletter' => $newsletter->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevél szerkesztése',
            'form' => $form->createView(),
        ]);
    }
}
