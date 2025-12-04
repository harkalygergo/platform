<?php

namespace App\Controller\Platform\Backend\Newsletter;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Newsletter\NewsletterSettings;
use App\Entity\Platform\User;
use App\Form\Platform\Newsletter\NewsletterSettingsType;
use App\Repository\Platform\Newsletter\NewsletterSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/newsletter')]
#[IsGranted(User::ROLE_USER)]
class NewsletterSettingsController extends PlatformController
{
    #[Route('/settings/', name: 'admin_v1_newsletter_settings')]
    public function index(Request $request, EntityManagerInterface $entityManager, NewsletterSettingsRepository $newsletterSettingsRepository): Response
    {
        $newsletterSettings = $newsletterSettingsRepository->findOneBy(['instance' => $this->currentInstance]) ?? new NewsletterSettings();

        $form = $this->createForm(NewsletterSettingsType::class, $newsletterSettings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletterSettings->setInstance($this->currentInstance);

            $this->doctrine->getManager()->persist($newsletterSettings);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.updated'));

            return $this->redirectToRoute('admin_v1_newsletter_settings');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Hírlevél beállítások',
            'form' => $form->createView(),
        ]);
    }
}
