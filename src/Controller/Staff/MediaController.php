<?php

namespace App\Controller\Staff;

use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;
use App\Repository\MediaRepository;
use App\Form\ImageType;
use App\Form\ImageAttachmentType;
use App\Form\MediaType;
use App\Form\MediaEditType;
use App\Form\CKEditorImageUploadType;
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

        return $this->render('media/browse.html.twig', [
            
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
     * @return Response Pre-Submission: Renders 'media/upload.html.twig' template with parameters 'form' signifying the form's view, 
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
                $upload = $uploadResults['file'];
                $fileName = $uploadResults['fileName'];

                $sizedImages = $uploader->generateSizedImages($upload);
                $logger->info("Sized Images: ");
                foreach ($sizedImages as $image) {
                    $logger->info($image);
                }
                
                // Fill Media entity values
                $media->setFileName($fileName);
                $media->setMimeType($upload->getMimeType());
                $media->setFilesize($upload->getSize());
                $media->setStaff($staff);
                
                $entityManager->persist($media);
                $entityManager->flush();
                $conn->commit();
            } catch (\Exception $e) {
                // delete the file if uploaded already
                if (isset($uploadResults['path']) && file_exists($uploadResults['path'])) {
                    unlink($uploadResults['path']);
                }
                $conn->rollback();
                throw $e;
            }

            return $this->redirectToRoute("media_show", [
                "mediaId" => $media->getMediaId(),
            ]);
        }
        
        return $this->render('media/upload.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Upload File',
            'staff_media' => $staffMedia,
        ]);
    }

    /**
     * Renders a display page for Media source.
     * 
     * @return Response Renders 'media/show.html.twig' template with parameter 'media' signifying
     * the Media entity to display.
     * 
     * @Route("/{mediaId}", name="media_show")
     */
    public function show(Request $request, Media $media, MediaService $uploader)
    {
        /** @var MediaAttachmentRepository $mediaAttRepo */
        $mediaAttRepo = $this->getDoctrine()->getRepository(MediaAttachment::class);
        $attachments = $mediaAttRepo->findBy(['media' => $media]);

        return $this->render('media/show.html.twig', [
            'media' => $media,
            'attachments' => $attachments,
        ]);
    }

    /**
     * Renders an edit page for the Media source.
     * 
     * @return Response Pre-Submission: Renders 'media/edit.html.twig' template with parameter 'media' signifying
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

        return $this->render('media/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    }

    // public function delete(Request $request, ChangeLogService $cls, Media $media)
    // {
    //     // Check whether user is authenticated
    //     // TODO: Check if permissions permit user to delete the faq
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     // Delete Faq and associated FaqSubject's and FaqFaqPage's
    //     if ($this->isCsrfTokenValid('delete'.$media->getMediaId(), $request->request->get('_token'))) {
    //         /** @var EntityManagerInterface $entityManager */
    //         $entityManager = $this->getDoctrine()->getManager();

    //         $entityManager->transactional(function() use($media, $cls) {
    //             // Preserve before deletion
    //             $mediaId = $media->getMediaId();
    //             $title = $media->getTitle();

    //             /** @var MediaAttachmentRepository $mediaAttRepo */
    //             $mediaAttRepo = $this->getDoctrine()->getRepository(MediaAttachment::class);
    //             $attachments = $mediaAttRepo->findBy([
    //                 'media' => $media,
    //             ]);

    //             // Delete Media (Set delete flag)
    //             $media->setDeletedAt(new \DateTimeImmutable());

    //             // Create new log entry
    //             /** @var Staff $staff */
    //             $staff = $this->getUser();
    //             $cls->addLog($staff, 'media', $mediaId, $title, 'delete');

    //             // Create flash message
    //             $this->addFlash('notice', 'Success! Deleted Media!');
    //         });
    //     }

    //     return $this->redirectToRoute('faq_index');
    // }
}
