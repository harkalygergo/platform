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
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/', name: 'admin_v1_website_media')]
    public function index(Request $request, WebsiteMediaRepository $websiteMediaRepository, Website $id): Response
    {
        $website = $id;

        $media = $websiteMediaRepository->findBy(['website' => $website]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'title' => $id->getDomain() . ' media',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'tableHead' => [
                'path' => 'Útvonal',
                'originalName' => 'Eredeti név',
            ],
            'tableBody' => $media,
            'actions' => [
                'new',
                'edit',
                'delete',
            ],
        ]);
    }

    #[Route('/{id}/new', name: 'admin_v1_website_media_new')]
    public function new(Request $request, SluggerInterface $slugger, Website $id): Response
    {
        $website = $id;

        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createMediaDirectory(
                $website->getFTPHost(),
                $website->getFTPUser(),
                $website->getFTPPassword(),
                $website->getFTPPath(),
            );


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


    public function createMediaDirectory(string $ftpHost, string $ftpUser, string $ftpPassword, string $ftpPath): void
    {
        $connection = ftp_connect($ftpHost);
        if (!$connection) {
            throw new \RuntimeException('Failed to connect to FTP server.');
        }

        $loginResult = ftp_login($connection, $ftpUser, $ftpPassword);
        if (!$loginResult) {
            ftp_close($connection);
            throw new \RuntimeException('Failed to log in to FTP server.');
        }

        // Ensure the "media" directory exists
        $mediaDirectory = $ftpPath . 'media';

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

}
