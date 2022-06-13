<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Tab.
 *
 * @ORM\Table(name="tab", uniqueConstraints={@ORM\UniqueConstraint(name="tab_uuid", columns={"uuid"})}, 
 *  indexes={@ORM\Index(name="fk_t_subject_id_idx", columns={"subject_id"})})
 * @ORM\Entity
 * 
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put", "delete"},
 *     order={"tabIndex": "ASC"}
 * )
 */
class Tab
{
    /**
     * @var int
     *
     * @ORM\Column(name="tab_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ApiProperty(identifier=false)
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
     * @ORM\Column(name="visibility", type="boolean", nullable=false, options={"default": "1"})
     */
    private $visibility = true;

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
     * @ORM\OneToMany(targetEntity="Section", mappedBy="tab", cascade={"persist", "remove"})
     * @ORM\OrderBy({"sectionIndex" = "ASC"})
     * @ApiSubresource(maxDepth=1)
     */
    private $sections;

    /**
     * @var Symfony\Component\Uid\Uuid|null
     * @ORM\Column(type="uuid", nullable=true)
     * @ApiProperty(identifier=true)
     * @SerializedName("id")
     */
    private $uuid;

    public function __construct(Uuid $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::v4();
        $this->sections = new ArrayCollection();
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

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
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

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setTab($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getTab() === $this) {
                $section->setTab(null);
            }
        }

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }
}
