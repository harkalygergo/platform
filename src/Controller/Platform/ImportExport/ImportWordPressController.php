<?php

namespace App\Controller\Platform\ImportExport;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\WebsitePage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/admin/v1/import/wordpress')]
#[IsGranted(User::ROLE_USER)]
class ImportWordPressController extends PlatformController
{
    private string $postURL = '/wp-json/wp/v2/posts';
    private string $pageURL = '/wp-json/wp/v2/pages';


    #[Route('/import/', name: 'admin_v1_wordpress_import')]
    public function import(): \Symfony\Component\HttpFoundation\Response
    {
        /*
        $domain = 'https://www.digimuhely.hu';
        $website = 4;

        // get JSON from domain
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
            $this->doctrine->getManager()->flush();
        }
        */

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
                $this->doctrine->getManager()->flush();
            }

            return $this->redirectToRoute('admin_v1_website_pages', [
                'id' => $website->getId(),
            ]);
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'WordPress importálás',
            'form' => $form->createView(),
        ]);
    }
}
