<?php

namespace App\Controller\Platform\Backend\Task;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Task\Task;
use App\Entity\Platform\User;
use App\Form\Platform\Task\TaskType;
use App\Form\Platform\Website\WebsiteType;
use App\Repository\Platform\Task\TaskRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/task')]
class TaskController extends PlatformController
{
    #[Route('/', name: 'admin_v1_task_index', methods: ['GET'])]
    public function index(TaskRepository $repository, Request $request): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Feladatok',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'title' => 'Cím',
                'description' => 'Leírás',
                'priority' => 'Prioritás',
            ],
            'tableBody' => $repository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'new',
                'edit',
                'view',
                'delete',
            ],
        ]);
    }

    // create view function with content.html.twig, content is Task title and description
    #[Route('/view/{id<\d+>}/', name: 'admin_v1_task_view')]
    public function view(Task $task): Response
    {
        return $this->render('platform/backend/v1/content.html.twig', [
            'title' => $task->getTitle(),
            'content' => $task->getDescription(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);
    }

    #[Route('/new/', name: 'admin_v1_task_new')]
    public function new(Request $request, TranslatorInterface $translator)
    {
        $entity = new Task();

        $form = $this->createForm(TaskType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setInstance($this->currentInstance);
            $entity->setCreatedBy($this->getUser());

            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_task_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új feladat',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id<\d+>}/', name: 'admin_v1_task_edit')]
    public function edit(Request $request, Task $task)
    {
        $form = $this->createForm(TaskType::class, $task, [
            'currentInstance' => $this->currentInstance,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->persist($task);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_task_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új feladat',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);

    }

    // create delete function
    #[Route('/delete/{id<\d+>}/', name: 'admin_v1_task_delete')]
    public function delete(Task $task): Response
    {
        $this->doctrine->getManager()->remove($task);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_v1_task_index');
    }
}
