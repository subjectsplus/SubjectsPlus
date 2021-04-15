<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section.
 *
 * @ORM\Table(name="section", indexes={@ORM\Index(name="fk_section_tab_idx", columns={"tab_id"})})
 * @ORM\Entity
 */
class Section
{
    /**
     * @var int
     *
     * @ORM\Column(name="section_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sectionId;

    /**
     * @var int
     *
     * @ORM\Column(name="section_index", type="integer", nullable=false)
     */
    private $sectionIndex = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="layout", type="string", length=255, nullable=false, options={"default"="4-4-4"})
     */
    private $layout = '4-4-4';

    /**
     * @var \Tab
     *
     * @ORM\ManyToOne(targetEntity="Tab", inversedBy="sections")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tab_id", referencedColumnName="tab_id")
     * })
     */
    private $tab;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlusletSection", mappedBy="section")
     */
    private $plusletSections;

    public function __construct()
    {
        $this->plusletSections = new \Doctrine\ORM\PersistentCollection();
    }

    public function getSectionId(): ?string
    {
        return $this->sectionId;
    }

    public function getSectionIndex(): ?int
    {
        return $this->sectionIndex;
    }

    public function setSectionIndex(int $sectionIndex): self
    {
        $this->sectionIndex = $sectionIndex;

        return $this;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function getTab(): ?Tab
    {
        return $this->tab;
    }

    public function setTab(?Tab $tab): self
    {
        $this->tab = $tab;

        return $this;
    }

    public function getPlusletSections()
    {
        return $this->plusletSections;
    }

    public function addPlusletSection(PlusletSection $plusletSection): self
    {
        if (!$this->plusletSections->contains($plusletSection)) {
            $this->plusletSections[] = $plusletSection;
            $plusletSection->setPluslet($this);
        }

        return $this;
    }

    public function removePlusletSection(PlusletSection $plusletSection): self
    {
        if ($this->plusletSections->removeElement($plusletSection)) {
            // set the owning side to null (unless already changed)
            if ($plusletSection->getPluslet() === $this) {
                $plusletSection->setPluslet(null);
            }
        }

        return $this;
    }
}
