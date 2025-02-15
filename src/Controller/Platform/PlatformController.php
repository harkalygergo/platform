<?php

namespace App\Controller\Platform;

use App\Controller\Platform\Backend\SidebarController;
use App\Entity\Platform\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class PlatformController extends AbstractController
{
    protected ?SidebarController $sidebarController = null;
    protected ?Instance $currentInstance;
    public function __construct(
        protected RequestStack $requestStack,
        protected \Doctrine\Persistence\ManagerRegistry $doctrine,
        protected TranslatorInterface $translator,
        protected KernelInterface $kernel
    ) {
        if (isset($_COOKIE['currentInstance'])) {
            $instance = $this->doctrine->getRepository(Instance::class)->find($_COOKIE['currentInstance'] ?? null);
        }

        $this->currentInstance = $instance ?? null;
    }

    public function getPlatformBasicEnviroments()
    {
        return [
            'robots'        => '',
            'keywords'      => '',
            'description'   => '',
            'title'         => 'PLATFORM',
            'locale'        => $this->requestStack->getCurrentRequest()->getLocale(),
            'request'       => $this->requestStack->getCurrentRequest()->query->all(),
            'ip'            => $this->requestStack->getCurrentRequest()->getClientIp(),
            'userAgent'     => $this->requestStack->getCurrentRequest()->headers->get('User-Agent'),
        ];
    }

    public function getSidebarController(): SidebarController
    {
        if (!$this->sidebarController) {
            $this->sidebarController = new SidebarController($this->requestStack, $this->doctrine, $this->translator, $this->kernel);
        }

        return $this->sidebarController;
    }

    public function sendMail($mailer, $logger, $toAddresses = [], $subject = '', $emailBody = '')
    {
        foreach (explode(',', $_ENV['MAIL_COPY']) as $toAddress) {
            $toAddresses[] = $toAddress;
        }

        foreach ($toAddresses as $toAddress) {
            $emailUniqueBody = '';
            $emailUniqueBody .= "\n \n \n ============= \n\n";
            $emailUniqueBody .= "\n TO: ". $toAddress;
            $emailUniqueBody .= "\n DATETIME: ". date('Y-m-d H:i:s');
            $emailUniqueBody .= "\n EMAIL_ID: ". time()."-".uniqid();

            $email = (new Email())
                ->from(Address::create($_ENV['EMAIL_FROM']))
                ->to($toAddress)
                ->replyTo($_ENV['EMAIL_REPLY_TO'])
                //->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->text($emailBody.$emailUniqueBody);

            $logger->info('Sending email', ['email' => $emailBody.$emailUniqueBody]);
            $mailer->send($email);
        }
    }

}
