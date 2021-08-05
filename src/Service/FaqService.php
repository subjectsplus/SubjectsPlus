<?php

namespace App\Service;

use App\Entity\Faq;
use App\Entity\Subject;
use App\Entity\FaqSubject;
use App\Entity\Faqpage;
use App\Entity\FaqFaqpage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class FaqService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Faq $faq
     * @param ArrayCollection|array $subjects
     */
    public function addSubjectsToFaq(Faq $faq, $subjects) {
        $this->entityManager->transactional(function() use ($faq, $subjects) {
            foreach ($subjects as $subject) {
                $this->addSubjectToFaq($faq, $subject);
            }
        });
    }

    /**
     * @param Faq $faq
     * @param Subject $subject
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
     * @param Faq $faq
     * @param ArrayCollection|array $subjects
     */
    public function removeSubjectsFromFaq(Faq $faq, $subjects) {
        $this->entityManager->transactional(function() use($faq, $subjects) {
            foreach ($subjects as $subject) {
                $this->removeSubjectFromFaq($faq, $subject);
            }
        });
    }

    /**
     * @param Faq $faq
     * @param Subject $subject
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
     * @param Faq $faq
     * @param ArrayCollection|array $faqpages
     */
    public function addFaqpagesToFaq(Faq $faq, $faqpages) {
        $this->entityManager->transactional(function() use($faq, $faqpages) {
            foreach ($faqpages as $faqpage) {
                $this->addFaqpageToFaq($faq, $faqpage);
            }
        });
    }

    /**
     * @param Faq $faq
     * @param Faqpage $faqpage
     */
    public function addFaqpageToFaq(Faq $faq, Faqpage $faqpage) {
        // Check if FaqFaqpage row already exists for new subject
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
     * @param Faq $faq
     * @param ArrayCollection|array $faqpages
     */
    public function removeFaqpagesFromFaq(Faq $faq, $faqpages) {
        $this->entityManager->transactional(function() use($faq, $faqpages) {
            foreach ($faqpages as $faqpage) {
                $this->removeFaqpageFromFaq($faq, $faqpage);
            }
        });
    }

    /**
     * @param Faq $faq
     * @param Faqpage $faqpage
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
     * @param Faq $faq
     * @param boolean $flush
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

            $this->entityManager->remove($faq);
        });
    }
}