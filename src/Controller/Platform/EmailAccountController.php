<?php

namespace App\Controller\Platform;

use App\Entity\Platform\EmailAccount;
use App\Entity\Platform\Order\OrderEmailTemplate;
use App\Entity\Platform\User;
use App\Form\Platform\EmailAccountType;
use PharIo\Manifest\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $form = $this->createForm(EmailAccountType::class, $entity);

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

        $form = $this->createForm(EmailAccountType::class, $entity);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
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
