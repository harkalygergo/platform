<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private array $siteConfig = [
        'siteLanguage'      => 'en',
        'siteTitle'         => 'BrandCom Studio',
        'siteDescription'   => '',
        'siteKeywords'      => '',
        'metaRobots'        => 'index, follow',
    ];

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $this->siteConfig['content'] = '<h1>Hello World!</h1>';
        $this->siteConfig['description'] = 'alpha, gamma';

        return $this->render('frontend/alpha/index.html.twig', $this->siteConfig);
    }
}
