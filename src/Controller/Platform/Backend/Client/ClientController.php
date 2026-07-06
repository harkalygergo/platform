<?php

namespace App\Controller\Platform\Backend\Client;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\Client;
use App\Entity\Platform\User;
use App\Form\Platform\ClientType;
use App\Repository\Platform\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/crm/client')]
class ClientController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_crm_client_index';

    #[Route('/', name: 'admin_v1_crm_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Ügyféllista',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'namePrefix' => $this->translator->trans('user.namePrefix'),
                'lastName' => $this->translator->trans('user.lastName'),
                'middleName' => $this->translator->trans('user.middleName'),
                'firstName' => $this->translator->trans('user.firstName'),
                'birthDate' => $this->translator->trans('user.birthDate'),
                'phone' => $this->translator->trans('user.phone'),
                'email' => 'E-mail',
                'comment' => $this->translator->trans('global.comment'),
            ],
            'tableBody' => $clientRepository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $client->setInstance($this->currentInstance);

            $this->doctrine->getManager()->persist($client);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.created'));

            return $this->redirectToRoute(self::redirectToRoute, [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Ügyfél hozzáadása',
            'form' => $form->createView(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);
    }

    #[Route('/edit/{client}', name: 'admin_v1_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.updated'));

            return $this->redirectToRoute(self::redirectToRoute, [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Ügyfél szerkesztése',
            'form' => $form->createView(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);

    }

    #[Route('/delete/{id}', name: 'admin_v1_client_delete', methods: ['GET'])]
    public function delete(Client $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->platformBackendDelete($entity, self::redirectToRoute);
    }
}
