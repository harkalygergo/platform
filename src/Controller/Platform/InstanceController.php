<?php

namespace App\Controller\Platform;

use App\Entity\Platform\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class InstanceController extends AbstractController
{
    #[Route('/{_locale}/admin/intranet/', name: 'admin_show_intranet')]
    public function showIntranet(UserInterface $user): Response
    {
        $currentInstance = $user->getDefaultInstance();

        return $this->render('platform/backend/v1/content.html.twig', [
            'title' => 'Intranet',
            'content' => $currentInstance->getIntranet() ?? ''
        ]);
    }


}
