<?php

namespace App\Controller\Platform\CRM;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CRM\Testimonial;
use App\Entity\Platform\User;
use App\Form\Platform\CRM\TestimonialType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/crm/testimonial')]
class TestimonialController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_crm_testimonial';

    #[Route('/', name: 'admin_v1_crm_testimonial', methods: ['GET'])]
    public function index()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(Testimonial::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Testimonial',
            'tableHead' => [
                'title' => 'Megnevezés',
                'description' => 'Leírás',
                'content' => 'Tartalom',
                'status' => 'Státusz',
                'createdBy' => 'Létrehozta'
            ],
            'tableBody' => $tableBody,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_crm_testimonial_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        //$entity->setInstance($this->currentInstance);
        $form = $this->createForm(TestimonialType::class);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{entity}', name: 'admin_v1_crm_testimonial_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testimonial $entity): Response
    {
        $form = $this->createForm(TestimonialType::class, $entity);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
    }

    #[Route('/delete/{id}', name: 'admin_v1_crm_testimonial_delete', methods: ['GET'])]
    public function delete(Testimonial $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->platformBackendDelete($entity, self::redirectToRoute);
    }
}
