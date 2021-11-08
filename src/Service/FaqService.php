<?php

namespace App\Service;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\FaqSubject;
use App\Entity\Faqpage;
use App\Entity\FaqFaqpage;
use App\Entity\MediaAttachment;
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
        $this->entityManager->transactional(function() use ($faq, $subjects) {
            foreach ($subjects as $subject) {
                $this->addSubjectToFaq($faq, $subject);
            }
        });
    }

    /**
     * Add a subject association to an Faq entity.
     * 
     * Checks if a duplicate FaqSubject exists consisting of the provided $faq
     * and $subject. If not, creates a new FaqSubject with the provided $faq and $subject,
     * and adds the new FaqSubject to the $faq. The new FaqSubject is then added to the
     * database.
     * 
     * @param Faq $faq Faq entity.
     * @param Subject $subject Subject entity.
     */
    public function addSubjectToFaq(Faq $faq, Subject $subject) {
        // Check if FaqSubject row already exists for new subject
        $duplicate = $this->entityManager
        ->getRepository(FaqSubject::class)
        ->findBy(['faq' => $faq, 'subject' => $subject]);

        if (!$duplicate) {
            $this->entityManager->transactional(function() use($faq, $subject) {
                // Create new FaqSubject row
                $faqSubject = new FaqSubject();
                $faqSubject->setFaq($faq);
                $faqSubject->setSubject($subject);

                $faq->addFaqSubject($faqSubject);
                $this->entityManager->persist($faqSubject);
            });
        }
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
        $this->entityManager->transactional(function() use($faq, $subjects) {
            foreach ($subjects as $subject) {
                $this->removeSubjectFromFaq($faq, $subject);
            }
        });
    }

    /**
     * Remove a subject association from an Faq entity.
     * 
     * Checks if an FaqSubject exists consisting of the provided $faq
     * and $subject. If it does, the FaqSubject is removed from the $faq
     * and deleted from the database.
     * 
     * @param Faq $faq Faq entity.
     * @param Subject $subject Subject entity.
     */
    public function removeSubjectFromFaq(Faq $faq, Subject $subject) {
        // Check if FaqSubject row to be removed exists
        $exists = $this->entityManager
        ->getRepository(FaqSubject::class)
        ->findOneBy(['faq' => $faq, 'subject' => $subject]);

        if ($exists) {
            $this->entityManager->transactional(function() use($faq, $exists) {
                // Delete the associated FaqSubject
                $faq->removeFaqSubject($exists);
                $this->entityManager->remove($exists);
            });
        }
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
        $this->entityManager->transactional(function() use($faq, $faqpages) {
            foreach ($faqpages as $faqpage) {
                $this->addFaqpageToFaq($faq, $faqpage);
            }
        });
    }

    /**
     * Add an faqpage association to an Faq entity.
     * 
     * Checks if a duplicate FaqFaqpage exists consisting of the provided $faq
     * and $faqpage. If not, creates a new FaqFaqpage with the provided $faq and $faqpage,
     * and adds the new FaqFaqpage to the $faq. The new FaqFaqpage is then added to the
     * database.
     * 
     * @param Faq $faq Faq entity.
     * @param Faqpage $faqpage Faqpage entity.
     */
    public function addFaqpageToFaq(Faq $faq, Faqpage $faqpage) {
        // Check if FaqFaqpage row already exists for new faqpage
        $duplicate = $this->entityManager
        ->getRepository(FaqFaqpage::class)
        ->findBy(['faq' => $faq, 'faqpage' => $faqpage]);

        if (!$duplicate) {
            $this->entityManager->transactional(function() use($faq, $faqpage) {
                // Create new FaqFaqpage row
                $faqFaqPage = new FaqFaqpage();
                $faqFaqPage->setFaq($faq);
                $faqFaqPage->setFaqpage($faqpage);

                $faq->addFaqFaqpage($faqFaqPage);
                $this->entityManager->persist($faqFaqPage);
            });
        }
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
        $this->entityManager->transactional(function() use($faq, $faqpages) {
            foreach ($faqpages as $faqpage) {
                $this->removeFaqpageFromFaq($faq, $faqpage);
            }
        });
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
        // Check if FaqFaqpage row to be removed exists
        $exists = $this->entityManager
        ->getRepository(FaqFaqpage::class)
        ->findOneBy(['faq' => $faq, 'faqpage' => $faqpage]);
        
        if ($exists) {
            $this->entityManager->transactional(function() use($faq, $exists) {
                // Delete the associated FaqFaqpage
                $faq->removeFaqFaqpage($exists);
                $this->entityManager->remove($exists);
            });
        }
    }

    /**
     * Deletes an Faq entity and removes any subject or faqpage associations to the Faq.
     * 
     * Finds any FaqSubject or FaqFaqpage owned by the $faq and removes them from the 
     * database before subsequently removing the $faq itself from the database.
     * 
     * @param Faq $faq Faq entity.
     */
    public function deleteFaq(Faq $faq) {
        $this->entityManager->transactional(function() use($faq) {
            // Delete FaqSubject's associated
            $faqSubjects = $this->entityManager
            ->getRepository(FaqSubject::class)
            ->findBy(['faq' => $faq]);

            foreach($faqSubjects as $faqSubject) {
                $this->entityManager->remove($faqSubject);
            }

            // Delete FaqFaqpage's associated
            $faqFaqpages = $this->entityManager
            ->getRepository(FaqFaqpage::class)
            ->findBy(['faq' => $faq]);

            foreach($faqFaqpages as $faqFaqpage) {
                $this->entityManager->remove($faqFaqpage);
            }

            // Delete MediaAttachment's associated
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