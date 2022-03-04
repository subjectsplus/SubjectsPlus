<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Faq.
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="App\Repository\FaqRepository")
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups": {"faq"}}
 * )
 */
class Faq
{
    /**
     * @var int
     *
     * @ORM\Column(name="faq_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"faq"})
     */
    private $faqId;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text", length=255, nullable=false)
     * @Groups({"faq"})
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=false)
     * @Groups({"faq"})
     */
    private $answer;

    /**
     * @var array|null
     *
     * @ORM\Column(name="keywords", type="json", nullable=true)
     * @Groups({"faq"})
     */
    private $keywords;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable="false", options={"default": "0"})
     * @Groups({"faq"})
     */
    private $active = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Subject", inversedBy="faqs")
     * @ORM\JoinTable(name="faq_subject", 
     *  joinColumns={
     *         @ORM\JoinColumn(name="faq_id", referencedColumnName="faq_id")
     *  },
     *  inverseJoinColumns={
     *         @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")
     *  })
     * @Groups({"faq"})
     */
    private $subjects;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Faqpage", inversedBy="faqs")
     * @ORM\JoinTable(name="faq_faqpage", 
     *  joinColumns={
     *         @ORM\JoinColumn(name="faq_id", referencedColumnName="faq_id")
     *  },
     *  inverseJoinColumns={
     *         @ORM\JoinColumn(name="faqpage_id", referencedColumnName="faqpage_id")
     *  })
     * @Groups({"faq"})
     */
    private $faqpages;

    public function __construct() {
        $this->subjects = new ArrayCollection();
        $this->faqpages = new ArrayCollection();
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

    public function getSubjects(): \Doctrine\Common\Collections\Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->addFaq($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            $subject->removeFaq($this);
        }

        return $this;
    }

    public function getFaqpages(): \Doctrine\Common\Collections\Collection
    {
        return $this->faqpages;
    }

    public function addFaqpage(Faqpage $faqPage): self
    {
        if (!$this->faqpages->contains($faqPage)) {
            $this->faqpages[] = $faqPage;
            $faqPage->addFaq($this);
        }

        return $this;
    }

    public function removeFaqpage(Faqpage $faqPage): self
    {
        if ($this->faqpages->removeElement($faqPage)) {
            $faqPage->removeFaq($this);
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
