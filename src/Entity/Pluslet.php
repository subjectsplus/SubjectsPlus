<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * Pluslet.
 *
 * @ORM\Table(name="pluslet")
 * @ORM\Entity
 * 
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 * )
 */
class Pluslet
{
    /**
     * @var int
     *
     * @ORM\Column(name="pluslet_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $plusletId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=0)
     */
    private $body;

    /**
     * @var string|null
     *
     * @ORM\Column(name="local_file", type="string", length=100, nullable=true)
     */
    private $localFile;

    /**
     * @var bool
     *
     * @ORM\Column(name="clone", type="boolean")
     */
    private $clone = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="extra", type="text", length=16777215, nullable=true)
     */
    private $extra;

    /**
     * @var bool
     *
     * @ORM\Column(name="hide_titlebar", type="boolean")
     */
    private $hideTitlebar = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="collapse_body", type="boolean")
     */
    private $collapseBody = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titlebar_styling", type="string", length=100, nullable=true)
     */
    private $titlebarStyling;

    /**
     * @var bool
     *
     * @ORM\Column(name="favorite_box", type="boolean")
     */
    private $favoriteBox = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="master", type="boolean", nullable=true)
     */
    private $master = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="target_blank_links", type="boolean")
     */
    private $targetBlankLinks = false;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="pluslets")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="section_id", referencedColumnName="section_id")
     * })
     */
    private $section;

    /**
     * @ORM\Column(name="pcolumn", type="integer")
     */
    private $pcolumn;

    /**
     * @ORM\Column(name="prow", type="integer")
     */
    private $prow;

    public function getPlusletId(): ?int
    {
        return $this->plusletId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getLocalFile(): ?string
    {
        return $this->localFile;
    }

    public function setLocalFile(?string $localFile): self
    {
        $this->localFile = $localFile;

        return $this;
    }

    public function getClone(): bool
    {
        return $this->clone;
    }

    public function setClone(bool $clone): self
    {
        $this->clone = $clone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getHideTitlebar(): bool
    {
        return $this->hideTitlebar;
    }

    public function setHideTitlebar(bool $hideTitlebar): self
    {
        $this->hideTitlebar = $hideTitlebar;

        return $this;
    }

    public function getCollapseBody(): bool
    {
        return $this->collapseBody;
    }

    public function setCollapseBody(bool $collapseBody): self
    {
        $this->collapseBody = $collapseBody;

        return $this;
    }

    public function getTitlebarStyling(): ?string
    {
        return $this->titlebarStyling;
    }

    public function setTitlebarStyling(?string $titlebarStyling): self
    {
        $this->titlebarStyling = $titlebarStyling;

        return $this;
    }

    public function getFavoriteBox(): bool
    {
        return $this->favoriteBox;
    }

    public function setFavoriteBox(bool $favoriteBox): self
    {
        $this->favoriteBox = $favoriteBox;

        return $this;
    }

    public function getMaster(): ?bool
    {
        return $this->master;
    }

    public function setMaster(?bool $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getTargetBlankLinks(): bool
    {
        return $this->targetBlankLinks;
    }

    public function setTargetBlankLinks(bool $targetBlankLinks): self
    {
        $this->targetBlankLinks = $targetBlankLinks;

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
}
