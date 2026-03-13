<?php

namespace App\Controller\Platform\Shop\Webshop;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\User;
use App\Entity\Platform\Webshop\Webshop;
use App\Form\Platform\Shop\Webshop\WebshopType;
use App\Repository\Platform\Webshop\WebshopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/shop/webshop')]
class WebshopController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_shop_webshop_index';

    #[Route('/', name: 'admin_v1_shop_webshop_index', methods: ['GET'])]
    public function index(WebshopRepository $repository): Response
    {
        $data = $repository->findBy([
            'instance' => $this->currentInstance
        ]);

        $tableHead = [
            'domain' => 'Domain',
            'name' => $this->translator->trans('global.name'),
            'title' => $this->translator->trans('global.title'),
            'theme' => 'Téma',
            'FTPHost' => 'FTP host',
            'FTPUser' => 'FTP user',
            'FTPPath' => 'FTP path',
            'status' => 'Státusz',
        ];

        $actions = ['new', 'edit', 'delete'];

        return $this->platformBackendIndex('Webshop', $tableHead, $data, $actions);
    }

    #[Route('/new/', name: 'admin_v1_shop_webshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(WebshopType::class, null, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }

    #[Route('/edit/{entity}', name: 'admin_v1_shop_webshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Webshop $entity): Response
    {
        $form = $this->createForm(WebshopType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendEdit($request, $form, $entity, self::redirectToRoute);
    }

    #[Route('/delete/{entity}', name: 'admin_v1_shop_webshop_delete', methods: ['GET', 'DELETE'])]
    public function delete(Webshop $entity): Response
    {
        return parent::platformBackendDelete($entity, self::redirectToRoute);
    }
}
