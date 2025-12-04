<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\BillingProfile;
use App\Entity\Platform\User;
use App\Form\Platform\BillingProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class BillingProfileController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/billing-profiles/', name: 'admin_v1_billing_profiles')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $instances = $user->getInstances();
        $billingProfiles = $this->doctrine->getRepository(BillingProfile::class)->findByUserInstances($instances);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Számlázási profilok',
            'tableHead' => [
                'name' => 'Név',
                'zip' => 'Irányítószám',
                'settlement' => 'Település',
                'address' => 'Cím',
                'vat' => 'Adószám',
                'euVat' => 'Közösségi adószám',
                'billingRegistrationNumber' => 'Nyilvántartási szám',
            ],
            'tableBody' => $billingProfiles,
            'actions' => [
            ],
        ]);
    }

    #[Route('/{_locale}/admin/v1/billing-profiles/add', name: 'admin_v1_billing_profiles_add')]
    public function add(Request $request): Response
    {
        $billingProfile = new BillingProfile();
        $form = $this->createForm(BillingProfileType::class, $billingProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$billingProfile->setUser($this->getUser());
            $this->doctrine->getManager()->persist($billingProfile);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_billing_profiles');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Számlázási profil hozzáadása',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{_locale}/admin/v1/billing-profiles/edit/{id}', name: 'admin_v1_billing_profiles_edit')]
    public function edit(Request $request, int $id): Response
    {
        $billingProfile = $this->doctrine->getRepository(BillingProfile::class)->find($id);
        $form = $this->createForm(BillingProfileType::class, $billingProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->persist($billingProfile);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_billing_profiles');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Számlázási profil szerkesztése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{_locale}/admin/v1/billing-profiles/delete/{id}', name: 'admin_v1_billing_profiles_delete')]
    public function delete(int $id): Response
    {
        $billingProfile = $this->doctrine->getRepository(BillingProfile::class)->find($id);
        $this->doctrine->getManager()->remove($billingProfile);
        $this->doctrine->getManager()->flush();

        return $this->redirectToRoute('admin_v1_billing_profiles');
    }
}
