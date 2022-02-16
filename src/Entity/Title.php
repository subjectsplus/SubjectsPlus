<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Filter\FullTextSearchFilter;
use App\Filter\LetterSearchFilter;

/**
 * Title.
 *
 * @ORM\Table(name="title")
 * @ORM\Entity(repositoryClass="App\Repository\TitleRepository")
 * 
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     order={"title": "ASC"}
 * )
 * 
 * @ApiFilter(SearchFilter::class, properties={
 *      "title": "partial",
 *      "alternateTitle": "partial",
 *      "location.format.format": "exact",
 *      "location.eresDisplay": "exact"
 *  })
 * 
 * @ApiFilter(FullTextSearchFilter::class, properties={
 *      "title": "partial",
 *      "alternateTitle": "partial"
 * })
 * 
 * @ApiFilter(LetterSearchFilter::class, properties={
 *      "title": "start",
 *      "alternateTitle": "start"
 * })
 */
class Title
{
    /**
     * @var int
     *
     * @ORM\Column(name="title_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $titleId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="alternate_title", type="string", length=255, nullable=true)
     */
    private $alternateTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="internal_notes", type="text", length=16777215, nullable=true)
     */
    private $internalNotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pre", type="string", length=255, nullable=true)
     */
    private $pre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_modified_by", type="string", length=50, nullable=true)
     */
    private $lastModifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $lastModified;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="title")
     * @ApiSubresource(maxDepth=1)
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rank", mappedBy="title")
     */
    private $ranks;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->location = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ranks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getTitleId(): ?int
    {
        return $this->titleId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAlternateTitle(): ?string
    {
        return $this->alternateTitle;
    }

    public function setAlternateTitle(?string $alternateTitle): self
    {
        $this->alternateTitle = $alternateTitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInternalNotes(): ?string
    {
        return $this->internalNotes;
    }

    public function setInternalNotes(?string $internalNotes): self
    {
        $this->internalNotes = $internalNotes;

        return $this;
    }

    public function getPre(): ?string
    {
        return $this->pre;
    }

    public function setPre(?string $pre): self
    {
        $this->pre = $pre;

        return $this;
    }

    public function getLastModifiedBy(): ?string
    {
        return $this->lastModifiedBy;
    }

    public function setLastModifiedBy(?string $lastModifiedBy): self
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->lastModified;
    }

    public function setLastModified(\DateTimeInterface $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->location->contains($location)) {
            $this->location[] = $location;
            $location->setTitle($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->location->removeElement($location)) {
            $location->setTitle(null);
        }

        return $this;
    }
}
