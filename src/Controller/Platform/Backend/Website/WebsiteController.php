<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Block;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\Website;
use App\Entity\Platform\Website\WebsitePage;
use App\Entity\Platform\Website\WebsitePost;
use App\Form\Platform\Website\WebsiteType;
use App\Repository\Platform\Website\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
                'FTPHost' => 'FTP Host',
                //'FTPUser' => 'FTP Felhasználó',
                //'FTPPath' => 'FTP Elérési út',
                'status' => 'Státusz',
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
                    'label' => '<i class="bi bi-code-slash"></i> Deploy',
                ],
                'posts' => [
                    'route' => 'admin_v1_website_posts',
                    'label' => $this->translator->trans('web.posts'),
                ],
                'pages' => [
                    'route' => 'admin_v1_website_pages',
                    'label' => $this->translator->trans('page.pages'),
                ],
                'categories' => [
                    'route' => 'admin_v1_website_categories',
                    'label' => $this->translator->trans('web.categories'),
                ],
                'menus' => [
                    'route' => 'admin_v1_website_menus',
                    'label' => $this->translator->trans('web.menus'),
                ],
                'media' => [
                    'route' => 'admin_v1_website_media',
                    'label' => $this->translator->trans('media'),
                ]
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

            //$this->addFlash('success', 'A honlap sikeresen törölve.');
        //} else {
        //    //$this->addFlash('danger', 'A honlap törlése sikertelen.');
        //}

        return $this->redirectToRoute('admin_v1_website_index');
    }

    // multiple delete
    #[Route('/multiple/{action}/{ids}', name: 'admin_v1_website_multiple')]
    public function multiple(Request $request, string $action, string $ids): Response
    {
        $idsArray = explode(',', $ids);

        if ($action === 'delete') {
            foreach ($idsArray as $website) {
                $website = $this->doctrine->getRepository(Website::class)->find($website);

                // delete all pages of the website
                $pages = $this->doctrine->getRepository(WebsitePage::class)->findBy(['website' => $website]);
                foreach ($pages as $page) {
                    $this->doctrine->getManager()->remove($page);
                }

                // delete all posts of the website
                $posts = $this->doctrine->getRepository(WebsitePost::class)->findBy(['website' => $website]);
                foreach ($posts as $post) {
                    $this->doctrine->getManager()->remove($post);
                }

                // delete all categories of the website
                $categories = $this->doctrine->getRepository('App\Entity\Platform\Website\WebsiteCategory')->findBy(['website' => $website]);
                foreach ($categories as $category) {
                    $this->doctrine->getManager()->remove($category);
                }

                $this->doctrine->getManager()->flush();

                // remove the website
                $this->doctrine->getManager()->remove($website);
            }
            $this->doctrine->getManager()->flush();

            //$this->addFlash('success', 'A kiválasztott honlap(ok) sikeresen törölve.');
        }

        return $this->redirectToRoute('admin_v1_website_index');
    }

    #[Route('/deploy/{id}', name: 'admin_v1_website_deploy')]
    public function deploy(Website $id): void
    {
        $website = $id;

        // check if the directory exists
        if (!is_dir('/tmp/' . $website->getId())) {
            mkdir('/tmp/' . $website->getId());
        }

        // check if website has FTP credentials
        if (!$website->getFTPHost() || !$website->getFTPUser() || !$website->getFTPPassword() || !$website->getFTPPath()) {
            //$this->addFlash('danger', 'FTP adatok hiányoznak.');

            //return $this->redirectToRoute('admin_v1_website_index');
        }

        $flashText = '';
        $slugger = new AsciiSlugger();
        $urls = [];
        $filenames = [];

        $categories = $this->doctrine->getRepository('App\Entity\Platform\Website\WebsiteCategory')->findBy(['website' => $website, 'status' => true]);
        $pages = $this->doctrine->getRepository(WebsitePage::class)->findBy(['website' => $website, 'status' => true]);
        // get all menus of the website, order by position
        $menus = $this->doctrine->getRepository('App\Entity\Platform\Website\Menu')->findBy(['website' => $website, 'status' => true], ['position' => 'ASC']);
        $events = $this->doctrine->getRepository('App\Entity\Platform\Event')->findUpcoming($website, 100);

        $this->deployStylesheet($website);
        $this->deployPages($website, $slugger, $urls, $filenames, $flashText, $categories, $pages, $menus, $events);
        $this->deployPosts($website, $slugger, $urls, $filenames, $flashText, $categories, $pages, $menus);
        $this->deployCategories($website, $slugger, $urls, $filenames, $flashText, $categories, $pages, $menus);
        $this->deployEvents($website, $slugger, $urls, $filenames, $flashText, $categories, $events, $menus);

        //$this->addFlash('success', $flashText);

        $this->createHtaccessFile($website, $urls, $filenames);

        //return $this->redirectToRoute('admin_v1_website_index');
    }

    public function deployEvents(Website $website, $slugger, &$urls, &$filenames, &$flashText, $categories, $events, $menus)
    {
        foreach ($events as $event) {
            $eventContent = $this->renderView('themes/' . $website->getTheme() . '/event.html.twig', [
                'website' => $website,
                'charset' => $website->getCharset(),
                'language' => $website->getLanguage(),
                'title' => $event->getTitle(),
                'keywords' => $website->getMetaKeywords(),
                'description' => $website->getMetaDescription(),
                'event' => $event,
                'menus' => $menus,
                'categories' => $categories,
                'events' => $events,
            ]);

            if ($event->getSlug() === '') {
                $slug = (new AsciiSlugger())->slug($event->getTitle());
            } else {
                if ($event->getSlug() === '/') {
                    $slug = 'index';
                } else {
                    $slug = $event->getSlug();
                }
            }

            $urls[] = $slug;
            $filenames[] = $slug . '.html';

            // if slug contains slashes, create necessary directories in /tmp/{websiteId}/
            $slugParts = explode('/', $slug);
            if (count($slugParts) > 1) {
                $path = '/tmp/' . $website->getId() . '/';
                for ($j = 0; $j < count($slugParts) - 1; $j++) {
                    $path .= $slugParts[$j] . '/';
                    if (!is_dir($path)) {
                        mkdir($path);
                    }
                }
            }

            // Save the generated HTML content to a temporary file
            $tempFilePath = '/tmp/' . $website->getId() . '/' . $slug . '.html';
            file_put_contents($tempFilePath, $eventContent);

            $this->pushToFTP(
                $website->getFTPHost(),
                $website->getFTPUser(),
                $website->getFTPPassword(),
                $website->getFTPPath(),
                $tempFilePath,
                $slug . '.html'
            );

            $flashText .= mb_strtoupper($this->translator->trans('web.event')) .': '. $event->getTitle() . " FTP OK <br>";
        }
    }

    private function deployStylesheet(Website $website)
    {
        $headerCSS = $website->getHeaderCSS();
        if ($headerCSS) {
            $tempFilePath = '/tmp/' . $website->getId() . '/style.css';
            file_put_contents($tempFilePath, $headerCSS);

            self::pushToFTP(
                $website->getFTPHost(),
                $website->getFTPUser(),
                $website->getFTPPassword(),
                $website->getFTPPath(),
                $tempFilePath,
                'style.css',
            );

            //$this->addFlash('success', 'style.css FTP OK.');
        } else {
            //$this->addFlash('warning', 'No header CSS found.');
        }
    }

    private function deployCategories($website, $slugger, &$urls, &$filenames, &$flashText, $categories, $pages, $menus)
    {
        // get website categories
        foreach ($categories as $category) {
            $htmlContent = $this->renderView('themes/' . $website->getTheme() . '/category.html.twig', [
                'website' => $website,
                'charset' => $website->getCharset(),
                'language' => $website->getLanguage(),
                'title' => $category->getTitle(),
                'keywords' => $website->getMetaKeywords(),
                'description' => $website->getMetaDescription(),
                'content' => $category,
                'categories' => $categories,
                'pages' => $pages,
                'menus' => $menus,
            ]);

            if ($category->getSlug() === '') {
                $slug = $slugger->slug($category->getName());
            } else {
                if ($category->getSlug() === '/') {
                    $slug = 'index';
                } else {
                    $slug = $category->getSlug();
                }
            }

            // Save the generated HTML content to a temporary file
            $tempFilePath = '/tmp/' . $website->getId() . '/' . $slug . '.html';
            file_put_contents($tempFilePath, $htmlContent);

            // Add to URLs and filenames for .htaccess
            $urls[] = $slug;
            $filenames[] = $slug . '.html';

            // Push to FTP
            self::pushToFTP(
                $website->getFTPHost(),
                $website->getFTPUser(),
                $website->getFTPPassword(),
                $website->getFTPPath(),
                $tempFilePath,
                $slug . '.html'
            );

            // Flash message
            $flashText .= mb_strtoupper($this->translator->trans('web.category')) . ': ' . htmlspecialchars($category->getTitle()) . " FTP OK <br>";

        }

    }

    private function deployPages($website, $slugger, &$urls, &$filenames, &$flashText, $categories, $pages, $menus, $events)
    {
        $i = 0;
        foreach ($pages as $page) {
            //dump($pages);

            //dump($page);

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

            //dump($twig->loader->exists('themes/'. $website->getTheme() .'/homepage.html.twig'));
            // if page is homepage, use index.html.twig template
            $templateFile = 'index.html.twig';

            /*
            // if it is homepage and homepage.html.twig exists, use it
            if ($page->isHomepage() && file_exists('themes/'. $website->getTheme() .'/homepage.html.twig')) {
                $templateFile = 'homepage.html.twig';
            }
            if($page->isHomepage())
            {
                //dump(__FILE__);
                //dump('themes/'. $website->getTheme() .'/homepage.html.twig');
                //dump($website->getTheme());
                //dump($page);
                //dump($website);
                //dump(file_exists('themes/'. $website->getTheme() .'/homepage.html.twig'));
                //dd($templateFile);
            }
            */
            $projectDir = $this->getParameter('kernel.project_dir');

            if (!$page->isHomepage() && file_exists($projectDir.'/templates/themes/'. $website->getTheme() .'/page.html.twig')) {
                $templateFile = 'page.html.twig';
            }

            /*
            $templateFile = 'index.html.twig';
            if (file_exists('themes/'. $website->getTheme() .'/page.html.twig')) {
                $templateFile = 'page.html.twig';
            }
            */



            $htmlContent = $this->renderView('themes/'. $website->getTheme() .'/'.$templateFile, [
                'website' => $website,
                'charset' => $website->getCharset(),
                'language' => $website->getLanguage(),
                'title' => $page->getTitle(),
                'keywords' => $website->getMetaKeywords(),
                'description' => $website->getMetaDescription(),
                'content' => $pageContent,
                'categories' => $categories,
                'pages' => $pages,
                'menus' => $menus,
                'events' => $events,
            ]);

            if ($page->getSlug() === '') {
                $slug = $slugger->slug($page->getTitle());
            } else {
                if ($page->getSlug() === '/' || $page->isHomepage()) {
                    $slug = 'index';
                } else {
                    $slug = $page->getSlug();
                }
            }

            $urls[] = $slug;
            $filenames[] = $slug.'.html';

            // if slug contains slashes, create necessary directories in /tmp/{websiteId}/
            $slugParts = explode('/', $slug);
            if (count($slugParts) > 1) {
                $path = '/tmp/' . $website->getId() . '/';
                for ($j = 0; $j < count($slugParts) - 1; $j++) {
                    $path .= $slugParts[$j] . '/';
                    if (!is_dir($path)) {
                        mkdir($path);
                    }
                }
            }

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

            $flashText .= mb_strtoupper($this->translator->trans('web.page')) .': '. $page->getTitle() . " FTP OK <br>";
            $i++;
        }
    }

    private function deployPosts($website, $slugger, &$urls, &$filenames, &$flashText, $categories, $pages, $menus)
    {
        $posts = $this->doctrine->getRepository(WebsitePost::class)->findBy(['website' => $website, 'status' => true]);

        foreach ($posts as $post) {
            $postContent = $post->getContent();
            preg_match_all('/\[block id="(\d+)"\]/', $postContent, $matches);
            foreach ($matches[1] as $blockId) {
                $blockRepository = $this->doctrine->getRepository(Block::class);
                $block = $blockRepository->findOneBy([
                    'id' => $blockId,
                    'instance' => $this->currentInstance,
                    'status' => true
                ]);
                if ($block) {
                    $postContent = str_replace('[block id="'.$blockId.'"]', $block->getContent(), $postContent);
                }
            }

            $templateFile = 'index.html.twig';
            if (file_exists('themes/'. $website->getTheme() .'/post.html.twig')) {
                $templateFile = 'post.html.twig';
            }

            $htmlContent = $this->renderView('themes/'. $website->getTheme() .'/post.html.twig', [
                'website' => $website,
                'charset' => $website->getCharset(),
                'language' => $website->getLanguage(),
                'title' => $post->getTitle(),
                'keywords' => $website->getMetaKeywords(),
                'description' => $website->getMetaDescription(),
                'content' => $postContent,
                'categories' => $categories,
                'pages' => $pages,
                'menus' => $menus,
            ]);

            if ($post->getSlug() === '') {
                $slug = $slugger->slug($post->getTitle());
            } else {
                if ($post->getSlug() === '/') {
                    $slug = 'index';
                } else {
                    $slug = $post->getSlug();
                }
            }

            $firstCategory = $post->getCategories()->first();
            $fileName = $slug;
            if ($firstCategory) {
                $slug = $firstCategory->getSlug() . '/' . $slug;
                $fileName = $firstCategory->getSlug() . '___' . $fileName;
            }

            // Save the generated HTML content to a temporary file
            $tempFilePath = '/tmp/' . $website->getId() .'/'. $fileName . '.html';
            file_put_contents($tempFilePath, $htmlContent);

            // Add to URLs and filenames for .htaccess
            $urls[] = $slug;
            $filenames[] = $fileName . '.html';

            // Push to FTP
            $this->pushToFTP(
                $website->getFTPHost(),
                $website->getFTPUser(),
                $website->getFTPPassword(),
                $website->getFTPPath(),
                $tempFilePath,
                $fileName . '.html'
            );

            $flashText .= mb_strtoupper($this->translator->trans('web.post')) . ': ' . htmlspecialchars($post->getTitle()) . " FTP OK <br>";
        }

    }

    private function createHtaccessFile(Website $website, array $urls=[], array $filenames=[])
    {
        $content = 'FallbackResource /index.html
RewriteEngine On
#RewriteBase /
#RewriteCond %{REQUEST_URI} !\.[a-zA-Z0-9]{1,5}$ [NC]
#RewriteCond %{REQUEST_URI} !/$
#RewriteRule ^(.*)$ /$1/ [L,R=301]
';

        $i = 0;
        foreach ($urls as $url) {
            $content .= 'RewriteRule ^' . $url . '/?$ ' . $filenames[$i] . ' [L,NC]'."\n";
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

        //$this->addFlash('success', '.htaccess FTP OK.');
    }

    public function createRobotsTxtFile(Website $website)
    {
        $fileName = 'robots.txt';

        $content = 'User-agent: *
Crawl-delay: 10
';

        $tempFilePath = '/tmp/' . $website->getId() . '/'. $fileName;
        file_put_contents($tempFilePath, $content);

        $this->pushToFTP(
            $website->getFTPHost(),
            $website->getFTPUser(),
            $website->getFTPPassword(),
            $website->getFTPPath(),
            $tempFilePath,
            $fileName
        );

        //$this->addFlash('success', $fileName.' FTP OK.');
    }

    public static function pushToFTP($FTPhost, $FTPuser, $FTPpassword, $FTPpath, $content, $filename, $website = null)
    {
        // if FTP host is localhost, move files to FTP path directly
        if ($FTPhost === 'localhost') {
            $filePath = $FTPpath . $filename;
            try {
                // if filename contains directories, create them
                $pathParts = explode('/', $filename);
                if (count($pathParts) > 1) {
                    $path = $FTPpath;
                    for ($j = 0; $j < count($pathParts) - 1; $j++) {
                        $path .= $pathParts[$j] . '/';
                        if (!is_dir($path)) {
                            mkdir($path);
                        }
                    }
                }


                file_put_contents($filePath, file_get_contents($content));
            }
            catch (\Exception $e) {
                dump($filename);

                dd('Hiba a fájl írása során: ' . $e->getMessage());
            }


            return;
        } else {
            $ftp = ftp_connect($FTPhost);
            ftp_login($ftp, $FTPuser, $FTPpassword);
            ftp_pasv($ftp, true);

            // check if the FTP path exists, if not create it
            if (!@ftp_chdir($ftp, $FTPpath)) {
                ftp_mkdir($ftp, $FTPpath);
                ftp_chdir($ftp, $FTPpath);
            }

            // check if ftp connection is successful
            if (!$ftp) {
                //$this->addFlash('danger', 'FTP kapcsolat sikertelen.');
                return;
            }

            ftp_put($ftp, $FTPpath.$filename, $content, FTP_ASCII);
            ftp_close($ftp);
        }
    }

    public static function removeFromFTP($FTPhost, $FTPuser, $FTPpassword, $FTPpath, $filename)
    {
        if ($FTPhost !== 'localhost') {
            $ftp = ftp_connect($FTPhost);
            ftp_login($ftp, $FTPuser, $FTPpassword);
            ftp_pasv($ftp, true);

            // check if the FTP path exists
            if (@ftp_chdir($ftp, $FTPpath)) {
                // check if file exists
                if (ftp_nlist($ftp, $FTPpath . $filename)) {
                    // delete the file
                    ftp_delete($ftp, $FTPpath . $filename);
                }
            }

            ftp_close($ftp);
        }
    }
}
