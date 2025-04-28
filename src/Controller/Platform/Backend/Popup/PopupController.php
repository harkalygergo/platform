<?php

namespace App\Controller\Platform\Backend\Popup;

use App\Controller\Platform\Backend\BackendController;
use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Popup\Popup;
use App\Entity\Platform\User;
use App\Form\Platform\Popup\PopupType;
use App\Repository\Platform\ClientRepository;
use App\Repository\Platform\PopupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/popup')]
class PopupController extends PlatformController
{
    #[Route('/', name: 'admin_v1_popup_index', methods: ['GET'])]
    public function index(PopupRepository $repository): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Felugró ablakok',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'name' => 'name',
                'maximumAppearance' => 'Maximális megjelenítések száma',
                'shownCount' => 'Megjelenítések száma',
                'status' => 'Státusz',
                'createdBy' => 'Készítette',
                'updatedBy' => 'Módosította',
            ],
            'tableBody' => $repository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'new',
                'edit',
            ],
        ]);
    }

    // create new function
    #[Route('/new/', name: 'admin_v1_popup_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $popup = new Popup();
        $form = $this->createForm(PopupType::class, $popup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $popup->setCreatedBy($this->getUser());
            $popup->setInstance($this->currentInstance);

            $this->doctrine->getManager()->persist($popup);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.created'));

            return $this->redirectToRoute('admin_v1_popup_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Felugró ablak hozzáadása',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    // create edit function
    #[Route('/edit/{id}', name: 'admin_v1_popup_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Popup $popup): Response
    {
        $form = $this->createForm(PopupType::class, $popup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $popup->setUpdatedAt(new \DateTime());
            $popup->setUpdatedBy($this->getUser());

            $this->doctrine->getManager()->persist($popup);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('action.updated'));

            return $this->redirectToRoute('admin_v1_popup_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Felugró ablak szerkesztése',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }
}
