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
        'url' => 'URL',
        'referrer' => 'Referrer',
        'ipAddress' => 'IP',
        'sessionId' => 'session',
        'userAgent' => 'user agent',
        'source' => 'source',
        'contentId' => 'content ID',
    ];

    #[Route('/cmspage', name: 'admin_v1_analytics_cmspage_index', methods: ['GET'])]
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
            'title' => 'Visitor log - bejegyzés',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }

    #[Route('/websitepost', name: 'admin_v1_analytics_websitepost_index', methods: ['GET'])]
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
            'title' => 'Visitor log - oldal',
            'tableHead' => $this->tableHead,
            'tableBody' => $tableBody,
            'actions' => [],
        ]);
    }

    #[Route('/websitecategory', name: 'admin_v1_analytics_websitecategory_index', methods: ['GET'])]
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
    }}
