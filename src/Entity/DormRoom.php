<?php

namespace App\Entity;

use App\Repository\DormRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DormRoomRepository::class)]
class DormRoom
{
    public function __toString() {
        return $this->getDorm() . ' ' . $this->getRoom();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $dorm = null;

    #[ORM\Column(length: 50)]
    private ?string $room = null;

    #[ORM\Column(length: 255)]
    private ?string $bathroom_type = null;

    #[ORM\Column(length: 50)]
    private ?string $floor = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $sort_order = null;

    #[ORM\OneToMany(mappedBy: 'dormRoom', targetEntity: Ambassador::class)]
    private Collection $ambassadors;

    #[ORM\OneToMany(mappedBy: 'dormRoom', targetEntity: StaffAssignment::class)]
    private Collection $staffAssignments;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $beds = null;

    public function __construct()
    {
        $this->ambassadors = new ArrayCollection();
        $this->staffAssignments = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getDorm(): ?string { return $this->dorm; }
    public function setDorm(string $dorm): self { $this->dorm = $dorm; return $this; }

    public function getRoom(): ?string { return $this->room; }
    public function setRoom(string $room): self { $this->room = $room; return $this; }

    public function getBathroomType(): ?string { return $this->bathroom_type; }
    public function setBathroomType(string $bathroom_type): self { $this->bathroom_type = $bathroom_type; return $this; }

    public function getFloor(): ?string { return $this->floor; }
    public function setFloor(string $floor): self { $this->floor = $floor; return $this; }

    public function getSortOrder(): ?int { return $this->sort_order; }
    public function setSortOrder(int $sort_order): self { $this->sort_order = $sort_order; return $this; }

    public function getBeds(): ?int { return $this->beds; }
    public function setBeds(int $beds): self { $this->beds = $beds; return $this; }

    // ========== Ambassadors ==========

    /** @return Collection<int, Ambassador> */
    public function getAmbassadors(): Collection { return $this->ambassadors; }

    public function addAmbassador(Ambassador $ambassador): self
    {
        if (!$this->ambassadors->contains($ambassador)) {
            $this->ambassadors->add($ambassador);
            $ambassador->setDormRoom($this);
        }
        return $this;
    }

    public function removeAmbassador(Ambassador $ambassador): self
    {
        if ($this->ambassadors->removeElement($ambassador)) {
            if ($ambassador->getDormRoom() === $this) {
                $ambassador->setDormRoom(null);
            }
        }
        return $this;
    }

    // ========== Staff Assignments ==========

    /** @return Collection<int, StaffAssignment> */
    public function getStaffAssignments(): Collection { return $this->staffAssignments; }

    /** Alias for template compatibility */
    public function getStaff(): Collection { return $this->staffAssignments; }

    public function addStaffAssignment(StaffAssignment $sa): self
    {
        if (!$this->staffAssignments->contains($sa)) {
            $this->staffAssignments->add($sa);
            $sa->setDormRoom($this);
        }
        return $this;
    }

    public function removeStaffAssignment(StaffAssignment $sa): self
    {
        if ($this->staffAssignments->removeElement($sa)) {
            if ($sa->getDormRoom() === $this) {
                $sa->setDormRoom(null);
            }
        }
        return $this;
    }
}
