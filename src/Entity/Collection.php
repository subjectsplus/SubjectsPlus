<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collection.
 *
 * @ORM\Table(name="collection")
 * @ORM\Entity
 */
class Collection
{
    /**
     * @var int
     *
     * @ORM\Column(name="collection_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $collectionId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=65535, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="shortform", type="text", length=65535, nullable=false)
     */
    private $shortform;

    public function getCollectionId(): ?int
    {
        return $this->collectionId;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShortform(): ?string
    {
        return $this->shortform;
    }

    public function setShortform(string $shortform): self
    {
        $this->shortform = $shortform;

        return $this;
    }
}
