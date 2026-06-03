<?php

namespace App\Controller\Platform;

use App\Entity\Platform\EmailAccount;
use App\Entity\Platform\User;
use App\Form\Platform\EmailAccountType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/dashboard/email-accounts')]
class EmailAccountController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_dashboard_email_account';

    #[Route('/', name: 'admin_v1_dashboard_email_account')]
    public function index(): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('superadmin'),
            'title' => 'E-mail fiókok',
            'tableHead' => [
                'prefix' => 'Előtag',
                'service' => 'Domain',
                'description' => 'Leírás',
                'status' => 'Status',
            ],
            'tableBody' => $this->doctrine->getRepository(EmailAccount::class)->findBy([
                'instance' => $this->currentInstance
            ]),
            'actions' => ['new', 'edit', 'delete'],
        ]);
    }

    #[\Symfony\Component\Routing\Attribute\Route('/new/', name: 'admin_v1_dashboard_email_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $entity = new EmailAccount();
        $entity->setInstance($this->currentInstance);
        $form = $this->createForm(EmailAccountType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{id}', name: 'admin_v1_dashboard_email_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmailAccount $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        $form = $this->createForm(EmailAccountType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);

        // Peek at the raw POST data BEFORE handleRequest() is called
        if ($request->isMethod('POST')) {
            $form->handleRequest($request); // handle once here

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $form->getData()->getPassword();

                if (!empty($password)) {
                    $user    = $entity->getInstance()->getCode();
                    $domain  = $entity->getService();
                    $account = $entity->getPrefix();

                    $this->runPasswordUpdateScript($user, $domain, $account, $password);
                }

                // Manually do what platformBackendEdit() would do on success
                $this->doctrine->getManager()->flush();
                $this->addFlash('success', $this->translator->trans('action.edited'));
                return $this->redirectToRoute(self::redirectToRoute);
            }
        }

        // On GET, or if form is invalid — let platformBackendEdit handle rendering
        // It will call handleRequest() again but on GET it's a no-op, POST already redirected
        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
    }

    private function runPasswordUpdateScript($user, $domain, $account, $password)
    {
        $scriptPath = '/usr/local/bin/hestia-change-password.sh';

        if (!is_file($scriptPath) || !is_executable($scriptPath)) {
            $this->addFlash('danger', 'HestiaCP wrapper script not found or not executable.');
        } else {
            $process = new Process([
                'sudo', '-n',
                $scriptPath,
                $user,
                $domain,
                $account,
                $password,
            ]);

            $process->setTimeout(10);

            try {
                $process->run();

                if (!$process->isSuccessful()) {
                    $this->addFlash('danger', sprintf(
                        'Password change failed: %s',
                        $process->getErrorOutput()
                    ));
                }
            } catch (\Symfony\Component\Process\Exception\ProcessTimedOutException $e) {
                $this->addFlash('danger', 'Password change timed out.');
            }
        }
    }


    #[Route('/delete/{id}', name: 'admin_v1_dashboard_email_account_delete', methods: ['GET'])]
    public function delete(EmailAccount $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->platformBackendDelete($entity, self::redirectToRoute);
    }
}
