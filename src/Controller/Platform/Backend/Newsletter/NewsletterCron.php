<?php

namespace App\Controller\Platform\Backend\Newsletter;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Newsletter\Newsletter;
use App\Entity\Platform\Newsletter\NewsletterSubscriber;
use App\Entity\Platform\User;
use App\Enum\NewsletterStatusEnum;
use App\Repository\Platform\Newsletter\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterCron extends PlatformController
{
    public function __construct(
        RequestStack $requestStack,
        \Doctrine\Persistence\ManagerRegistry $doctrine,
        TranslatorInterface $translator,
        KernelInterface $kernel,
        MailerInterface $mailer,
        LoggerInterface $logger,
        protected EntityManagerInterface $entityManager,
        protected NewsletterRepository $newsletterRepository
    ) {
        parent::__construct($requestStack, $doctrine, $translator, $kernel, $mailer, $logger);
    }

    public function __invoke(): void
    {
        $scheduledNewsletters = $this->newsletterRepository->findScheduledNewsletters();

        foreach ($scheduledNewsletters as $newsletter) {
            $this->sendNewsletter($newsletter);
        }

        $newsletters = $this->entityManager->getRepository(Newsletter::class)->createQueryBuilder('n')
            ->where('n.status = :status')
            ->andWhere('n.sendAt < :now')
            ->setParameter('status', NewsletterStatusEnum::SCHEDULED)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();

        foreach ($newsletters as $newsletter) {
            $this->sendNewsletter($newsletter);
        }
    }

    public function sendNewsletter(Newsletter $newsletter): void
    {
        $newsletterInstance = $newsletter->getInstance();
        $subscribers = $this->entityManager->getRepository(NewsletterSubscriber::class)->findBy(['instance' => $newsletterInstance]);
        $toAddresses = [];

        foreach ($subscribers as $subscriber) {
            $toAddresses[] = $subscriber->getEmail();
        }

        // send the newsletter
        $this->sendMail($toAddresses, $newsletter->getSubject(), $newsletter->getHtmlContent());

        $newsletter->setStatus(NewsletterStatusEnum::SENT);
        $this->entityManager->persist($newsletter);
        $this->entityManager->flush();
    }
}
