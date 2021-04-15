<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlusletSection
 *
 * @ORM\Table(name="pluslet_section", indexes={@ORM\Index(name="fk_pt_pluslet_id_idx", columns={"pluslet_id"}), @ORM\Index(name="fk_pt_tab_id_idx", columns={"section_id"})})
 * @ORM\Entity
 */
class PlusletSection
{
    /**
     * @var int
     *
     * @ORM\Column(name="pluslet_section_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $plusletSectionId;

    /**
     * @var int
     *
     * @ORM\Column(name="pluslet_id", type="bigint", nullable=false)
     */
    private $plusletId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="pcolumn", type="integer", nullable=false)
     */
    private $pcolumn;

    /**
     * @var int
     *
     * @ORM\Column(name="prow", type="integer", nullable=false)
     */
    private $prow;

    /**
     * @var \Section
     *
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="plusletSections")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="section_id")
     * })
     */
    private $section;


    /**
     * @ORM\ManyToOne(targetEntity=Pluslet::class, inversedBy="plusletSections", fetch="EAGER")
     * @ORM\JoinColumn(name="pluslet_id", referencedColumnName="pluslet_id", nullable=false)
     */
    private $pluslet;

    public function __construct()
    {
        $this->pluslets = new ArrayCollection();
    }

    public function getPlusletSectionId(): ?int
    {
        return $this->plusletSectionId;
    }

    public function getPlusletId(): ?string
    {
        return $this->plusletId;
    }

    public function setPlusletId(string $plusletId): self
    {
        $this->plusletId = $plusletId;

        return $this;
    }

    public function getPcolumn(): ?int
    {
        return $this->pcolumn;
    }

    public function setPcolumn(int $pcolumn): self
    {
        $this->pcolumn = $pcolumn;

        return $this;
    }

    public function getProw(): ?int
    {
        return $this->prow;
    }

    public function setProw(int $prow): self
    {
        $this->prow = $prow;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection|Pluslet[]
     */
    public function getPluslets(): Collection
    {
        return $this->pluslets;
    }

    public function addPluslet(Pluslet $pluslet): self
    {
        if (!$this->pluslets->contains($pluslet)) {
            $this->pluslets[] = $pluslet;
        }

        return $this;
    }

    public function removePluslet(Pluslet $pluslet): self
    {
        $this->pluslets->removeElement($pluslet);

        return $this;
    }

    public function getPluslet(): ?Pluslet
    {
        return $this->pluslet;
    }

    public function setPluslet(?Pluslet $pluslet): self
    {
        $this->pluslet = $pluslet;

        return $this;
    }
}
