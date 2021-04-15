<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Department.
 *
 * @ORM\Table(name="department", indexes={@ORM\Index(name="INDEXSEARCHdepart", columns={"name"})})
 * @ORM\Entity
 */
class Department
{
    /**
     * @var int
     *
     * @ORM\Column(name="department_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $departmentId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var int
     *
     * @ORM\Column(name="department_sort", type="integer", nullable=false)
     */
    private $departmentSort = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=false)
     */
    private $telephone = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDepartmentSort(): ?int
    {
        return $this->departmentSort;
    }

    public function setDepartmentSort(int $departmentSort): self
    {
        $this->departmentSort = $departmentSort;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
