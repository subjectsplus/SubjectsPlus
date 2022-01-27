<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tab.
 *
 * @ORM\Table(name="tab", indexes={@ORM\Index(name="fk_t_subject_id_idx", columns={"subject_id"})})
 * @ORM\Entity
 */
class Tab
{
    /**
     * @var int
     *
     * @ORM\Column(name="tab_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tabId;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=120, nullable=false, options={"default": "Main"})
     */
    private $label = 'Main';

    /**
     * @var int
     *
     * @ORM\Column(name="tab_index", type="integer", nullable=false)
     */
    private $tabIndex = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="external_url", type="string", length=500, nullable=true)
     */
    private $externalUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="visibility", type="integer", nullable=false, options={"default": "1"})
     */
    private $visibility = 1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parent", type="string", length=500, nullable=true)
     */
    private $parent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="children", type="string", length=500, nullable=true)
     */
    private $children;

    /**
     * @var string|null
     *
     * @ORM\Column(name="extra", type="string", length=500, nullable=true)
     */
    private $extra;

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="tabs")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")
     * })
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Section", mappedBy="tab")
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): \Doctrine\Common\Collections\ArrayCollection
    {
        return $this->sections;
    }

    public function getTabId(): ?string
    {
        return $this->tabId;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getTabIndex(): ?int
    {
        return $this->tabIndex;
    }

    public function setTabIndex(int $tabIndex): self
    {
        $this->tabIndex = $tabIndex;

        return $this;
    }

    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?string $externalUrl): self
    {
        $this->externalUrl = $externalUrl;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): ?string
    {
        return $this->children;
    }

    public function setChildren(?string $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getExtra(): ?string
    {
        return $this->extra;
    }

    public function setExtra(?string $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function toArray()
    {
        return [
            'label' => $this->getLabel(),
            'external_url' => $this->getExternalUrl(),
        ];
    }
}
