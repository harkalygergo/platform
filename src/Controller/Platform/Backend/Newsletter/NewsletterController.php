<?php

namespace App\Controller\Platform\Backend\Newsletter;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Newsletter\Newsletter;
use App\Entity\Platform\Newsletter\NewsletterSettings;
use App\Entity\Platform\Newsletter\NewsletterSubscriber;
use App\Entity\Platform\User;
use App\Enum\NewsletterStatusEnum;
use App\Form\Platform\NewsletterType;
use App\Repository\Platform\Newsletter\NewsletterRepository;
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
                'status' => 'Státusz',
                'sendAt' => 'Küldés ideje',
            ],
            'tableBody' => $newsletters,
            'actions' => [
                'new',
                'edit'
            ],
            'extraActions' => [
                'send' => [
                    'route' => 'admin_v1_newsletter_send',
                    'label' => 'Küldés',
                ],
            ]
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

            $this->addFlash('success', $this->translator->trans('event.saved successfully'));

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
        // if newsletter is not found, redirect to index
        if (!$newsletter) {
            $this->addFlash('danger', 'Hírlevél nem található!');
            return $this->redirectToRoute('admin_v1_newsletter');
        }

        // if newsletter instance is not the current instance, redirect to index
        if ($newsletter->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', 'Hírlevél nem található!');
            return $this->redirectToRoute('admin_v1_newsletter');
        }

        if ($newsletter->isSent()) {
            $this->addFlash('danger', 'Hírlevél már elküldve!');
            return $this->redirectToRoute('admin_v1_newsletter');
        }

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

    #[Route('/send/{id}', name: 'admin_v1_newsletter_send')]
    public function sendNewsletter(Newsletter $id, NewsletterRepository $newsletterRepository): Response
    {
        $newsletter = $id;

        // get all active newsletterSubscribers
        $newsletterSubscribers = $this->doctrine->getRepository(NewsletterSubscriber::class)->findBy(['instance' => $this->currentInstance, 'status' => 1]);

        // check if a newsletter is not sent
        if ($newsletter->isSent()) {
            $this->addFlash('danger', 'Hírlevél már elküldve!');
            return $this->redirectToRoute('admin_v1_newsletter');
        }

        // get newsletter settings for instance
        $newsletterSettings = $this->doctrine->getRepository(NewsletterSettings::class)->findOneBy(['instance' => $this->currentInstance]);

        $newsletterSettingFromEmail = $newsletterSettings->getFromName() .' <'. $newsletterSettings->getFromEmail().'>';

        // loop through newsletterSubscribers and send email
        foreach ($newsletterSubscribers as $newsletterSubscriber) {
            $this->sendMail([$newsletterSubscriber->getEmail()], $newsletter->getSubject(), $newsletter->getPlainTextContent(),
                $newsletterSettingFromEmail, $newsletter->getHtmlContent());
        }


        $newsletter->setStatus(NewsletterStatusEnum::SCHEDULED);
        // save
        $this->doctrine->getManager()->persist($newsletter);
        $this->doctrine->getManager()->flush();
        $this->addFlash('success', 'Hírlevél időzítve!');

        /*
        $newsletter = $newsletterRepository->find($id);

        if ($newsletter) {
            $this->sendMail($this->mailer, $this->logger, [$newsletter->getInstance()->getEmail()], $newsletter->getSubject(), $newsletter->getHtmlContent());
            $this->addFlash('success', 'Hírlevél elküldve!');
        } else {
            $this->addFlash('error', 'Hírlevél nem található!');
        }
        */

        return $this->redirectToRoute('admin_v1_newsletter');
    }
}
