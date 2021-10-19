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
     * @Route("/upload", name="media_upload")
     */
    public function upload(Request $request, MediaService $uploader, ValidationService $validation, LoggerInterface $logger, string $uploadDestination): Response
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

            /** @var UploadedFile $upload */
            $upload = $form->get('file')->getData();

            // Upload file to file server
            $uploadResults = $uploader->uploadFile($upload);
            $upload = $uploadResults['file'];
            $fileName = $uploadResults['fileName'];

            // Fill Media entity values
            $media->setFileName($fileName);
            $media->setMimeType($upload->getMimeType());
            $media->setFilesize($upload->getSize());
            $media->setStaff($staff);
            
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute("media_show", [
                "mediaId" => $media->getMediaId(),
            ]);
        }
        
        return $this->render('media/upload.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Upload File',
            'staff_media' => $staffMedia,
            'relative_url' => $uploadDestination,
        ]);
    }

    /**
     * @Route("/{mediaId}", name="media_show")
     */
    public function show(Request $request, Media $media, MediaService $uploader)
    {
        $url = $uploader->getRelativeUrlFromMedia($media);
        $mimeType = $media->getMimeType();
        $type = 'generic';

        if (strpos($mimeType, "image/") !== false)
            $type = 'image';

        return $this->render('media/show.html.twig', [
            'media' => $media,
            'url' => $url,
            'type' => $type,
        ]);
    }

    /**
     * @Route("/{mediaId}/edit", name="media_edit")
     */
    public function edit(Request $request, Media $media): Response
    {
        $form = $this->createForm(MediaEditType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('media/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    } 
}
