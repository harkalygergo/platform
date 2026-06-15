<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\CMS\VisitorLog;
use App\Entity\Platform\Order\OrderEmailTemplate;
use App\Entity\Platform\User;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/analytics')]
class VisitorLogController extends PlatformBackendController
{
    private array $tableHead = [
        'visitedAt' => 'Timestamp',
        'userAgent' => 'user agent',
        'ipAddress' => 'IP',
        'url' => 'URL',
        'referrer' => 'Referrer',
        'sessionId' => 'session',
        'source' => 'source',
        'contentId' => 'content ID',
    ];

    #[Route('/', name: 'admin_v1_kpi_index', methods: ['GET'])]
    public function index()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(VisitorLog::class)->findBy(
            [
                'instance' => $this->currentInstance,
            ],
            [
                'visitedAt' => 'DESC'
            ],
            1000
        );

        $this->tableHead['contentType'] = 'Content type';

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Visitor log - összes',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }

    #[Route('/product', name: 'admin_v1_kpi_product', methods: ['GET'])]
    public function index_product()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(VisitorLog::class)->findBy(
            [
                'instance' => $this->currentInstance,
                'contentType' => 'App\Entity\Platform\Ecom\Product'
            ],
            [
                'visitedAt' => 'DESC'
            ],
            1000
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Visitor log - termék',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }

    #[Route('/websitepost', name: 'admin_v1_kpi_websitepost_index', methods: ['GET'])]
    public function index_website_post()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(VisitorLog::class)->findBy(
            [
                'instance' => $this->currentInstance,
                'contentType' => 'App\Entity\Platform\Website\WebsitePost'
            ],
            [
                'visitedAt' => 'DESC'
            ],
            1000
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Visitor log - bejegyzés',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }

    #[Route('/websitecategory', name: 'admin_v1_kpi_websitecategory_index', methods: ['GET'])]
    public function index_website_category()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(VisitorLog::class)->findBy(
            [
                'instance' => $this->currentInstance,
                'contentType' => 'App\Entity\Platform\Website\WebsiteCategory'
            ],
            [
                'visitedAt' => 'DESC'
            ],
            1000
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Visitor log - bejegyzés kategória',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }

    #[Route('/cmspage', name: 'admin_v1_kpi_cmspage_index', methods: ['GET'])]
    public function index_cmspage()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(VisitorLog::class)->findBy(
            [
                'instance' => $this->currentInstance,
                'contentType' => 'App\Entity\Platform\Website\CmsPage'
            ],
            [
                'visitedAt' => 'DESC'
            ],
            1000
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Visitor log - oldal',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }
}
