<?php

namespace App\Controller\Platform;

use App\Entity\Platform\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[\Symfony\Component\Routing\Attribute\Route('/admin')]
#[IsGranted(User::ROLE_ADMIN)]
class PlatformController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        return $this->render('platform/backend/list.html.twig', [
            'title' => 'Dashboard',
            'tableBody' => [],
        ]);
    }
}
