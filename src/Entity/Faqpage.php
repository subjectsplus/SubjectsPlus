<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Faqpage.
 *
 * @ORM\Table(name="faqpage")
 * @ORM\Entity
 */
class Faqpage
{
    /**
     * @var int
     *
     * @ORM\Column(name="faqpage_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"faq"})
     */
    private $faqpageId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Groups({"faq"})
     * @OrderBy({"name" = "ASC"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     * @Groups({"faq"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Faq", mappedBy="faqpages", cascade={"persist", "remove"})
     */
    private $faqs;

    public function __construct() {
        $this->faqs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getFaqpageId(): ?int
    {
        return $this->faqpageId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Faq[]
     */
    public function getFaqs(): \Doctrine\Common\Collections\Collection
    {
        return $this->faqs;
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs[] = $faq;
            $faq->addFaqpage($this);
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faqs->removeElement($faq)) {
            $faq->removeFaqpage($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
