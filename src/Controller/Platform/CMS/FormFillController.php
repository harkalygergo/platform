<?php

namespace App\Controller\Platform\CMS;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CMS\Form;
use App\Entity\Platform\User;
use App\Repository\Platform\CRM\FormFillRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/crm/formfill')]
class FormFillController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_crm_form_fill_all';

    #[Route('/', name: 'admin_v1_crm_form_fill_all', methods: ['GET'])]
    public function index(FormFillRepository $formFillRepository): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $formFillRepository->findByCreatedAtDesc($this->currentInstance);

        $tableHead = [
            'ip' => 'IP',
        ];

        if (count($tableBody) !== 0) {
            $firstData = $tableBody[0]->getData();

            $tableBody = array_map(function ($formFill) {
                $data = $formFill->getData();
                $data['id'] = $formFill->getId();
                $data['form'] = $formFill->getForm()->getName();
                $data['createdAt'] = $formFill->getCreatedAt();
                $data['ip'] = $formFill->getIp();
                return $data;
            }, $tableBody);

            foreach ($firstData as $key => $value) {
                if (!in_array($key, ['action', 'key', 'formID', 'honeypot', 'robotstop'])) {
                    $tableHead[$key] = $key;
                }
            }
        }

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Űrlap kitöltések',
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => [
            ],
        ]);
    }

    #[Route('/{id}/', name: 'admin_v1_crm_form_fill', methods: ['GET'])]
    public function formIndex(Form $id, FormFillRepository $formFillRepository): Response
    {
        $this->denyAccessUnlessUserHasInstance();
        $fields = $id->getFields();

        $tableHead = [
            'ip' => 'IP',
        ];

        foreach($fields as $field)
        {
            $tableHead[str_replace(' ', '_', $field->getName())] = $field->getName();
        }

        $tableBody = $formFillRepository->findByCreatedAtDesc($this->currentInstance);

        if (count($tableBody) !== 0) {
            $tableBody = array_map(function ($formFill) {
                $data = $formFill->getData();
                $data['id'] = $formFill->getId();
                $data['createdAt'] = $formFill->getCreatedAt();
                $data['ip'] = $formFill->getIp();
                return $data;
            }, $tableBody);
        }

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => $id->getName() . ' űrlap kitöltések',
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => [
            ],
        ]);
    }
}
