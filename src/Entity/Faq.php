<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faq.
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="App\Repository\FaqRepository")
 */
class Faq
{
    /**
     * @var int
     *
     * @ORM\Column(name="faq_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $faqId;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255, nullable=false)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=false)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=false)
     */
    private $keywords;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\FaqSubject", mappedBy="faq")
     */
    private $faqSubject;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\FaqFaqpage", mappedBy="faq")
     */
    private $faqFaqpage;

    public function getFaqId(): ?int
    {
        return $this->faqId;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function addFaqSubject(FaqSubject $faqSubject): self
    {
        if (!$this->faqSubject->contains($faqSubject)) {
            $this->faqSubject[] = $faqSubject;
            $faqSubject->setFaq($this);
        }

        return $this;
    }

    public function removeFaqSubject(FaqSubject $faqSubject): self
    {
        if ($this->faqSubject->removeElement($faqSubject)) {
            $faqSubject->setFaq(null);
        }

        return $this;
    }

    public function addFaqpage(FaqFaqpage $faqFaqPage): self
    {
        if (!$this->faqFaqpage->contains($faqFaqPage)) {
            $this->faqFaqpage[] = $faqFaqPage;
            $faqFaqPage->setFaq($this);
        }

        return $this;
    }

    public function removeFaqpage(FaqFaqpage $faqFaqPage): self
    {
        if ($this->faqSubject->removeElement($faqFaqPage)) {
            $faqFaqPage->setFaq(null);
        }

        return $this;
    }
}
