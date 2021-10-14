<?php

namespace App\Controller\Staff;

use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;
use App\Form\ImageType;
use App\Form\ImageAttachmentType;
use App\Form\MediaType;
use App\Form\CKEditorImageUploadType;
use App\Service\UploadService;
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
    public function upload(Request $request, UploadService $uploader, ValidationService $validation, LoggerInterface $logger): Response
    {
        
        $logger->info("Connected to media_upload route.");

        $type = $request->query->get('type');
        $validation_groups = [];

        if ($type === 'image') {
            $validation_groups[] = 'image';
        } else {
            // generic file
            $validation_groups[] = 'generic';
        }

        $logger->info(implode(',', $validation_groups));

        $media = new Media();

        $form = $this->createForm(MediaType::class, $media, [
            'validation_groups' => $validation_groups,
        ]);
        $form->handleRequest($request);

        $logger->info("Created form.");

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info("Form submitted!");
            $logger->info("Validation succeeded!");

            $entityManager = $this->getDoctrine()->getManager();

            /** @var UploadedFile $upload */
            $upload = $form->get('file')->getData();
            /** @var Staff $staff */
            $staff = $this->getUser();

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
        }
        
        return $this->render('media/upload.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Upload File',
        ]);
    }

    // /**
    //  * @Route("/upload/add_details", name="media_upload_add_details")
    //  */
    // public function addDetails(Request $request)
    // {

    // } 
}
