<?php

namespace App\Controller\Platform\Media;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\Media\Media;
use App\Entity\Platform\User;
use App\Form\Platform\Media\MediaType;
use App\Repository\Platform\Media\MediaRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/{_locale}/admin/v1/media/instance')]
#[IsGranted(User::ROLE_USER)]
class InstanceMediaController extends PlatformController
{
    #[Route('/', name: 'admin_v1_media_instance_index')]
    public function index(Request $request, MediaRepository $mediaRepository): Response
    {
        $medias = $mediaRepository->findBy(['instance' => $this->currentInstance]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Fiók médiatárhely',
            'tableBody' => $medias,
            'tableHead' => [
                'originalName' => 'Eredeti név',
                'description' => 'Leírás',
                'type' => 'Type',
                'size' => 'Méret',
                'path' => 'Elérési út',
                'public' => 'Nyilvános',
                'status' => 'Státusz',
                'createdAt' => 'Létrehozva',
                'createdBy' => 'Létrehozta',
            ],
            'actions' => [
                'new',
                'delete',
            ]
        ]);
    }

    #[Route('/new', name: 'admin_v1_media_instance_new')]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFiles = $form->get('file')->getData();

            if ($uploadedFiles) {

                foreach ($uploadedFiles as $uploadedFile) {
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
                }

                $this->addFlash('success', 'File(s) uploaded successfully.');

                return $this->redirectToRoute('admin_v1_media_instance_index');
            }
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új média feltöltése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_v1_media_instance_delete')]
    public function delete(int $id): Response
    {
        $media = $this->doctrine->getRepository(Media::class)->find($id);
        if (!$media) {
            $this->addFlash('danger', 'Media not found.');
            return $this->redirectToRoute('admin_v1_media_instance_index');
        }

        // remove the media file from the filesystem
        $filePath = __DIR__ . '/../../../../public/media/instance/' . $this->currentInstance . '/' . $media->getPath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // remove the media from the database
        $this->doctrine->getManager()->remove($media);
        $this->doctrine->getManager()->flush();

        $this->addFlash('success', 'Media deleted successfully.');
        return $this->redirectToRoute('admin_v1_media_instance_index');
    }
}
