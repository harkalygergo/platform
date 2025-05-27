<?php

namespace App\Controller\Platform\Backend\Block;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Block;
use App\Entity\Platform\User;
use App\Form\Platform\Block\BlockType;
use App\Repository\Platform\BlockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/block')]
class BlockController extends PlatformController
{
    private $instanceRepository;

    #[Route('/', name: 'admin_v1_block_index', methods: ['GET'])]
    public function index(BlockRepository $blockRepository): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Block',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'name' => 'Név',
                'status' => 'Státusz',
            ],
            'tableBody' => $blockRepository->findBy([
                'instance' => $this->currentInstance,
            ]),
            'actions' => [
                'new',
                'edit',
            ],
        ]);
    }

    #[Route('/new', name: 'admin_v1_block_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $block = new Block();
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $block->setInstance($this->currentInstance);
            $block->setCreatedBy($this->getUser());
            $this->doctrine->getManager()->persist($block);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_block_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új blokk létrehozása',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_v1_block_show', methods: ['GET'])]
    public function show(Block $block): Response
    {
        return $this->render('platform/backend/v1/view.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Blokk megtekintése',
            'block' => $block,
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_v1_block_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Block $block): Response
    {
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $block->setUpdatedBy($this->getUser());
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_block_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Blokk szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_v1_block_delete', methods: ['POST'])]
    public function delete(Request $request, Block $block): Response
    {
        if ($this->isCsrfTokenValid('delete' . $block->getId(), $request->request->get('_token'))) {
            $this->doctrine->getManager()->remove($block);
            $this->doctrine->getManager()->flush();
        }

        return $this->redirectToRoute('admin_v1_block_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/instances', name: 'admin_v1_block_instances', methods: ['GET'])]
    public function instances(Block $block): Response
    {
        $instances = $this->instanceRepository->findBy(['block' => $block]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Blokk példányok',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'tableHead' => [
                'name' => 'Név',
                'createdAt' => 'Létrehozva',
                'createdBy' => 'Létrehozta',
            ],
            'tableBody' => $instances,
            'actions' => [
                'edit',
                'delete',
            ],
        ]);
    }
}
