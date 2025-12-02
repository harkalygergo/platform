<?php

namespace App\Controller\Platform;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    #[Route('/platform', name: 'homepage')]
    public function index(Request $request): Response
    {
        return $this->render('platform/backend/list.html.twig', [
        ]);
    }
}
