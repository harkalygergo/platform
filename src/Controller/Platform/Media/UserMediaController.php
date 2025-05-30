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

#[Route('/{_locale}/admin/v1/media/user')]
#[IsGranted(User::ROLE_USER)]
class UserMediaController extends PlatformController
{
    #[Route('/', name: 'admin_v1_media_user_index')]
    public function index(Request $request, MediaRepository $mediaRepository): Response
    {
        $medias = $mediaRepository->findBy(['createdBy' => $this->getUser(), 'instance' => null]);

        return $this->render('platform/backend/v1/list.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Személyes tárhely',
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

    #[Route('/new', name: 'admin_v1_media_user_new')]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFiles = $form->get('file')->getData();

            if ($uploadedFiles) {

                foreach ($uploadedFiles as $uploadedFile) {
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                    try {
                        // get uploaded file mime type
                        $mimeType = $uploadedFile->getMimeType();
                        $size = $uploadedFile->getSize();

                        $uploadedFile->move(__DIR__ . '/../../../../public/media/u' . $this->getUser()->getId(), $newFilename);

                        // save the file to the database as InstanceStorage
                        $instanceStorage = new Media();
                        $instanceStorage->setPath($newFilename);
                        $instanceStorage->setOriginalName($uploadedFile->getClientOriginalName());
                        $instanceStorage->setType($mimeType);
                        $instanceStorage->setSize($size);
                        $instanceStorage->setCreatedAt(new \DateTime());
                        $instanceStorage->setPublic(true);
                        $instanceStorage->setCreatedBy($this->getUser());
                        $instanceStorage->setDescription($form->get('description')->getData());
                        // save instanceStorage
                        $entityManager = $this->doctrine->getManager();
                        $entityManager->persist($instanceStorage);
                        $entityManager->flush();
                    } catch (FileException $e) {
                        // Handle exception if something happens during file upload
                        $this->addFlash('danger', 'Failed to upload file.');
                        return $this->redirectToRoute('admin_v1_media_user_index');
                    }
                }

                $this->addFlash('success', 'File(s) uploaded successfully.');

                return $this->redirectToRoute('admin_v1_media_user_index');
            }
        }

        return $this->render('platform/backend/v1/form.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
            'title' => 'Új média feltöltése',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_v1_media_user_delete')]
    public function delete(int $id): Response
    {
        $media = $this->doctrine->getRepository(Media::class)->find($id);
        if ($media && $media->getCreatedBy() === $this->getUser()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($media);
            $entityManager->flush();

            $this->addFlash('success', 'Media file deleted successfully.');
        } else {
            $this->addFlash('danger', 'Media file not found or you do not have permission to delete it.');
        }

        return $this->redirectToRoute('admin_v1_media_user_index');
    }
}
