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
        foreach ($subjects as $subject) {
            $this->addSubjectToFaq($faq, $subject, false);
        }
        $this->entityManager->flush();
    }

    /**
     * @param Faq $faq
     * @param Subject $subject
     * @param boolean $flush
     */
    public function addSubjectToFaq(Faq $faq, Subject $subject, $flush=true) {
        // Check if FaqSubject row already exists for new subject
        $duplicate = $this->entityManager
        ->getRepository(FaqSubject::class)
        ->findBy(['faq' => $faq, 'subject' => $subject]);

        if (!$duplicate) {
            // Create new FaqSubject row
            $faqSubject = new FaqSubject();
            $faqSubject->setFaq($faq);
            $faqSubject->setSubject($subject);

            $faq->addFaqSubject($faqSubject);
            $this->entityManager->persist($faqSubject);

            if ($flush) {
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param Faq $faq
     * @param ArrayCollection|array $subjects
     */
    public function removeSubjectsFromFaq(Faq $faq, $subjects) {
        foreach ($subjects as $subject) {
            $this->removeSubjectFromFaq($faq, $subject, false);
        }
        $this->entityManager->flush();
    }

    /**
     * @param Faq $faq
     * @param Subject $subject
     * @param boolean $flush
     */
    public function removeSubjectFromFaq(Faq $faq, Subject $subject, $flush=true) {
        // Check if FaqSubject row to be removed exists
        $exists = $this->entityManager
        ->getRepository(FaqSubject::class)
        ->findOneBy(['faq' => $faq, 'subject' => $subject]);

        if ($exists) {
            // Delete the associated FaqSubject
            $faq->removeFaqSubject($exists);
            $this->entityManager->remove($exists);

            if ($flush) {
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param Faq $faq
     * @param ArrayCollection|array $faqpages
     */
    public function addFaqpagesToFaq(Faq $faq, $faqpages) {
        foreach ($faqpages as $faqpage) {
            $this->addFaqpageToFaq($faq, $faqpage, false);
        }
        $this->entityManager->flush();
    }

    /**
     * @param Faq $faq
     * @param Faqpage $faqpage
     * @param boolean $flush
     */
    public function addFaqpageToFaq(Faq $faq, Faqpage $faqpage, $flush=true) {
        // Check if FaqFaqpage row already exists for new subject
        $duplicate = $this->entityManager
        ->getRepository(FaqFaqpage::class)
        ->findBy(['faq' => $faq, 'faqpage' => $faqpage]);

        if (!$duplicate) {
            // Create new FaqFaqpage row
            $faqFaqPage = new FaqFaqpage();
            $faqFaqPage->setFaq($faq);
            $faqFaqPage->setFaqpage($faqpage);

            $faq->addFaqFaqpage($faqFaqPage);
            $this->entityManager->persist($faqFaqPage);

            if ($flush) {
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param Faq $faq
     * @param ArrayCollection|array $faqpages
     */
    public function removeFaqpagesFromFaq(Faq $faq, $faqpages) {
        foreach ($faqpages as $faqpage) {
            $this->removeFaqpageFromFaq($faq, $faqpage, false);
        }
        $this->entityManager->flush();
    }

    /**
     * @param Faq $faq
     * @param Faqpage $faqpage
     * @param boolean $flush
     */
    public function removeFaqpageFromFaq(Faq $faq, Faqpage $faqpage, $flush=true) {
        // Check if FaqFaqpage row to be removed exists
        $exists = $this->entityManager
        ->getRepository(FaqFaqpage::class)
        ->findOneBy(['faq' => $faq, 'faqpage' => $faqpage]);
        
        if ($exists) {
            // Delete the associated FaqFaqpage
            $faq->removeFaqFaqpage($exists);
            $this->entityManager->remove($exists);
            
            if ($flush) {
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param Faq $faq
     * @param boolean $flush
     */
    public function deleteFaq(Faq $faq, $flush=true) {
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

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}