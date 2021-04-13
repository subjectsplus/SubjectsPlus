<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pluslet
 *
 * @ORM\Table(name="pluslet", indexes={@ORM\Index(name="INDEXSEARCHpluslet", columns={"body"})})
 * @ORM\Entity
 */
class Pluslet
{
    /**
     * @var int
     *
     * @ORM\Column(name="pluslet_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $plusletId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=0, nullable=false)
     */
    private $body;

    /**
     * @var string|null
     *
     * @ORM\Column(name="local_file", type="string", length=100, nullable=true)
     */
    private $localFile;

    /**
     * @var int
     *
     * @ORM\Column(name="clone", type="integer", nullable=false)
     */
    private $clone = '0';

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
     * @var int
     *
     * @ORM\Column(name="hide_titlebar", type="integer", nullable=false)
     */
    private $hideTitlebar = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="collapse_body", type="integer", nullable=false)
     */
    private $collapseBody = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="titlebar_styling", type="string", length=100, nullable=true)
     */
    private $titlebarStyling;

    /**
     * @var int
     *
     * @ORM\Column(name="favorite_box", type="integer", nullable=false)
     */
    private $favoriteBox = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="master", type="integer", nullable=false)
     */
    private $master = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="target_blank_links", type="integer", nullable=false)
     */
    private $targetBlankLinks = '0';

    /**
     * @ORM\OneToMany(targetEntity=PlusletSection::class, mappedBy="pluslet")
     */
    private $plusletSections;

    public function __construct()
    {
        $this->plusletSections = new ArrayCollection();
    }

    public function getPlusletId(): ?string
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

    public function getClone(): ?int
    {
        return $this->clone;
    }

    public function setClone(int $clone): self
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

    public function getHideTitlebar(): ?int
    {
        return $this->hideTitlebar;
    }

    public function setHideTitlebar(int $hideTitlebar): self
    {
        $this->hideTitlebar = $hideTitlebar;

        return $this;
    }

    public function getCollapseBody(): ?int
    {
        return $this->collapseBody;
    }

    public function setCollapseBody(int $collapseBody): self
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

    public function getFavoriteBox(): ?int
    {
        return $this->favoriteBox;
    }

    public function setFavoriteBox(int $favoriteBox): self
    {
        $this->favoriteBox = $favoriteBox;

        return $this;
    }

    public function getMaster(): ?int
    {
        return $this->master;
    }

    public function setMaster(int $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getTargetBlankLinks(): ?int
    {
        return $this->targetBlankLinks;
    }

    public function setTargetBlankLinks(int $targetBlankLinks): self
    {
        $this->targetBlankLinks = $targetBlankLinks;

        return $this;
    }

    /**
     * @return Collection|PlusletSection[]
     */
    public function getPlusletSections(): Collection
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
