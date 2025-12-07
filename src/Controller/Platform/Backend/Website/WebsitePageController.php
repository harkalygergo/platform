<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website;
use App\Entity\Platform\Website\WebsitePage;
use App\Form\Platform\Website\WebsitePageType;
use App\Repository\Platform\Website\WebsitePageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/website/pages')]
class WebsitePageController extends PlatformController
{
    #[Route('/', name: 'admin_v1_website_pages')]
    public function index(WebsitePageRepository $websitePageRepository): Response
    {
        //$pagesByWebsite = $websitePageRepository->findByWebsiteId($id->getId());
        $pages = $websitePageRepository->findAll();

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => ' oldalak',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'title' => 'Cím',
                'slug' => 'Slug',
                'status' => 'Státusz',
                'homepage' => 'Főoldal'
            ],
            'tableBody' => $pages,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/{id}/new/', name: 'admin_v1_website_page_new')]
    public function new(Request $request, \App\Entity\Platform\Website\Website $id): Response
    {
        $form = $this->createForm(WebsitePageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $websitePage = $form->getData();
            $websitePage->setWebsite($id);
            $this->doctrine->getManager()->persist($websitePage);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_pages', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új oldal',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    // create edit function
    #[Route('/{id}/edit/{page}', name: 'admin_v1_website_page_edit')]
    public function edit(Request $request, \App\Entity\Platform\Website\Website $id, WebsitePage $page): Response
    {
        $form = $this->createForm(WebsitePageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_pages', [
                'id' => $id->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Oldal szerkesztése',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete/{page}', name: 'admin_v1_website_page_delete')]
    public function delete(Request $request, \App\Entity\Platform\Website\Website $id, WebsitePage $page): Response
    {
        // check if page's website is the same as the current website
        if ($page->getWebsite() !== $id) {
            throw $this->createAccessDeniedException('You do not have permission to delete this page.');
        }

        // check if website's instance is the same as the current instance
        if ($page->getWebsite()->getInstance() !== $this->currentInstance) {
            throw $this->createAccessDeniedException('You do not have permission to delete this page.');
        }

        //if ($request->isMethod('POST')) {
            $this->doctrine->getManager()->remove($page);
            $this->doctrine->getManager()->flush();
        //}

        return $this->redirectToRoute('admin_v1_website_pages', [
            'id' => $id->getId(),
        ]);
    }




    private string $title = '';

    /*
    public function __construct(protected ManagerRegistry $doctrine, TranslatorInterface $translator)
    {
        $this->title = '<i class="bi bi-page"></i> '. $translator->trans('global.page');
    }

    #[Route('/{website}/pages/', name: 'admin_website_page_list')]
    public function list(WebsitePageRepository $repository, Website $website, Request $request): Response
    {
        $dataList = $repository->findByWebsiteId($website->getId());

        $data = [
            'title' => $this->title,
            'dataList' => $dataList,
            'sidebar' => $this->getSidebarMain($request),
        ];

        return $this->render('platform/backend/v1/list.html.twig', $data);
    }
    */

    // create edit form for Website Page
    #[Route('/{website}/pages/edit/{id}', name: 'admin_website_page_edit')]
    public function editregi(Request $request, WebsitePageRepository $repository, Website $website, int $id): Response
    {
        $entity = $repository->find($id);

        // create form for Website Page
        $form = $this->createFormBuilder($entity)
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('metaTitle', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('metaDescription', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('metaKeywords', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('metaRobots', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('metaCanonical', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'global.save',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $data['notification'] = $user->getTitle() . ' sikeresen létrehozva.';
        }

        $data = [
            'title' => $this->title,
            'data' => $entity,
            'form'  => $form->createView(),
            'sidebar' => $this->getSidebarMain($request),
        ];

        return $this->render('platform/backend/v1/form.html.twig', $data);
    }

    public function addNex(Request $request, object $entity, FormInterface $form)
    {

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Website $new */
            $new = $form->getData();
            $new->setCreatedAt(new \DateTimeImmutable('now')); // setting current date and time

            $em = $this->doctrine->getManager();
            $em->persist($new);
            $em->flush();

            $data['notification'] = $new->getTitle() . ' sikeresen létrehozva.';
        }

        $data = [
            'title' => $this->title.'<hr>',
            'form' => $form,
            'sidebar' => $this->getSidebarMain($request),
        ];

        return $this->render('platform/backend/v1/form.html.twig', $data);
    }

    #[Route('/{website}/pages/new/', name: 'admin_website_page_new')]
    public function newregi(Request $request, TranslatorInterface $translator, Website $website)
    {
        $entity = new WebsitePage();

        $form = $this->createFormBuilder($entity)
            // add website as Website entity, set default value as $website
            ->add('website', EntityType::class, [
                'class' => Website::class,
                'choice_label' => 'title',
                'data' => $website,
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    //'disabled' => true,
                ]
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => $translator->trans('global.save'),
                'attr' => [
                    'class' => 'my-1 btn btn-lg btn-success'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Website $new */
            $new = $form->getData();
            $new->setCreatedAt(new \DateTimeImmutable('now')); // setting current date and time

            $em = $this->doctrine->getManager();
            $em->persist($new);
            $em->flush();

            $data['notification'] = $new->getTitle() . ' sikeresen létrehozva.';
        }

        $data = [
            'title' => $this->title.'<hr>',
            'form' => $form,
            'sidebar' => $this->getSidebarMain($request),
        ];

        return $this->render('platform/backend/v1/form.html.twig', $data);
    }

    #[Route('/{id}/multiple/{action}/{ids}', name: 'admin_v1_website_page_multiple')]
    public function multiple(Request $request, \App\Entity\Platform\Website\Website $id, string $action, string $ids)
    {
        $idsArray = explode(',', $ids);

        switch ($action) {
            case 'delete':
                foreach ($idsArray as $id) {
                    $page = $this->doctrine->getRepository(WebsitePage::class)->find($id);
                    if ($page) {
                        $this->doctrine->getManager()->remove($page);
                    }
                }
                $this->doctrine->getManager()->flush();
                break;
            default:
                throw new \Exception('Unknown action: ' . $action);
        }

        return $this->redirectToRoute('admin_v1_website_pages', [
            'id' => $request->get('id'),
        ]);
    }
}

