<?php

namespace App\Controller\Platform;

use App\Entity\Platform\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class PlatformBackendController extends PlatformController
{
    public function platformBackendIndex(string $tableTitle = '', array $tableHead = [], array $tableBody = [], array $actions = []): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => $tableTitle,
            'tableHead' => $tableHead,
            'tableBody' => $tableBody,
            'actions' => $actions,
        ]);
    }

    public function platformBackendNew(Request $request, FormInterface $form, string $redirectToRoute = ''): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute($redirectToRoute);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $this->translator->trans('global.new'),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    public function platformBackendEdit(Request $request, FormInterface $form, object $entity, string $redirectToRoute = ''): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', $this->translator->trans('action.edited') . ': ' . $entity->getName());

            return $this->redirectToRoute($redirectToRoute);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $this->translator->trans('action.edit') . ': ' . $entity->getName(),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    public function platformBackendDelete(object $entity, string $redirectToRoute): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $em = $this->doctrine->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash('success', $this->translator->trans('action.deleted') . ': ' . $entity->getName());

        return $this->redirectToRoute($redirectToRoute);
    }

}
