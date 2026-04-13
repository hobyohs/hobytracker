<?php

namespace App\Entity;

use App\Repository\StaffAssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaffAssignmentRepository::class)]
#[ORM\UniqueConstraint(name: 'user_year_unique', columns: ['user_id', 'seminar_year'])]
#[ORM\HasLifecycleCallbacks]
class StaffAssignment
{
    // ========== Role group taxonomy ==========
    public const ROLE_SENIOR_FACILITATOR = 'Senior Facilitator';
    public const ROLE_JUNIOR_FACILITATOR = 'Junior Facilitator';
    public const ROLE_TEAM_HQ            = 'Team HQ';
    public const ROLE_JCREW              = 'J-Crew';
    public const ROLE_MEDICAL            = 'Medical';

    public const ROLE_GROUPS = [
        self::ROLE_SENIOR_FACILITATOR,
        self::ROLE_JUNIOR_FACILITATOR,
        self::ROLE_TEAM_HQ,
        self::ROLE_JCREW,
        self::ROLE_MEDICAL,
    ];

    /** Role groups treated as <21 young staff — no Seminar Ops access, age defaults to 0. */
    public const YOUNG_STAFF_GROUPS = [
        self::ROLE_JUNIOR_FACILITATOR,
        self::ROLE_JCREW,
    ];

    /** Role groups treated as 21+ senior staff — Seminar Ops access allowed. */
    public const SENIOR_OPS_GROUPS = [
        self::ROLE_SENIOR_FACILITATOR,
        self::ROLE_TEAM_HQ,
        self::ROLE_MEDICAL,
    ];

    /**
     * Canonical list of staff positions, ordered by rough frequency of use. This constant
     * is the single source of truth: `getPositionChoices()` returns the keys in order for
     * the admin picker, and this same map classifies each position into a role group for
     * the computed Seminar Ops access check. Adding a new position means adding one line
     * here — the picker and the classifier both pick it up for free.
     *
     * Custom / novel position strings (anything an admin types that isn't in this list)
     * fall through to null roleGroup, which fails closed on Seminar Ops access. If you
     * add a commonly-used custom position, add it here.
     */
    public const POSITION_TO_ROLE_GROUP = [
        'Senior Facilitator'       => self::ROLE_SENIOR_FACILITATOR,
        'Junior Facilitator'       => self::ROLE_JUNIOR_FACILITATOR,
        'J-Crew'                   => self::ROLE_JCREW,
        'Team HQ'                  => self::ROLE_TEAM_HQ,
        'Nurse'                    => self::ROLE_MEDICAL,
        'Counselor'                => self::ROLE_MEDICAL,
        'Leadership Seminar Chair' => self::ROLE_TEAM_HQ,
        'Director of Facilitators' => self::ROLE_TEAM_HQ,
        'Director of Program'      => self::ROLE_TEAM_HQ,
        'Director of Operations'   => self::ROLE_TEAM_HQ,
        'Director of Fundraising'  => self::ROLE_TEAM_HQ,
        'Director of Media'        => self::ROLE_TEAM_HQ,
        'J-Crew Lead'              => self::ROLE_TEAM_HQ,
        'Board President'          => self::ROLE_TEAM_HQ,
        'Board Vice President'     => self::ROLE_TEAM_HQ,
        'Board Secretary'          => self::ROLE_TEAM_HQ,
        'Board Treasurer'          => self::ROLE_TEAM_HQ,
        'Board Member'             => self::ROLE_TEAM_HQ,
    ];

    /**
     * Returns the canonical position list in display order, for use as datalist options
     * on the admin position picker. Derived from POSITION_TO_ROLE_GROUP keys so the
     * ordering is guaranteed consistent with the classifier.
     */
    public static function getPositionChoices(): array
    {
        return array_keys(self::POSITION_TO_ROLE_GROUP);
    }

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

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $roleGroup = null;

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
        return match ($this->roleGroup) {
            self::ROLE_SENIOR_FACILITATOR => 1,
            self::ROLE_JUNIOR_FACILITATOR => 2,
            self::ROLE_JCREW              => 3,
            default                       => 0,
        };
    }

    /**
     * Returns true if this assignment is a <21 young staff role (Junior Facilitator
     * or J-Crew). Null roleGroup returns false — unknown is NOT treated as young.
     */
    public function isYoungStaff(): bool
    {
        return in_array($this->roleGroup, self::YOUNG_STAFF_GROUPS, true);
    }

    /**
     * Returns true if this assignment is a 21+ senior ops role (Senior Facilitator,
     * Team HQ, or Medical) with access to check-in, check-out, bed checks, and C&G.
     * Null roleGroup returns false — unknown fails closed, locking out until an
     * admin classifies the assignment.
     */
    public function isSeniorOps(): bool
    {
        return in_array($this->roleGroup, self::SENIOR_OPS_GROUPS, true);
    }

    public function getControlledAge(): ?int
    {
        if (is_null($this->age)) {
            return $this->isYoungStaff() ? 0 : 100;
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

    public function getRoleGroup(): ?string
    {
        return $this->roleGroup;
    }

    public function setRoleGroup(?string $roleGroup): self
    {
        $this->roleGroup = $roleGroup;
        return $this;
    }

    /**
     * Always recompute roleGroup from position on save. roleGroup is not user-editable —
     * it's a purely derived backend attribute used for Seminar Ops access checks. If a
     * position string isn't in POSITION_TO_ROLE_GROUP (custom/novel value), roleGroup
     * becomes null, which fails closed on Seminar Ops.
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function populateRoleGroupFromPosition(): void
    {
        $this->roleGroup = ($this->position !== null && isset(self::POSITION_TO_ROLE_GROUP[$this->position]))
            ? self::POSITION_TO_ROLE_GROUP[$this->position]
            : null;
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
