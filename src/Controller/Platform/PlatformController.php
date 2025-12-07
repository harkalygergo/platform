<?php

namespace App\Controller\Platform;

use App\Controller\Platform\Backend\SidebarController;
use App\Entity\Platform\Instance;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PlatformController extends AbstractController
{
    protected ?SidebarController $sidebarController = null;
    protected ?Instance $currentInstance;

    public function __construct(
        protected RequestStack $requestStack,
        protected \Doctrine\Persistence\ManagerRegistry $doctrine,
        protected TranslatorInterface $translator,
        protected KernelInterface $kernel,
        protected MailerInterface $mailer,
        protected LoggerInterface $logger
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
            $this->sidebarController = new SidebarController($this->requestStack, $this->doctrine, $this->translator, $this->kernel, $this->mailer, $this->logger);
        }

        return $this->sidebarController;
    }

    public function sendMail(array $toAddresses = [], $subject = '', $emailBody = '', $fromAddress = null, $emailHTMLBody = '')
    {
        if (empty($toAddresses)) {
            $toAddresses = explode(',', $_ENV['MAIL_TO']);
        }

        if (empty($subject)) {
            $subject = $_ENV['EMAIL_SUBJECT'];
        }

        if (empty($emailBody)) {
            $emailBody = $_ENV['EMAIL_BODY'];
        }

        if (empty($emailHTMLBody)) {
            // replace \n to <br> for HTML
            $emailHTMLBody = str_replace("\n", '<br>', $emailBody);
            //$emailHTMLBody = $_ENV['EMAIL_HTML_BODY'];
        }

        if (empty($fromAddress)) {
            $fromAddress = $_ENV['EMAIL_FROM'];
        }

        foreach (explode(',', $_ENV['MAIL_COPY']) as $toAddress) {
            $toAddresses[] = $toAddress;
        }

        foreach ($toAddresses as $toAddress) {
            $emailUniqueBody = '';
            $emailUniqueBody .= "\n \n \n ============= \n\n";
            $emailUniqueBody .= "\n TO: ". $toAddress;
            $emailUniqueBody .= "\n DATETIME: ". date('Y-m-d H:i:s');
            $emailUniqueBody .= "\n EMAIL_ID: ". time()."-".uniqid();

            // replace \n to <br> for HTML as $emailHTMLUniqueBody
            $emailHTMLUniqueBody = str_replace("\n", '<br>', $emailUniqueBody);

            $email = (new Email())
                ->from(Address::create($fromAddress))
                ->to($toAddress)
                ->replyTo($_ENV['EMAIL_REPLY_TO'])
                //->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->text($emailBody.$emailUniqueBody)
                ->html($emailHTMLBody.$emailHTMLUniqueBody)
            ;

            $this->mailer->send($email);
            $this->logger->info('Sending email', ['email' => $emailBody.$emailUniqueBody]);
        }
    }







    #[Route('/homepage', name: 'homepage', methods: ['GET'])]
    public function dashboard(Request $request): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Dashboard',
            'tableHead' => [],
            'tableBody' => [],
        ]);
    }
}
