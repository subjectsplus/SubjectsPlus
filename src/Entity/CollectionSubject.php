<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CollectionSubject
 *
 * @ORM\Table(name="collection_subject")
 * @ORM\Entity
 */
class CollectionSubject
{
    /**
     * @var int
     *
     * @ORM\Column(name="collection_subject_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $collectionSubjectId;

    /**
     * @var int
     *
     * @ORM\Column(name="collection_id", type="integer", nullable=false)
     */
    private $collectionId;

    /**
     * @var int
     *
     * @ORM\Column(name="subject_id", type="bigint", nullable=false)
     */
    private $subjectId;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort = '0';

    public function getCollectionSubjectId(): ?int
    {
        return $this->collectionSubjectId;
    }

    public function getCollectionId(): ?int
    {
        return $this->collectionId;
    }

    public function setCollectionId(int $collectionId): self
    {
        $this->collectionId = $collectionId;

        return $this;
    }

    public function getSubjectId(): ?string
    {
        return $this->subjectId;
    }

    public function setSubjectId(string $subjectId): self
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
