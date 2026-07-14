<?php

namespace App\Controller\Platform\CMS;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CMS\Form;
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
    private const string redirectToRoute = 'admin_v1_cms_form_fill_all';

    #[Route('/', name: 'admin_v1_cms_form_fill_all', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(FormFill::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        $tableHead = [
            'ip' => 'IP',
        ];

        if (count($tableBody) !== 0) {
            $firstData = $tableBody[0]->getData();

            $tableBody = array_map(function ($formFill) {
                $data = $formFill->getData();
                $data['id'] = $formFill->getId();
                $data['created_at'] = $formFill->getCreatedAt()->format('Y-m-d H:i:s');
                $data['ip'] = $formFill->getIp();
                return $data;
            }, $tableBody);

            foreach ($firstData as $key => $value) {
                if (!in_array($key, ['action', 'key', 'formID', 'honeypot', 'robotstop'])) {
                    $tableHead[$key] = $key;
                }
            }
        }

        $tableHead = array_merge($tableHead, $tableHead);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Űrlap kitöltések',
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => [
            ],
        ]);
    }


    #[Route('/{form}/', name: 'admin_v1_cms_form_fill', methods: ['GET'])]
    public function formIndex(Form $form, Request $request): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        //dump($form);

        $fields = $form->getFields();

        $tableHead = [
            'ip' => 'IP',
        ];


        foreach($fields as $field)
        {
            $tableHead[str_replace(' ', '_', $field->getName())] = $field->getName();
        }

        $tableBody = $this->doctrine->getRepository(FormFill::class)->findBy(
            ['instance' => $this->currentInstance]
        );


        if (count($tableBody) !== 0) {
            $firstData = $tableBody[0]->getData();

            $tableBody = array_map(function ($formFill) {
                $data = $formFill->getData();
                $data['id'] = $formFill->getId();
                $data['created_at'] = $formFill->getCreatedAt()->format('Y-m-d H:i:s');
                $data['ip'] = $formFill->getIp();
                return $data;
            }, $tableBody);

            foreach ($firstData as $key => $value) {
                if (!in_array($key, ['action', 'key', 'formID', 'honeypot', 'robotstop'])) {
                    $tableHead[$key] = $key;
                }
            }
        }

        $tableHead = array_merge($tableHead, $tableHead);

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
