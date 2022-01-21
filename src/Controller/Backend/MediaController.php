<?php

namespace App\Controller\Backend;

use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;
use App\Repository\MediaRepository;
use App\Form\MediaType;
use App\Form\MediaEditType;
use App\Service\MediaService;
use App\Service\ValidationService;
use App\Service\ChangeLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Constraints\File as FileValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
/**
 * @Route("/control/media")
 */
class MediaController extends AbstractController
{
    /**
     * @Route("/", name="media_index")
     */
    public function index(): Response
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/browse", name="media_browse")
     */
    public function browse(Request $request, LoggerInterface $logger): Response
    {
        $ckeditorFuncNum = $request->query->get('CKEditorFuncNum');
        $ckeditorInstance = $request->query->get('CKEditor');
        $langCode = $request->query->get('langCode');
        $type = $request->query->get('type');
        $target = $request->query->get('target');
        $targetId = $request->query->get('target_id');

        $logger->info("CKEditorFuncNum: " . $ckeditorFuncNum);
        $logger->info("CKEditor: " . $ckeditorInstance);
        $logger->info("LangCode: " . $langCode);
        $logger->info("Type: " . $type);
        $logger->info("Target: " . $target);
        $logger->info("Target Id: " . $targetId);

        return $this->render('backend/media/browse.html.twig', [
            
        ]);
    }

    /**
     * Renders an upload page for Media source.
     * 
     * Renders an upload page for client to upload media sources.
     * Upon form submission, the file is uploaded to the file server and
     * a new Media entity is created for that file in the database.
     * 
     * @Route("/upload", name="media_upload")
     * 
     * @return Response Pre-Submission: Renders 'backend/media/upload.html.twig' template with parameters 'form' signifying the form's view, 
     * 'button_label' signifying the label of the submit button, and 'staff_media' signifying media the logged in staff member has
     * previously uploaded.
     * 
     * Post-Submission: Redirects to the 'media_show' route with parameter 'mediaId'.
     */
    public function upload(Request $request, MediaService $uploader, ValidationService $validation, LoggerInterface $logger): Response
    {
        
        $logger->info("Connected to media_upload route.");
       
        /** @var Staff $staff */
        $staff = $this->getUser();
        /** @var MediaRepository $mediaRepo */
        $mediaRepo = $this->getDoctrine()->getRepository(Media::class);
        $staffMedia = $mediaRepo->findByStaff($staff); // all media owned by staff member

        $media = new Media();

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        $logger->info("Created form.");

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info("Form submitted!");
            $logger->info("Validation succeeded!");

            $entityManager = $this->getDoctrine()->getManager();
            $conn = $this->getDoctrine()->getConnection();

            $conn->beginTransaction();

            try {
                /** @var UploadedFile $upload */
                $upload = $form->get('file')->getData();

                // Upload file to file server
                $uploadResults = $uploader->uploadFile($upload);
                /** @var UploadedFile $upload */
                $upload = $uploadResults['file'];
                $fileName = $upload->getFilename();
                $mimeType = $upload->getMimeType();

                // Variations for image files
                $largeFile = $uploadResults['largeFile'];
                $mediumFile = $uploadResults['mediumFile'];
                $smallFile = $uploadResults['smallFile'];

                if ($largeFile !== null) {
                    $media->setLargeFileName($largeFile->getFilename());
                }

                if ($mediumFile !== null) {
                    $media->setMediumFileName($mediumFile->getFilename());
                }

                if ($smallFile !== null) {
                    $media->setSmallFileName($smallFile->getFilename());
                }
                
                // Fill Media entity values
                $media->setFileName($fileName);
                $media->setMimeType($mimeType);
                $media->setFilesize($upload->getSize());
                $media->setStaff($staff);
                
                $entityManager->persist($media);
                $entityManager->flush();
                $conn->commit();
            } catch (\Exception $e) {
                // delete the file if uploaded already
                if (isset($file) && file_exists($file->getRealPath())) {
                    unlink($file->getRealPath());
                }
                if (isset($largeFile) && file_exists($largeFile->getRealPath())) {
                    unlink($largeFile->getRealPath());
                }
                if (isset($mediumFile) && file_exists($mediumFile->getRealPath())) {
                    unlink($mediumFile->getRealPath());
                }
                if (isset($smallFile) && file_exists($smallFile->getRealPath())) {
                    unlink($smallFile->getRealPath());
                }
                $conn->rollback();
                throw $e;
            }

            return $this->redirectToRoute("media_show", [
                "mediaId" => $media->getMediaId(),
            ]);
        }
        
        return $this->render('backend/media/upload.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Upload File',
            'staff_media' => $staffMedia,
        ]);
    }

    /**
     * Renders a display page for Media source.
     * 
     * @return Response Renders 'backend/media/show.html.twig' template with parameter 'media' signifying
     * the Media entity to display.
     * 
     * @Route("/{mediaId}", name="media_show", methods={"GET"})
     */
    public function show(Request $request, Media $media, MediaService $uploader): Response
    {
        return $this->render('backend/media/show.html.twig', [
            'media' => $media,
        ]);
    }

    /**
     * Renders an edit page for the Media source.
     * 
     * @return Response Pre-Submission: Renders 'backend/media/edit.html.twig' template with parameter 'media' signifying
     * the Media entity to edit and 'form' signifying the form's view.
     * 
     * Post-Submission: Saves the changes to the database and redirects to the 'media_show' route
     * with parameter 'mediaId'.
     * 
     * @Route("/{mediaId}/edit", name="media_edit")
     */
    public function edit(Request $request, Media $media): Response
    {
        $form = $this->createForm(MediaEditType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute("media_show", [
                "mediaId" => $media->getMediaId(),
            ]);
        }

        return $this->render('backend/media/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Performs a delete request for the Media entity.
     * 
     * @return Response Upon successful deletion of Media entity, redirects to the media_upload route.
     * 
     * @Route("/{mediaId}", name="media_delete", methods={"POST"})
     */
    public function delete(Request $request, ChangeLogService $cls, Media $media): Response
    {
        // Check whether user is authenticated
        // TODO: Check if permissions permit user to delete the media
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Delete Media
        if ($this->isCsrfTokenValid('delete'.$media->getMediaId(), $request->request->get('_token'))) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->transactional(function() use($media, $cls, $entityManager) {
                // Preserve before deletion
                $mediaId = $media->getMediaId();
                $title = $media->getTitle();

                // Delete Media (Set delete flag)
                $media->setDeletedAt(new \DateTimeImmutable());
                $entityManager->persist($media);

                // Create new log entry
                /** @var Staff $staff */
                $staff = $this->getUser();
                $cls->addLog($staff, 'media', $mediaId, $title, 'delete');

                // Create flash message
                $this->addFlash('notice', 'Success! Deleted Media!');
            });
        }

        return $this->redirectToRoute('media_upload');
    }
}
