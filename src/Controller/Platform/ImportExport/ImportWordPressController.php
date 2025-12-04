<?php

namespace App\Controller\Platform\ImportExport;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\WebsiteCategory;
use App\Entity\Platform\Website\WebsitePage;
use App\Entity\Platform\Website\WebsitePost;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/import/wordpress')]
#[IsGranted(User::ROLE_USER)]
class ImportWordPressController extends PlatformController
{
    private string $postURL = '/wp-json/wp/v2/posts';
    private string $pageURL = '/wp-json/wp/v2/pages';
    private string $categoryURL = '/wp-json/wp/v2/categories';

    #[Route('/import/', name: 'admin_v1_wordpress_import')]
    public function import(): Response
    {
        // create a new form with "domain" input and render with templates/platform/backend/v1/form.html.twig
        $form  = $this->createFormBuilder()
            ->add('website', EntityType::class, [
                'class' => 'App\Entity\Platform\Website\Website',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('w')
                        ->where('w.instance = :instance')
                        ->setParameter('instance', $this->currentInstance);
                },
                'choice_label' => function ($website) {
                    return $website->getName(). " (" . $website->getDomain() . ")";
                },
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Weboldal',
                'required' => true,
            ])

            ->add('domain', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://domain.tld',
                ],
                'label' => 'Domain',
                'required' => true,
                'help' => 'A domain, ahonnan az importálni szeretnél. Pl.: https://domain.tld',
                // constraint to check if the domain is valid
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Url([
                        'message' => 'A domain nem érvényes URL.',
                    ]),
                ],
            ]);
        $form = $form->getForm();
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $domain = $data['domain'];
            $website = $data['website'];

            $this->importPagesFromWordPress($domain, $website);
            $categoryStructureToPostImport = $this->importCategoriesFromWordPress($domain, $website);
            $this->importPostsFromWordPress($domain, $website, $categoryStructureToPostImport);

            return $this->redirectToRoute('admin_v1_website_index', [
                'id' => $website->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'WordPress importálás',
            'form' => $form->createView(),
        ]);
    }

    private function importPagesFromWordPress($domain, $website = null): void
    {
        $json = file_get_contents($domain . $this->pageURL);
        if ($json === false) {
            throw new \Exception('Could not get JSON from ' . $domain . $this->pageURL);
        }
        $json = json_decode($json, true);

        foreach ($json as $pages) {
            $page = new WebsitePage();

            $page->setTitle($pages['title']['rendered']);
            $page->setContent($pages['content']['rendered']);
            $page->setSlug($pages['slug']);
            $page->setInstance($this->currentInstance);
            $page->setWebsite($this->doctrine->getRepository('App\Entity\Platform\Website\Website')->find($website));

            $this->doctrine->getManager()->persist($page);
        }
        $this->doctrine->getManager()->flush();
    }

    private function importCategoriesFromWordPress($domain, $website = null): array
    {
        $categoryStructureToPostImport = [];

        $json = file_get_contents($domain . $this->categoryURL);
        if ($json === false) {
            throw new \Exception('Could not get JSON from ' . $domain . $this->categoryURL);
        }
        $json = json_decode($json, true);

        foreach ($json as $categoryData) {
            $categoryStructureToPostImport[$categoryData['id']] = $categoryData['slug'];
            $category = new WebsiteCategory();
            $category->setTitle($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setContent($categoryData['description'] ?? '');
            $category->setInstance($this->currentInstance);
            $category->setWebsite($this->doctrine->getRepository('App\Entity\Platform\Website\Website')->find($website));
            $this->doctrine->getManager()->persist($category);
        }
        $this->doctrine->getManager()->flush();

        return $categoryStructureToPostImport;
    }

    private function importPostsFromWordPress($domain, $website = null, $categoryStructureToPostImport = []): void
    {
        $json = file_get_contents($domain . $this->postURL);
        if ($json === false) {
            throw new \Exception('Could not get JSON from ' . $domain . $this->postURL);
        }
        $json = json_decode($json, true);

        $websiteCategories = [];
        $websiteCategoryRepository = $this->doctrine->getRepository('App\Entity\Platform\Website\WebsiteCategory');
        foreach($websiteCategoryRepository->findAll() as $websiteCategory) {
            $websiteCategories[$websiteCategory->getSlug()] = $websiteCategory;
        }

        foreach ($json as $posts) {
            $post = new WebsitePost();
            $post->setTitle($posts['title']['rendered']);
            $post->setContent($posts['content']['rendered']);
            $post->setSlug($posts['slug']);
            $post->setInstance($this->currentInstance);
            $post->setWebsite($this->doctrine->getRepository('App\Entity\Platform\Website\Website')->find($website));
            $post->setStatus($posts['status'] ?? 'draft'); // Default to 'draft' if status is not set
            $post->setMetaDescription(substr(strip_tags($posts['excerpt']['rendered']), 0, 255) ?? '');

            foreach ($posts['categories'] as $category) {
                $externalCategory = $categoryStructureToPostImport[$category];
                $internalCategory = $websiteCategories[$externalCategory] ?? null;

                if (!is_null($internalCategory)) {
                    $post->addCategory($internalCategory);
                }
            }

            $this->doctrine->getManager()->persist($post);
        }
        $this->doctrine->getManager()->flush();
    }
}
