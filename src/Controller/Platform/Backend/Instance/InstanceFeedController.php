<?php

namespace App\Controller\Platform\Backend\Instance;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Instance\InstanceFeed;
use App\Entity\Platform\User;
use App\Form\Platform\Instance\InstanceFeedType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class InstanceFeedController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/instance/add', name: 'admin_v1_instance_feed_add', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $feed = new InstanceFeed();

        $form = $this->createForm(InstanceFeedType::class, $feed, [
            'action' => $this->generateUrl('admin_v1_instance_feed_add'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feed->setInstance($this->currentInstance);
            $feed->setCreatedBy($this->getUser());
            $entityManager->persist($feed);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_v1_dashboard');
    }
}
