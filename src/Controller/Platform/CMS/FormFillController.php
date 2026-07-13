<?php

namespace App\Controller\Platform\CMS;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CMS\FormFill;
use App\Entity\Platform\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/cms/formfill')]
class FormFillController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_cms_form_fill';

    #[Route('/', name: 'admin_v1_cms_form_fill', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(FormFill::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        $tableHead = [
            'ip' => 'IP',
        ];
        $firstData = $tableBody[0]->getData();

        foreach ($firstData as $key => $value) {
            if (!in_array($key, ['action', 'formID', 'honeypot', 'robotstop'])) {
                $tableHead[$key] = $key;
            }
        }

        $tableHead = array_merge($tableHead, $tableHead);

        $tableBody = array_map(function ($formFill) {
            $data = $formFill->getData();
            $data['id'] = $formFill->getId();
            $data['created_at'] = $formFill->getCreatedAt()->format('Y-m-d H:i:s');
            $data['ip'] = $formFill->getIp();
            return $data;
        }, $tableBody);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Űrlap kitöltések',
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => [
            ],
        ]);
    }
}
