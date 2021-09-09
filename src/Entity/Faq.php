<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

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
     * @var array|null
     * 
     * @ORM\Column(name="keywords", type="json", nullable=true)
     */
    private $keywords;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable="false")
     */
    private $active;

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

    public function __construct() {
        $this->faqSubject = new ArrayCollection();
        $this->faqFaqpage = new ArrayCollection();
        $this->active = true;
    }

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

    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    public function addKeyword(string $keyword): self
    {
        if (!$this->keywords)
            $this->keywords = array();

        if (!in_array($keyword, $this->keywords)) {
            array_push($this->keywords, $keyword);
        }

        return $this;
    }

    public function removeKeyword(string $keyword): self
    {
        $index = array_search($keyword, $this->keywords);
        if ($index !== false) {
            array_splice($this->keywords, $index, 1);
        }

        return $this;
    }

    public function getFaqSubjects(): ArrayCollection {
        return $this->faqSubject;
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

    public function getFaqPages(): ArrayCollection {
        return $this->faqFaqpage;
    }

    public function addFaqFaqpage(FaqFaqpage $faqFaqPage): self
    {
        if (!$this->faqFaqpage->contains($faqFaqPage)) {
            $this->faqFaqpage[] = $faqFaqPage;
            $faqFaqPage->setFaq($this);
        }

        return $this;
    }

    public function removeFaqFaqpage(FaqFaqpage $faqFaqPage): self
    {
        if ($this->faqSubject->removeElement($faqFaqPage)) {
            $faqFaqPage->setFaq(null);
        }

        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        
        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function __toString(): string {
        return "Entity: Faq, Id: " . $this->getFaqId();
    }
}
