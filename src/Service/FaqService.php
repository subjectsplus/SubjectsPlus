<?php

namespace App\Service;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\FaqSubject;
use App\Entity\Faqpage;
use App\Entity\FaqFaqpage;
use App\Entity\MediaAttachment;
use App\Repository\MediaAttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FaqService allows for interactions between the Faq, Subject, and Faqpage entities.
 * 
 * Functionalities of FaqService include adding and removing subjects to the Faq, 
 * adding and removing faqpages to the Faq, and deleting the Faq.
 */
class FaqService {

    /**
     * Entity Manager.
     *
     * @var EntityManagerInterface $entityManager
     */

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Add an array of subject associations to an Faq entity.
     * 
     * Loops through the $subjects array and calls FaqService::addSubjectToFaq on
     * each subject.
     * 
     * @param Faq $faq Faq entity.
     * @param ArrayCollection|array $subjects Array consisting of Subject entity.
     */
    public function addSubjectsToFaq(Faq $faq, $subjects) {
        foreach ($subjects as $subject) {
            $this->addSubjectToFaq($faq, $subject);
        }
    }

    /**
     * Add a subject association to an Faq entity.
     * 
     * @param Faq $faq Faq entity.
     * @param Subject $subject Subject entity.
     */
    public function addSubjectToFaq(Faq $faq, Subject $subject) {
        $faq->addSubject($subject);
    }

    /**
     * Remove an array of subject associations from an Faq entity.
     * 
     * Loops through the $subjects array and calls FaqService::removeSubjectFromFaq on
     * each subject.
     * 
     * @param Faq $faq Faq entity.
     * @param ArrayCollection|array $subjects Array consisting of Subject entity.
     */
    public function removeSubjectsFromFaq(Faq $faq, $subjects) {
        foreach ($subjects as $subject) {
            $this->removeSubjectFromFaq($faq, $subject);
        }
    }

    /**
     * Remove a subject association from an Faq entity.
     * 
     * @param Faq $faq Faq entity.
     * @param Subject $subject Subject entity.
     */
    public function removeSubjectFromFaq(Faq $faq, Subject $subject) {
        $faq->removeSubject($subject);
    }

    /**
     * Add an array of faqpage associations to an Faq entity.
     * 
     * Loops through the $faqpages array and calls FaqService::addFaqpageToFaq on
     * each faqpage.
     * 
     * @param Faq $faq Faq entity.
     * @param ArrayCollection|array $faqpages Array consisting of Faqpage entity.
     */
    public function addFaqpagesToFaq(Faq $faq, $faqpages) {
        foreach ($faqpages as $faqpage) {
            $this->addFaqpageToFaq($faq, $faqpage);
        }
    }

    /**
     * Add an faqpage association to an Faq entity.
     * 
     * @param Faq $faq Faq entity.
     * @param Faqpage $faqpage Faqpage entity.
     */
    public function addFaqpageToFaq(Faq $faq, Faqpage $faqpage) {
        $faq->addFaqpage($faqpage);
    }

    /**
     * Remove an array of faqpage associations from an Faq entity.
     * 
     * Loops through the $faqpages array and calls FaqService::removeFaqpageFromFaq on
     * each faqpage.
     * 
     * @param Faq $faq Faq entity.
     * @param ArrayCollection|array $faqpages Array consisting of Faqpage entity.
     */
    public function removeFaqpagesFromFaq(Faq $faq, $faqpages) {
        foreach ($faqpages as $faqpage) {
            $this->removeFaqpageFromFaq($faq, $faqpage);
        }
    }

    /**
     * Remove an faqpage association from an Faq entity.
     * 
     * Checks if an FaqFaqpage exists consisting of the provided $faq
     * and $faqpage. If it does, the FaqFaqpage is removed from the $faq
     * and deleted from the database.
     * 
     * @param Faq $faq Faq entity.
     * @param Faqpage $faqpage Faqpage entity.
     */
    public function removeFaqpageFromFaq(Faq $faq, Faqpage $faqpage) {
        $faq->removeFaqpage($faqpage);
    }

    /**
     * Deletes an Faq entity and removes any subject, faqpage, or media_attachment associations to the Faq.
     * 
     * Finds any FaqSubject or FaqFaqpage owned by the $faq and removes them from the 
     * database before subsequently removing the $faq itself from the database.
     * 
     * @param Faq $faq Faq entity.
     */
    public function deleteFaq(Faq $faq) {
        $this->entityManager->transactional(function() use($faq) {
            // Delete MediaAttachment's associated
            /** @var MediaAttachmentRepository $mediaAttachment */
            $mediaAttachments = $this->entityManager
            ->getRepository(MediaAttachment::class)
            ->findBy(['attachmentType' => 'faq', 'attachmentId' => $faq->getFaqId()]);

            foreach($mediaAttachments as $attachment) {
                $this->entityManager->remove($attachment);
            }
            
            $this->entityManager->remove($faq);
        });
    }
}