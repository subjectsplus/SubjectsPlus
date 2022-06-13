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
 * Section.
 *
 * @ORM\Table(name="section", uniqueConstraints={@ORM\UniqueConstraint(name="section_uuid", columns={"uuid"})},
 *      indexes={@ORM\Index(name="fk_section_tab_idx", columns={"tab_id"})})
 * @ORM\Entity
 * 
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put", "delete"},
 *     order={"sectionIndex": "ASC"}
 * )
 */
class Section
{
    /**
     * @var int
     *
     * @ORM\Column(name="section_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ApiProperty(identifier=false)
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
     * @ORM\Column(name="layout", type="string", length=255, nullable=false, options={"default": "4-4-4"})
     */
    private $layout = '4-4-4';

    /**
     * @ORM\ManyToOne(targetEntity=Tab::class, inversedBy="sections")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="tab_id", referencedColumnName="tab_id")
     * })
     */
    private $tab;
    
    /**
     * @ORM\OneToMany(targetEntity=Pluslet::class, mappedBy="section", cascade={"persist", "remove"})
     * @ORM\OrderBy({"pcolumn" = "ASC", "prow" = "ASC"})
     * @ApiSubresource(maxDepth=1)
     */
    private $pluslets;

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
        $this->pluslets = new ArrayCollection();
    }

    public function getSectionId(): ?int
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
            $pluslet->setSection($this);
        }

        return $this;
    }

    public function removePluslet(Pluslet $pluslet): self
    {
        if ($this->pluslets->removeElement($pluslet)) {
            // set the owning side to null (unless already changed)
            if ($pluslet->getSection() === $this) {
                $pluslet->setSection(null);
            }
        }

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }
}
