<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaqFaqpage.
 *
 * @ORM\Table(name="faq_faqpage", indexes={@ORM\Index(name="fk_ff_faqpage_id_idx", columns={"faqpage_id"}), @ORM\Index(name="fk_ff_faq_id_idx", columns={"faq_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\FaqFaqpageRepository")
 */
class FaqFaqpage
{
    /**
     * @var int
     *
     * @ORM\Column(name="faq_faqpage_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $faqFaqpageId;

    /**
     * @var \Faq
     *
     * @ORM\ManyToOne(targetEntity="Faq", inversedBy="faqFaqpage")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="faq_id", referencedColumnName="faq_id")
     * })
     */
    private $faq;

    /**
     * @var \Faqpage
     *
     * @ORM\ManyToOne(targetEntity="Faqpage")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="faqpage_id", referencedColumnName="faqpage_id")
     * })
     */
    private $faqpage;

    public function getFaqFaqpageId(): ?int
    {
        return $this->faqFaqpageId;
    }

    public function getFaq(): ?Faq
    {
        return $this->faq;
    }

    public function setFaq(?Faq $faq): self
    {
        $this->faq = $faq;

        return $this;
    }

    public function getFaqpage(): ?Faqpage
    {
        return $this->faqpage;
    }

    public function setFaqpage(?Faqpage $faqpage): self
    {
        $this->faqpage = $faqpage;

        return $this;
    }
}
