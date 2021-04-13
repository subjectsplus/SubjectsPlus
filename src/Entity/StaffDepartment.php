<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffDepartment
 *
 * @ORM\Table(name="staff_department")
 * @ORM\Entity
 */
class StaffDepartment
{
    /**
     * @var int
     *
     * @ORM\Column(name="staff_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $staffId;

    /**
     * @var int
     *
     * @ORM\Column(name="department_id", type="integer", nullable=false)
     */
    private $departmentId;

    public function getStaffId(): ?int
    {
        return $this->staffId;
    }

    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    public function setDepartmentId(int $departmentId): self
    {
        $this->departmentId = $departmentId;

        return $this;
    }
}
