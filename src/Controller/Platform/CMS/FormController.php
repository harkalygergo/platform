<?php

namespace App\Controller\Platform\CMS;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CMS\Form;
use App\Entity\Platform\User;
use App\Form\Platform\CMS\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/cms/form')]
class FormController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_cms_form';

    #[Route('/', name: 'admin_v1_cms_form', methods: ['GET'])]
    public function index()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(Form::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Űrlap',
            'tableHead' => [
                'name' => 'Megnevezés',
                'code' => 'Kód',
                'notificationEmail' => 'E-mail',
                'status' => 'Státusz',
                'shortcode' => 'Beágyazó kód',
                'apiKey' => 'API Key',
            ],
            'tableBody' => $tableBody,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
            'extraActions' => [
                [
                    'label' => 'alma',
                    'route' => 'admin_v1_cms_form_field'
                ]
            ]
        ]);
    }

    #[Route('/new/', name: 'admin_v1_cms_form_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        //$entity->setInstance($this->currentInstance);
        $form = $this->createForm(FormType::class);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{entity}', name: 'admin_v1_cms_form_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Form $entity): Response
    {
        $form = $this->createForm(FormType::class, $entity);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
    }

    #[Route('/delete/{id}', name: 'admin_v1_cms_form_delete', methods: ['GET'])]
    public function delete(Form $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->platformBackendDelete($entity, self::redirectToRoute);
    }
}
