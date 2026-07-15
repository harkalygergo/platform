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
    protected function platformBackendIndex(string $tableTitle = '', array $tableHead = [], array $tableBody = [], array $actions = []): Response
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

    protected function platformBackendNew(Request $request, FormInterface $form, string $redirectToRoute = '', ?array $redirectToRouteParameters = null): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();

            if (method_exists($entity, 'setInstance')) {
                $entity->setInstance($this->currentInstance);
            } elseif (method_exists($entity, 'addInstance')) {
                $entity->addInstance($this->currentInstance);
            } else {
                throw new \Exception('Entity does not have setInstance or addInstance method');
            }

            $this->doctrine->getManager()->persist($entity);
            $this->doctrine->getManager()->flush();

            if ($redirectToRouteParameters) {
                return $this->redirectToRoute($redirectToRoute, $redirectToRouteParameters);
            }

            return $this->redirectToRoute($redirectToRoute);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $this->translator->trans('global.new'),
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    protected function platformBackendEdit(Request $request, FormInterface $form, object $entity, string $redirectToRoute = '', ?array $redirectToRouteParameters=null): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $form->handleRequest($request);

        $title = $this->translator->trans('action.edited').': ';
        if (method_exists($entity, 'getName')) {
            $title .= $entity->getName();
        } else {
            $title .= '#' . $entity->getId();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', $title);

            if ($redirectToRouteParameters) {
                return $this->redirectToRoute($redirectToRoute, $redirectToRouteParameters);
            }

            return $this->redirectToRoute($redirectToRoute);
        }

        $title = $this->translator->trans('action.edit') . ': ';

        if (method_exists($entity, 'getName')) {
            $title .= $entity->getName();
        } else {
            $title .= '#' . $entity->getId();
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => $title,
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    protected function platformBackendDelete(object $entity, string $redirectToRoute, ?array $redirectToRouteParameters=null): Response
    {
        $this->denyAccessUnlessUserHasInstance();

        $title = $this->translator->trans('action.deleted');
        if (method_exists($entity, 'getName')) {
            $title .= $entity->getName();
        }
        elseif (method_exists($entity, 'getTitle')) {
            $title .= $entity->getTitle();
        }
        else {
            $title .= ' #' . $entity->getId();
        }

        $em = $this->doctrine->getManager();
        $em->remove($entity);
        $em->flush();

        $this->addFlash('success', $title);

        if ($redirectToRouteParameters) {
            return $this->redirectToRoute($redirectToRoute, $redirectToRouteParameters);
        }

        return $this->redirectToRoute($redirectToRoute);
    }

}
