<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Block;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\Website;
use App\Entity\Platform\Website\WebsitePage;
use App\Form\Platform\Website\WebsiteType;
use App\Repository\Platform\BlockRepository;
use App\Repository\Platform\Website\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[IsGranted(User::ROLE_USER)]
#[Route('/{_locale}/admin/v1/website')]
class WebsiteController extends PlatformController
{
    #[Route('/', name: 'admin_v1_website_index', methods: ['GET'])]
    public function index(WebsiteRepository $websiteRepository): Response
    {
        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => 'Honlapok',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'domain' => 'Domain',
                'name' => $this->translator->trans('global.name'),
                'title' => $this->translator->trans('global.title'),
                'theme' => 'Téma',
            ],
            'tableBody' => $websiteRepository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
            'extraActions' => [
                'deploy' => [
                    'route' => 'admin_v1_website_deploy',
                    'label' => 'Deploy',
                ],
                'pages' => [
                    'route' => 'admin_v1_website_pages',
                    'label' => $this->translator->trans('page.pages'),
                ],
            ],
        ]);
    }

    #[Route('/new', name: 'admin_v1_website_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        // handle form submission
        $form = $this->createForm(WebsiteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $website = $form->getData();
            $website->setInstance($this->currentInstance);
            $this->doctrine->getManager()->persist($website);
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Új honlap',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_v1_website_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Website $website): Response
    {
        $existingWebsite = $this->doctrine->getRepository(Website::class)->find($website->getId());
        $existingWebsitePassword = $existingWebsite->getFTPPassword();

        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($website->getFTPPassword() === '' || $website->getFTPPassword() === null) {
                $website->setFTPPassword($existingWebsitePassword);
            }

            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Szerkesztés',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    // delete
    #[Route('/delete/{id}', name: 'admin_v1_website_delete')]
    public function delete(Request $request, Website $website): Response
    {
        // delete all pages of the website
        $pages = $this->doctrine->getRepository(WebsitePage::class)->findBy(['website' => $website]);
        foreach ($pages as $page) {
            $this->doctrine->getManager()->remove($page);
        }
        $this->doctrine->getManager()->flush();

        //if ($this->isCsrfTokenValid('delete' . $website->getId(), $request->request->get('_token'))) {
            $em = $this->doctrine->getManager();
            $em->remove($website);
            $em->flush();

            $this->addFlash('success', 'A honlap sikeresen törölve.');
        //} else {
        //    $this->addFlash('danger', 'A honlap törlése sikertelen.');
        //}

        return $this->redirectToRoute('admin_v1_website_index');
    }

    #[Route('/deploy/{id}', name: 'admin_v1_website_deploy')]
    public function deploy(Website $id): Response
    {
        $website = $id;

        // check if the directory exists
        if (!is_dir('/tmp/' . $website->getId())) {
            mkdir('/tmp/' . $website->getId());
        }

        // check if website has FTP credentials
        if (!$website->getFTPHost() || !$website->getFTPUser() || !$website->getFTPPassword() || !$website->getFTPPath()) {
            $this->addFlash('danger', 'FTP adatok hiányoznak.');

            return $this->redirectToRoute('admin_v1_website_index');
        }

        $slugger = new AsciiSlugger();
        $pages = $this->doctrine->getRepository(WebsitePage::class)->findBy(['website' => $id, 'status' => true]);
        $urls = [];
        $filenames = [];

        foreach ($pages as $page) {

            $pageContent = $page->getContent();
            preg_match_all('/\[block id="(\d+)"\]/', $pageContent, $matches);
            foreach ($matches[1] as $blockId) {
                $blockRepository = $this->doctrine->getRepository(Block::class);
                $block = $blockRepository->findOneBy([
                    'id' => $blockId,
                    'instance' => $this->currentInstance,
                    'status' => true
                ]);
                if ($block) {
                    $pageContent = str_replace('[block id="'.$blockId.'"]', $block->getContent(), $pageContent);
                }
            }

            $htmlContent = $this->renderView('themes/'. $website->getTheme() .'/index.html.twig', [
                'charset' => $website->getCharset(),
                'language' => $website->getLanguage(),
                'title' => $page->getTitle(),
                'keywords' => $website->getMetaKeywords(),
                'description' => $website->getMetaDescription(),
                'content' => $pageContent,
            ]);

            if ($page->getSlug() === '') {
                $slug = $slugger->slug($page->getTitle());
            } else {
                if ($page->getSlug() === '/') {
                    $slug = 'index';
                } else {
                    $slug = $page->getSlug();
                }
            }

            $urls[] = $slug;
            $filenames[] = $slug.'.html';

            // Save the generated HTML content to a temporary file
            $tempFilePath = '/tmp/' . $website->getId() .'/'. $slug . '.html';
            file_put_contents($tempFilePath, $htmlContent);

            $this->pushToFTP(
                $website->getFTPHost(),
                $website->getFTPUser(),
                $website->getFTPPassword(),
                $website->getFTPPath(),
                $tempFilePath,
                $slug.'.html'
            );

            $this->addFlash('success', $page->getTitle(). ' FTP OK.');
        }

        $this->createHtaccessFile($website, $urls, $filenames);

        return $this->redirectToRoute('admin_v1_website_index');
    }

    private function createHtaccessFile(Website $website, array $urls=[], array $filenames=[])
    {
        $content = 'FallbackResource /index.html
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_URI} !\.[a-zA-Z0-9]{1,5}$ [NC]
RewriteCond %{REQUEST_URI} !/$
RewriteRule ^(.*)$ /$1/ [L,R=301]
';

        $i = 0;
        foreach ($urls as $url) {
            $content .= 'RewriteRule ^' . $url . '/$ ' . $filenames[$i] . ' [L,NC]'."\n";
            $i++;
        }

        $tempFilePath = '/tmp/' . $website->getId() . '/.htaccess';
        file_put_contents($tempFilePath, $content);

        $this->pushToFTP(
            $website->getFTPHost(),
            $website->getFTPUser(),
            $website->getFTPPassword(),
            $website->getFTPPath(),
            $tempFilePath,
            '.htaccess'
        );

        $this->addFlash('success', '.htaccess FTP OK.');
    }

    private function pushToFTP($FTPhost, $FTPuser, $FTPpassword, $FTPpath, $content, $filename)
    {
        if ($FTPhost !== 'localhost') {
            $ftp = ftp_connect($FTPhost);
            ftp_login($ftp, $FTPuser, $FTPpassword);
            ftp_pasv($ftp, true);
            ftp_put($ftp, $FTPpath.$filename, $content, FTP_ASCII);
            ftp_close($ftp);
        }
    }
}
