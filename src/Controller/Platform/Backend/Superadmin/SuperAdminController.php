<?php

namespace App\Controller\Platform\Backend\Superadmin;

use App\Controller\Platform\PlatformController;
use App\Controller\Platform\Backend\SidebarController;
use App\Entity\Platform\User;
use App\Repository\OrderRepository;
use App\Repository\Platform\BillingProfileRepository;
use App\Repository\Platform\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_SUPERADMIN)]
class SuperAdminController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/superadmin/users', name: 'admin_v1_superadmin_users')]
    public function index(): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => (new SidebarController($this->requestStack, $this->doctrine, $this->translator))->getSidebarMenu(),
            'title' => 'Felhasználók',
            'tableHead' => [
                'namePrefix' => 'Előtag',
                'lastName' => 'Vezetéknév',
                'firstName' => 'Keresztnév',
                'nickName' => 'Becenév',
                'position' => 'Beosztás',
                'birthDate' => 'Születési dátum',
                'phone' => 'Telefon',
                'email' => 'E-mail',
                'status' => 'Státusz',
                'roles' => 'Szerepkörök',
                'lastLogin' => 'Utolsó belépés',
                'lastActivation' => 'Utolsó aktivitás',
            ],
            'tableBody' => (new UserRepository($this->doctrine))->findAll(),
            'actions' => [
                'edit' => 'Szerkesztés',
                'delete' => 'Törlés',
            ],
        ]);
    }

    #[Route('/{_locale}/admin/v1/superadmin/billing-profiles', name: 'admin_v1_superadmin_billing_profiles')]
    public function superAdminBillingProfileList(): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => (new SidebarController($this->requestStack, $this->doctrine, $this->translator))->getSidebarMenu(),
            'title' => 'Számlázási fiókok',
            'tableHead' => [
                'name' => 'Név',
                'zip' => 'Irányítószám',
                'settlement' => 'Település',
                'address' => 'Cím',
                'vat' => 'Adószám',
                'euVat' => 'EU adószám',
                'email' => 'E-mail',
            ],
            'tableBody' => (new BillingProfileRepository($this->doctrine))->findAll(),
            'actions' => [
                'edit' => 'Szerkesztés',
                'delete' => 'Törlés',
            ],
        ]);
    }

    // get all orders
    #[Route('/{_locale}/admin/v1/superadmin/orders', name: 'admin_v1_superadmin_orders')]
    public function superAdminOrderList(): Response
    {
        // if user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => (new SidebarController($this->requestStack, $this->doctrine, $this->translator))->getSidebarMenu(),
            'title' => 'Rendelések',
            'tableHead' => [
                'createdBy' => 'Felhasználó',
                'billingProfile' => 'Számlázási fiók',
                'paymentStatus' => 'Fizetési státusz',
                'total' => 'Total',
                'comment' => 'Megjegyzés',
            ],
            'tableBody' => (new OrderRepository($this->doctrine))->findAll(),
            'actions' => [
                'edit' => 'Szerkesztés',
                'delete' => 'Törlés',
            ],
        ]);
    }
}
