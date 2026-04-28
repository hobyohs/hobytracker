<?php

namespace App\Entity;

use App\Repository\BedCheckAssignmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BedCheckAssignmentRepository::class)]
#[ORM\UniqueConstraint(
    name: 'bed_check_unique',
    columns: ['staff_assignment_id', 'dorm', 'floor', 'night', 'seminar_year']
)]
class BedCheckAssignment
{
    public const NIGHTS = ['Thursday', 'Friday', 'Saturday'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?StaffAssignment $staffAssignment = null;

    #[ORM\Column(length: 100)]
    private ?string $dorm = null;

    #[ORM\Column(length: 50)]
    private ?string $floor = null;

    #[ORM\Column(length: 10)]
    private ?string $night = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    public function getId(): ?int { return $this->id; }

    public function getStaffAssignment(): ?StaffAssignment { return $this->staffAssignment; }
    public function setStaffAssignment(?StaffAssignment $sa): self { $this->staffAssignment = $sa; return $this; }

    public function getDorm(): ?string { return $this->dorm; }
    public function setDorm(string $dorm): self { $this->dorm = $dorm; return $this; }

    public function getFloor(): ?string { return $this->floor; }
    public function setFloor(string $floor): self { $this->floor = $floor; return $this; }

    public function getNight(): ?string { return $this->night; }
    public function setNight(string $night): self { $this->night = $night; return $this; }

    public function getSeminarYear(): ?int { return $this->seminarYear; }
    public function setSeminarYear(int $year): self { $this->seminarYear = $year; return $this; }

    public function getStaffName(): string
    {
        return $this->staffAssignment?->getConsolidatedFirstName() . ' ' . $this->staffAssignment?->getLastName();
    }
}
