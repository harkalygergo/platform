<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Service;
use App\Entity\Platform\User;
use App\Form\Platform\ServiceType;
use App\Repository\Platform\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_ADMIN)]
class ServiceDomainController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/domains/', name: 'admin_v1_domains')]
    public function index(Request $request): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        // get user's first instance
        $instance = $this->currentInstance;

        // get instances services where type is domain
        $services = (new ServiceRepository($this->doctrine))->findBy(['instance' => $instance, 'type' => 'domain']);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Domainek',
            'tableHead' => [
                'name' => 'Név',
                'description' => 'Leírás',
                'fee' => 'Díj',
                'currency' => 'Pénznem',
                'type' => 'Típus',
                'status' => 'Státusz',
            ],
            'tableBody' => $services,
            'actions' => [
                'new', 'delete'
            ],
        ]);
    }

    // add new domain
    #[Route('/{_locale}/admin/v1/domains/new', name: 'admin_v1_domains_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $domain = new Service();
        $domain->setType('domain');
        $domain->setInstance($this->currentInstance);

        $form = $this->createForm(ServiceType::class, $domain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($domain);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('action.created'));
            return $this->redirectToRoute('admin_v1_domains', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új domain hozzáadása',
            'form' => $form->createView(),
        ]);
    }

    // delete
    #[Route('/{_locale}/admin/v1/domains/delete/{id}', name: 'admin_v1_domains_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Request $request, Service $domain, EntityManagerInterface $entityManager): Response
    {
        //if ($this->isCsrfTokenValid('delete' . $domain->getId(), $request->request->get('_token'))) {
            $entityManager->remove($domain);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('Deleted successfully'));

            return $this->redirectToRoute('admin_v1_domains', [], Response::HTTP_SEE_OTHER);
        //}

        return $this->redirectToRoute('admin_v1_domains', [], Response::HTTP_SEE_OTHER);
    }

}
