<?php

namespace App\Entity;

use App\Repository\LetterGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;

#[ORM\Entity(repositoryClass: LetterGroupRepository::class)]
class LetterGroup
{
    public function __toString() {
        return $this->getLetter();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $letter = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $homeBuilding = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $homeRoom = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $color = null;

    #[ORM\OneToMany(mappedBy: 'letterGroup', targetEntity: Ambassador::class)]
    private Collection $ambassadors;

    #[ORM\OneToMany(mappedBy: 'letterGroup', targetEntity: StaffAssignment::class)]
    private Collection $staffAssignments;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $interview_assignment = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    public function __construct()
    {
        $this->ambassadors = new ArrayCollection();
        $this->staffAssignments = new ArrayCollection();
    }

    public function printLetterOnly()
    {
        return "<span class=\"letter ".$this->color."\">".$this->letter."</span>";
    }

    public function printLetterGroup()
    {
        return "<span class=\"letter ".$this->color."\">Group ".$this->letter."</span>";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $letter): self
    {
        $this->letter = $letter;
        return $this;
    }

    public function getHomeBuilding(): ?string
    {
        return $this->homeBuilding;
    }

    public function setHomeBuilding(?string $homeBuilding): self
    {
        $this->homeBuilding = $homeBuilding;
        return $this;
    }

    public function getHomeRoom(): ?string
    {
        return $this->homeRoom;
    }

    public function setHomeRoom(?string $homeRoom): self
    {
        $this->homeRoom = $homeRoom;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    // ========== Ambassadors ==========

    /** @return Collection<int, Ambassador> */
    public function getAmbassadors(): Collection
    {
        return $this->ambassadors;
    }

    public function addAmbassador(Ambassador $ambassador): self
    {
        if (!$this->ambassadors->contains($ambassador)) {
            $this->ambassadors->add($ambassador);
            $ambassador->setLetterGroup($this);
        }
        return $this;
    }

    public function removeAmbassador(Ambassador $ambassador): self
    {
        if ($this->ambassadors->removeElement($ambassador)) {
            if ($ambassador->getLetterGroup() === $this) {
                $ambassador->setLetterGroup(null);
            }
        }
        return $this;
    }

    // ========== Staff Assignments (facilitators) ==========

    /** @return Collection<int, StaffAssignment> */
    public function getStaffAssignments(): Collection
    {
        return $this->staffAssignments;
    }

    /** Alias for template compatibility */
    public function getFacilitators(): Collection
    {
        return $this->staffAssignments;
    }

    public function getFacilitatorsExcept(int $userId): ArrayCollection
    {
        $result = new ArrayCollection();
        foreach ($this->staffAssignments as $sa) {
            if ($sa->getUserId() !== $userId) {
                $result[] = $sa;
            }
        }
        return $result;
    }

    public function getJuniorFacilitators(): ArrayCollection
    {
        $result = new ArrayCollection();
        foreach ($this->staffAssignments as $sa) {
            if ($sa->getRoleGroup() === StaffAssignment::ROLE_JUNIOR_FACILITATOR) {
                $result[] = $sa;
            }
        }
        return $result;
    }

    public function addStaffAssignment(StaffAssignment $sa): self
    {
        if (!$this->staffAssignments->contains($sa)) {
            $this->staffAssignments->add($sa);
            $sa->setLetterGroup($this);
        }
        return $this;
    }

    public function removeStaffAssignment(StaffAssignment $sa): self
    {
        if ($this->staffAssignments->removeElement($sa)) {
            if ($sa->getLetterGroup() === $this) {
                $sa->setLetterGroup(null);
            }
        }
        return $this;
    }

    // ========== Other ==========

    public function getInterviewAssignment(): ?string
    {
        return $this->interview_assignment;
    }

    public function setInterviewAssignment(?string $interview_assignment): self
    {
        $this->interview_assignment = $interview_assignment;
        return $this;
    }

    public function getSeminarYear(): ?int
    {
        return $this->seminarYear;
    }

    public function setSeminarYear(int $seminarYear): self
    {
        $this->seminarYear = $seminarYear;
        return $this;
    }
}
