<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformBackendController;
use App\Entity\Platform\User;
use App\Form\Platform\UserType;
use App\Repository\Platform\InstanceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/instance/user')]
class InstanceUserController extends PlatformBackendController
{
    private const string redirectToRoute = 'admin_v1_sys_instance_user';

    #[Route('/', name: 'admin_v1_sys_instance_user', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $tableBody = $this->doctrine->getRepository(User::class)->findByInstanceResults($this->currentInstance);

        $tableHead = [
            'username' => 'username',
            'email' => 'email',
            'roles' => 'roles',
            'isActive' => 'isActive',
            'lastLogin' => 'lastLogin',
            'lastActivation' => 'lastActivation',
            'namePrefix' => 'prefix',
            'firstName' => 'firstName',
            'middleName' => 'middleName',
            'lastName' => 'lastName',
            'nickName' => 'nickName',
            'birthName' => 'birthName',
            'birthDate' => 'birthDate',
            'position' => 'position',
            'phone' => 'phone',
            'profileImageUrl' => 'profileImageUrl',
        ];

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Rendszerfelhasználók',
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => [
                'new',
            ],
        ]);
    }

    #[Route('/new/', name: 'admin_v1_sys_instance_user_new', methods: ['GET', 'POST'])]
    public function newInstanceUser(Request $request, InstanceRepository $instanceRepository)
    {
        $this->denyAccessUnlessUserHasInstance();

        if ($this->currentInstance->getPlan()==='FREE') {
            $this->addFlash('danger', 'FREE csomagban nem engedélyezett több felhasználó létrehozása. Kérjük, válasszon egy másik csomagot a további felhasználók hozzáadásához.');

            return $this->redirectToRoute(self::redirectToRoute);
        }

        $entity = new User();
        $password = bin2hex(random_bytes(16));
        $entity->setPassword($password);
        $entity->setRoles([User::ROLE_USER]);
        $entity->addInstance($this->currentInstance);
        $entity->addInstance($instanceRepository->findOneBy(['id' => 1]));

        $form = $this->createForm(UserType::class, $entity, [
            'currentInstance' => $this->currentInstance,
        ]);

        return $this->platformBackendNew($request, $form, self::redirectToRoute);
    }
}
