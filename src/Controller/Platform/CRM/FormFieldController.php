<?php

namespace App\Controller\Platform\CRM;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CRM\Form;
use App\Entity\Platform\CRM\FormField;
use App\Entity\Platform\User;
use App\Form\Platform\CRM\FormFieldType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/cms/form/{id}')]
class FormFieldController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_crm_form_field';

    #[Route('/', name: 'admin_v1_crm_form_field', methods: ['GET'])]
    public function index(Form $id, Request $request): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(FormField::class)->findBy(
            ['form' => $id]
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => $id->getName() . ' űrlap elemek',
            'tableHead' => [
                'name' => 'Megnevezés',
                'label' => 'Label',
                'type' => 'Type',
                'position' => 'Position',
                'isRequired' => 'Required?',
                'defaultValue' => 'Default',
                'placeholder' => 'Placeholder',
                'status' => 'Státusz',
            ],
            'tableBody' => $tableBody,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_crm_form_field_new', methods: ['GET', 'POST'])]
    public function new(Form $id, Request $request): Response
    {
        $entity = new FormField;
        $entity->setForm($id);
        //$entity->setInstance($this->currentInstance);
        $form = $this->createForm(FormFieldType::class, $entity);

        return $this->platformBackendNew($request, $form, self::redirectToRoute, ['id' => $id->getId()]);
    }

    #[Route('/edit/{entity}', name: 'admin_v1_crm_form_field_edit', methods: ['GET', 'POST'])]
    public function edit(Form $id, Request $request, FormField $entity): Response
    {
        $form = $this->createForm(FormFieldType::class, $entity);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute, ['id' => $id->getId()]);
    }

    #[Route('/delete/{entity}', name: 'admin_v1_crm_form_field_delete', methods: ['GET'])]
    public function delete(Form $id, FormField $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->platformBackendDelete($entity, self::redirectToRoute, ['id' => $id->getId()]);
    }

}
