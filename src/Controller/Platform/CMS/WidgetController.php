<?php

namespace App\Controller\Platform\CMS;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CMS\Widget;
use App\Entity\Platform\EmailAccount;
use App\Entity\Platform\User;
use App\Entity\Platform\Webshop\Webshop;
use App\Form\Platform\CMS\WidgetType;
use App\Form\Platform\Shop\Webshop\WebshopType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/cms/widget')]
class WidgetController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_cms_widget';

    #[Route('/', name: 'admin_v1_cms_widget', methods: ['GET'])]
    public function index()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(Widget::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Widget',
            'tableHead' => [
                'templateCode' => 'Kód',
                'name' => 'Név',
                'description' => 'Leírás',
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

    #[Route('/new/', name: 'admin_v1_cms_widget_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        //$entity->setInstance($this->currentInstance);
        $form = $this->createForm(WidgetType::class);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{entity}', name: 'admin_v1_cms_widget_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Widget $entity): Response
    {
        $form = $this->createForm(WidgetType::class, $entity);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
    }

    #[Route('/delete/{id}', name: 'admin_v1_cms_widget_delete', methods: ['GET'])]
    public function delete(Widget $entity): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($entity->getInstance() !== $this->currentInstance) {
            $this->addFlash('danger', $this->translator->trans('action.not_found'));
            return $this->redirectToRoute(self::redirectToRoute);
        }

        return $this->platformBackendDelete($entity, self::redirectToRoute);
    }
}
