<?php

namespace App\Controller\Platform\CMS;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\EventRegistration;
use App\Entity\Platform\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/cms/event/registration')]
class EventRegistrationController extends PlatformBackendController
{
    #[Route('/', name: 'admin_v1_cms_event_registration', methods: ['GET'])]
    public function index()
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(EventRegistration::class)->findBy(
            ['instance' => $this->currentInstance]
        );

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Esemény jelentkezők',
            'tableHead' => [
                'event' => 'Esemény',
                'fullName' => 'Teljes név',
                'phoneNumber' => 'Telefonszám',
                'email' => 'E-mail',
                'birthDate' => 'születési dátum',
                'comment' => 'megjegyzés',
                'arrivesWith' => 'Akikkel érkezik'
            ],
            'tableBody' => $tableBody,
            'actions' => [
            ],
        ]);
    }
}
