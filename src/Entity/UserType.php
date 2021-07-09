<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserType.
 *
 * @ORM\Table(name="user_type")
 * @ORM\Entity
 */
class UserType
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", length=100, nullable=false)
     */
    private $userType;

    public function getUserTypeId(): ?int
    {
        return $this->userTypeId;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    // Register Magic Method to Print the userType
    public function __toString() {
        return $this->userType;
    }
}
