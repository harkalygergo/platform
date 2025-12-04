<?php

namespace App\Controller\Platform\Backend\Client;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Client;
use App\Entity\Platform\User;
use App\Form\Platform\ClientType;
use App\Repository\Platform\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/client')]
class ClientController extends PlatformController
{
    #[Route('/', name: 'admin_v1_client_index', methods: ['GET'])]
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

            return $this->redirectToRoute('admin_v1_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Ügyfél hozzáadása',
            'form' => $form->createView(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);
    }

    // make an edit route and form
    #[Route('/edit/{client}', name: 'admin_v1_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.updated'));

            return $this->redirectToRoute('admin_v1_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Ügyfél szerkesztése',
            'form' => $form->createView(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);

    }


    /*

    #[Route('/{id}', name: 'admin_v1_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('platform/backend/v1/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_v1_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientService $clientService): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientService->save($client);

            $this->addFlash('success', 'action.updated');

            return $this->redirectToRoute('admin_v1_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_v1_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientService $clientService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientService->delete($client);

            $this->addFlash('success', 'action.deleted');
        }

        return $this->redirectToRoute('admin_v1_client_index', [], Response::HTTP_SEE_OTHER);
    }
    */
}
