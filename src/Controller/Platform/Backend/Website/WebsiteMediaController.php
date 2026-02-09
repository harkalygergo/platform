<?php

namespace App\Controller\Platform\Backend\Website;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Media\Media;
use App\Entity\Platform\User;
use App\Entity\Platform\Website\Website;
use App\Entity\Platform\Website\WebsiteMedia;
use App\Form\Platform\Media\MediaType;
use App\Repository\Platform\Website\WebsiteMediaRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted(User::ROLE_USER)]
#[\Symfony\Component\Routing\Attribute\Route('/{_locale}/admin/v1/website/media')]
class WebsiteMediaController extends PlatformController
{
    #[\Symfony\Component\Routing\Attribute\Route('/', name: 'admin_v1_website_media')]
    public function index(Request $request, WebsiteMediaRepository $websiteMediaRepository): Response
    {
        //$website = $id;

        $media = $websiteMediaRepository->findAll();

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => ' media',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'originalName' => 'Eredeti név',
                'type' => 'Típus',
                'size' => 'Méret (bytes)',
                'description' => 'Leírás',
            ],
            'tableBody' => $media,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/new', name: 'admin_v1_website_media_new')]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        // get instance first website
        $id = $this->currentInstance->getWebsites()->first();

        $website = $id;

        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createMediaDirectory($website);

            $uploadedFiles = $form->get('file')->getData();

            if ($uploadedFiles) {

                foreach ($uploadedFiles as $uploadedFile) {

                    $mimeType = $uploadedFile->getMimeType();
                    $size = $uploadedFile->getSize();

                    $websiteMedia = new WebsiteMedia();
                    $websiteMedia->setWebsite($website);
                    $websiteMedia->setPath($uploadedFile->getFilename());
                    $websiteMedia->setOriginalName($uploadedFile->getClientOriginalName());
                    $websiteMedia->setType($mimeType);
                    $websiteMedia->setSize($size);
                    $websiteMedia->setCreatedAt(new \DateTime());
                    $websiteMedia->setPublic(true);
                    $websiteMedia->setCreatedBy($this->getUser());
                    $websiteMedia->setInstance($this->currentInstance);
                    $websiteMedia->setDescription($form->get('description')->getData());
                    // save instanceStorage
                    $entityManager = $this->doctrine->getManager();
                    $entityManager->persist($websiteMedia);
                    $entityManager->flush();

                    $tempFilePath = '/tmp/' . $uploadedFile->getFilename();
                    file_put_contents($tempFilePath, $uploadedFile->getContent());

                    $tempFilePath = '/tmp/' . $website->getId() .'/'. $uploadedFile->getClientOriginalName();
                    if (!is_dir('/tmp/' . $website->getId())) {
                        mkdir('/tmp/' . $website->getId(), 0777, true);
                    }
                    file_put_contents($tempFilePath, $uploadedFile->getContent());

                    WebsiteController::pushToFTP(
                        $website->getFTPHost(),
                        $website->getFTPUser(),
                        $website->getFTPPassword(),
                        $website->getFTPPath(),
                        $tempFilePath,
                        'media/'.$uploadedFile->getClientOriginalName()
                    );

                    /*
                    // get file name with original extension
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                    try {
                        // get uploaded file mime type
                        $mimeType = $uploadedFile->getMimeType();
                        $size = $uploadedFile->getSize();

                        $uploadedFile->move(__DIR__ . '/../../../../public/media/i' . $this->currentInstance->getId(), $newFilename);

                        // save the file to the database as InstanceStorage
                        $instanceStorage = new Media();
                        $instanceStorage->setPath($newFilename);
                        $instanceStorage->setOriginalName($uploadedFile->getClientOriginalName());
                        $instanceStorage->setType($mimeType);
                        $instanceStorage->setSize($size);
                        $instanceStorage->setCreatedAt(new \DateTime());
                        $instanceStorage->setPublic(true);
                        $instanceStorage->setCreatedBy($this->getUser());
                        $instanceStorage->setInstance($this->currentInstance);
                        $instanceStorage->setDescription($form->get('description')->getData());
                        // save instanceStorage
                        $entityManager = $this->doctrine->getManager();
                        $entityManager->persist($instanceStorage);
                        $entityManager->flush();
                    } catch (FileException $e) {
                        // Handle exception if something happens during file upload
                        $this->addFlash('danger', 'Failed to upload file.');
                        return $this->redirectToRoute('admin_v1_media_instance_index');
                    }
                    */
                }

                $this->addFlash('success', 'File(s) uploaded successfully.');

                return $this->redirectToRoute('admin_v1_website_media', ['id' => $website->getId()]);
            }
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új média feltöltése',
            'form' => $form->createView(),
        ]);
    }


    public function createMediaDirectory(Website $website): void
    {
        if ($website->getFTPHost() === 'localhost') {
            $mediaDirectory = $website->getFTPPath() . 'media';
            if (!is_dir($mediaDirectory)) {
                mkdir($mediaDirectory, 0777, true);
            }
            return;
        }

        $connection = ftp_connect($website->getFTPHost());
        if (!$connection) {
            throw new \RuntimeException('Failed to connect to FTP server.');
        }

        $loginResult = ftp_login($connection, $website->getFTPUser(), $website->getFTPPassword());
        if (!$loginResult) {
            ftp_close($connection);
            throw new \RuntimeException('Failed to log in to FTP server.');
        }

        // Ensure the "media" directory exists
        $mediaDirectory = $website->getFTPPath() . 'media';

        if (!@ftp_chdir($connection, $mediaDirectory)) {
            ftp_mkdir($connection, $mediaDirectory);
            ftp_chdir($connection, $mediaDirectory);
        }

        ftp_close($connection);
    }

    // create delete function, not just delete record, but remove file from FTP server as well
    #[Route('/{id}/delete/{websiteMedia}', name: 'admin_v1_website_media_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Website $id, WebsiteMedia $websiteMedia): Response
    {
        //if ($this->isCsrfTokenValid('delete' . $id->getId(), $request->request->get('_token'))) {
            $entityManager = $this->doctrine->getManager();
            //$websiteMedia = $entityManager->getRepository(WebsiteMedia::class)->find($id);

            if ($websiteMedia) {
                // Remove file from FTP server
                WebsiteController::removeFromFTP(
                    $websiteMedia->getWebsite()->getFTPHost(),
                    $websiteMedia->getWebsite()->getFTPUser(),
                    $websiteMedia->getWebsite()->getFTPPassword(),
                    $websiteMedia->getWebsite()->getFTPPath(),
                    'media/' . $websiteMedia->getPath()
                );

                // Remove record from database
                $entityManager->remove($websiteMedia);
                $entityManager->flush();

                $this->addFlash('success', 'Média sikeresen törölve.');
            } else {
                $this->addFlash('danger', 'A média nem található.');
            }
        //}

        return $this->redirectToRoute('admin_v1_website_media', ['id' => $id->getId()]);
    }

    // multiple delete
    // http://platform.local/hu/admin/v1/website/media/9/multiple/delete/on,12,13,14
    //     #[Route('/{id}/delete/{websiteMedia}', name: 'admin_v1_website_media_delete', methods: ['GET', 'POST'])]
    #[Route('/{website}/multiple/{action}/{ids}', name: 'admin_v1_website_media_multiple')]
    public function multiple(Request $request, WebsiteMediaRepository $websiteMediaRepository, Website $website, string $action, string $ids): Response
    {
        $idsArray = explode(',', $ids);

        if ($action === 'delete') {
            foreach ($idsArray as $websiteMediaId) {
                $websiteMedia = $this->doctrine->getRepository(WebsiteMedia::class)->find($websiteMediaId);
                if (!$websiteMedia) {
                    continue; // Skip if media not found
                }
                // delete media record from database
                $this->delete($request, $website, $websiteMedia);

                // delete media from database and FTP server
                //$websiteMedia = $this->doctrine->getRepository(WebsiteMedia::class)->find($websiteMedia);
                // delete $websiteMedia if it exists
                if ($websiteMedia) {
                    /*
                    // Remove file from FTP server
                    WebsiteController::removeFromFTP(
                        $websiteMedia->getWebsite()->getFTPHost(),
                        $websiteMedia->getWebsite()->getFTPUser(),
                        $websiteMedia->getWebsite()->getFTPPassword(),
                        $websiteMedia->getWebsite()->getFTPPath(),
                        'media/' . $websiteMedia->getPath()
                    );
                    */

                    // Remove record from database
                    //$this->doctrine->getManager()->remove($websiteMedia);
                }
            }
            //$this->doctrine->getManager()->flush();

            $this->addFlash('success', 'A kiválasztott média sikeresen törölve.');
        }

        return $this->redirectToRoute('admin_v1_website_media', ['id' => $website->getId()]);
    }

}
