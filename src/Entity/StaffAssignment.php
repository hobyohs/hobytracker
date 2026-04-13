<?php

namespace App\Entity;

use App\Repository\StaffAssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaffAssignmentRepository::class)]
#[ORM\UniqueConstraint(name: 'user_year_unique', columns: ['user_id', 'seminar_year'])]
class StaffAssignment
{
    public function __toString(): string
    {
        return $this->getFullName() . ' (' . $this->seminarYear . ')';
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'staffAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    #[ORM\Column(length: 20)]
    private string $status = 'active';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $position = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $shirtSize = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\ManyToOne(inversedBy: 'staffAssignments')]
    private ?LetterGroup $letterGroup = null;

    #[ORM\ManyToOne(inversedBy: 'staffAssignments')]
    private ?DormRoom $dormRoom = null;

    // Emergency contact
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ecFirstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ecLastName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ecRelationship = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $ecPhone1 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $ecPhone2 = null;

    // Dietary
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dietInfo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dietRestrictions = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $dietSeverity = null;

    // Medical
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $currentRx = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $currentConditions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exerciseLimits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $allergies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medAllergies = null;

    // Requirements
    #[ORM\Column(nullable: true)]
    private ?bool $paperworkComplete = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hobyAppComplete = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hoursComplete = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ambRegistered = null;

    #[ORM\Column(nullable: true)]
    private ?bool $fundraisingComplete = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bgCheckSubmitted = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bgCheckComplete = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $requirementNotes = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $psmsUploadedOn = null;

    // Assignments
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $assignmentCheckIn = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $assignmentClosingCeremonies = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $assignmentCheckOut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $assignmentCheckInNotes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $assignmentClosingCeremoniesNotes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $assignmentCheckOutNotes = null;

    // ========== Convenience getters for User properties ==========

    public function getFirstName(): ?string
    {
        return $this->user?->getFirstName();
    }

    public function getLastName(): ?string
    {
        return $this->user?->getLastName();
    }

    public function getPrefName(): ?string
    {
        return $this->user?->getPrefName();
    }

    public function getConsolidatedFirstName(): ?string
    {
        return $this->user?->getConsolidatedFirstName();
    }

    public function getFullName(): ?string
    {
        return $this->user?->getFullName();
    }

    public function getEmail(): ?string
    {
        return $this->user?->getEmail();
    }

    public function getUserId(): ?int
    {
        return $this->user?->getId();
    }

    public function getCellPhone(): ?string
    {
        return $this->user?->getCellPhone();
    }

    public function getGender(): ?string
    {
        return $this->user?->getGender();
    }

    public function getPronouns(): ?string
    {
        return $this->user?->getPronouns();
    }

    // ========== Computed methods ==========

    public function getSortRank(): ?int
    {
        if ($this->position == 'Senior Facilitator') return 1;
        elseif ($this->position == 'Junior Facilitator') return 2;
        elseif ($this->position == 'J-Crew') return 3;
        else return 0;
    }

    public function getControlledAge(): ?int
    {
        if (is_null($this->age)) {
            if (in_array($this->position, ['J-Crew', 'Junior Facilitator'])) {
                return 0;
            } else {
                return 100;
            }
        }
        return $this->age;
    }

    public function getDorm(): ?string
    {
        return $this->dormRoom?->getDorm() ?? '';
    }

    public function getRoom(): ?string
    {
        return $this->dormRoom?->getRoom() ?? '';
    }

    public function getCofacilitators(): ?ArrayCollection
    {
        $lg = $this->letterGroup;
        if (is_null($lg)) return null;
        return $lg->getFacilitatorsExcept($this->getUserId());
    }

    // ========== Standard getters and setters ==========

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function getShirtSize(): ?string
    {
        return $this->shirtSize;
    }

    public function setShirtSize(?string $shirtSize): self
    {
        $this->shirtSize = $shirtSize;
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getLetterGroup(): ?LetterGroup
    {
        return $this->letterGroup;
    }

    public function setLetterGroup(?LetterGroup $letterGroup): self
    {
        $this->letterGroup = $letterGroup;
        return $this;
    }

    public function getDormRoom(): ?DormRoom
    {
        return $this->dormRoom;
    }

    public function setDormRoom(?DormRoom $dormRoom): self
    {
        $this->dormRoom = $dormRoom;
        return $this;
    }

    public function getEcFirstName(): ?string { return $this->ecFirstName; }
    public function setEcFirstName(?string $v): self { $this->ecFirstName = $v; return $this; }

    public function getEcLastName(): ?string { return $this->ecLastName; }
    public function setEcLastName(?string $v): self { $this->ecLastName = $v; return $this; }

    public function getEcRelationship(): ?string { return $this->ecRelationship; }
    public function setEcRelationship(?string $v): self { $this->ecRelationship = $v; return $this; }

    public function getEcPhone1(): ?string { return $this->ecPhone1; }
    public function setEcPhone1(?string $v): self { $this->ecPhone1 = $v; return $this; }

    public function getEcPhone2(): ?string { return $this->ecPhone2; }
    public function setEcPhone2(?string $v): self { $this->ecPhone2 = $v; return $this; }

    public function getDietInfo(): ?string { return $this->dietInfo; }
    public function setDietInfo(?string $v): self { $this->dietInfo = $v; return $this; }

    public function getDietRestrictions(): ?string { return $this->dietRestrictions; }
    public function setDietRestrictions(?string $v): self { $this->dietRestrictions = $v; return $this; }

    public function getDietSeverity(): ?string { return $this->dietSeverity; }
    public function setDietSeverity(?string $v): self { $this->dietSeverity = $v; return $this; }

    public function getCurrentRx(): ?string { return $this->currentRx; }
    public function setCurrentRx(?string $v): self { $this->currentRx = $v; return $this; }

    public function getCurrentConditions(): ?string { return $this->currentConditions; }
    public function setCurrentConditions(?string $v): self { $this->currentConditions = $v; return $this; }

    public function getExerciseLimits(): ?string { return $this->exerciseLimits; }
    public function setExerciseLimits(?string $v): self { $this->exerciseLimits = $v; return $this; }

    public function getAllergies(): ?string { return $this->allergies; }
    public function setAllergies(?string $v): self { $this->allergies = $v; return $this; }

    public function getMedAllergies(): ?string { return $this->medAllergies; }
    public function setMedAllergies(?string $v): self { $this->medAllergies = $v; return $this; }

    public function isPaperworkComplete(): ?bool { return $this->paperworkComplete; }
    public function setPaperworkComplete(?bool $v): self { $this->paperworkComplete = $v; return $this; }

    public function isHobyAppComplete(): ?bool { return $this->hobyAppComplete; }
    public function setHobyAppComplete(?bool $v): self { $this->hobyAppComplete = $v; return $this; }

    public function isHoursComplete(): ?bool { return $this->hoursComplete; }
    public function setHoursComplete(?bool $v): self { $this->hoursComplete = $v; return $this; }

    public function isAmbRegistered(): ?bool { return $this->ambRegistered; }
    public function setAmbRegistered(?bool $v): self { $this->ambRegistered = $v; return $this; }

    public function isFundraisingComplete(): ?bool { return $this->fundraisingComplete; }
    public function setFundraisingComplete(?bool $v): self { $this->fundraisingComplete = $v; return $this; }

    public function isBgCheckSubmitted(): ?bool { return $this->bgCheckSubmitted; }
    public function setBgCheckSubmitted(?bool $v): self { $this->bgCheckSubmitted = $v; return $this; }

    public function isBgCheckComplete(): ?bool { return $this->bgCheckComplete; }
    public function setBgCheckComplete(?bool $v): self { $this->bgCheckComplete = $v; return $this; }

    public function getRequirementNotes(): ?string { return $this->requirementNotes; }
    public function setRequirementNotes(?string $v): self { $this->requirementNotes = $v; return $this; }

    public function getPsmsUploadedOn(): ?string { return $this->psmsUploadedOn; }
    public function setPsmsUploadedOn(?string $v): self { $this->psmsUploadedOn = $v; return $this; }

    public function getAssignmentCheckIn(): ?string { return $this->assignmentCheckIn; }
    public function setAssignmentCheckIn(?string $v): self { $this->assignmentCheckIn = $v; return $this; }

    public function getAssignmentClosingCeremonies(): ?string { return $this->assignmentClosingCeremonies; }
    public function setAssignmentClosingCeremonies(?string $v): self { $this->assignmentClosingCeremonies = $v; return $this; }

    public function getAssignmentCheckOut(): ?string { return $this->assignmentCheckOut; }
    public function setAssignmentCheckOut(?string $v): self { $this->assignmentCheckOut = $v; return $this; }

    public function getAssignmentCheckInNotes(): ?string { return $this->assignmentCheckInNotes; }
    public function setAssignmentCheckInNotes(?string $v): self { $this->assignmentCheckInNotes = $v; return $this; }

    public function getAssignmentClosingCeremoniesNotes(): ?string { return $this->assignmentClosingCeremoniesNotes; }
    public function setAssignmentClosingCeremoniesNotes(?string $v): self { $this->assignmentClosingCeremoniesNotes = $v; return $this; }

    public function getAssignmentCheckOutNotes(): ?string { return $this->assignmentCheckOutNotes; }
    public function setAssignmentCheckOutNotes(?string $v): self { $this->assignmentCheckOutNotes = $v; return $this; }
}
