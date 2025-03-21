<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\Website;
use App\Form\Platform\Website\WebsiteType;
use App\Repository\Platform\Website\WebsiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            ],
            'tableBody' => $websiteRepository->findBy(['instance' => $this->currentInstance]),
            'actions' => [
                'edit',
            ],
            'extraActions' => [
                'deploy' => [
                    'route' => 'admin_v1_website_deploy',
                    'label' => 'Deploy',
                ],
                'pages' => [
                    'route' => 'admin_v1_website_pages',
                    'label' => 'Pages',
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
        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('admin_v1_website_index');
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'title' => 'Szerkesztés',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deploy/{id}', name: 'admin_v1_website_deploy')]
    public function deploy(Website $id): Response
    {
        $content = 'banán';

        $htmlContent = $this->renderView('themes/'. $id->getTheme() .'/index.html.twig', [
            'charset' => $id->getCharset(),
            'language' => $id->getLanguage(),
            'title' => $id->getTitle(),
            'keywords' => $id->getMetaKeywords(),
            'description' => $id->getMetaDescription(),
            'content' => $content,
        ]);

        // Save the generated HTML content to a temporary file
        $random = uniqid();
        $tempFilePath = '/tmp/' . $random . '.html';
        file_put_contents($tempFilePath, $htmlContent);
        echo $htmlContent;
        exit;







        $content = "alma";
        // get templates/themes/cv/index.php file and replace CONTENT with $content
        $file = $this->kernel->getProjectDir() . '/templates/themes/cv/index.php';
        $fileContent = file_get_contents($file);
        $fileContent = str_replace('CONTENT', $content, $fileContent);

        $random = uniqid();
        ob_start();
        eval('?>' . $fileContent);
        $fileContent = ob_get_clean();
        ob_flush();
        file_put_contents('/tmp/'.$random.'.html', $fileContent);


        // push HTML result to FTP server
        $ftp = ftp_connect('harkalygergo.hu');
        ftp_login($ftp, 'harkalygergo_ftp', '%gQ_?9%9OHDtqN7^');
        ftp_pasv($ftp, true);
        ftp_put($ftp, 'public_html/index.html', '/tmp/'.$random.'.html', FTP_ASCII);
        ftp_close($ftp);
        print_r(error_get_last());





        return new Response($fileContent);
    }
}
