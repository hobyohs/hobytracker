<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __toString(): string
    {
        return $this->getFullName();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /** @var string The hashed password */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    private $newPassword;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prefName = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $cellPhone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $pronouns = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: StaffAssignment::class)]
    private Collection $staffAssignments;

    public function __construct()
    {
        $this->staffAssignments = new ArrayCollection();
    }

    // ========== Staff Assignment helpers ==========

    public function getStaffAssignments(): Collection
    {
        return $this->staffAssignments;
    }

    public function addStaffAssignment(StaffAssignment $assignment): self
    {
        if (!$this->staffAssignments->contains($assignment)) {
            $this->staffAssignments->add($assignment);
            $assignment->setUser($this);
        }
        return $this;
    }

    public function removeStaffAssignment(StaffAssignment $assignment): self
    {
        if ($this->staffAssignments->removeElement($assignment)) {
            if ($assignment->getUser() === $this) {
                $assignment->setUser(null);
            }
        }
        return $this;
    }

    public function getActiveAssignment(): ?StaffAssignment
    {
        $month = (int) date('n');
        $year = (int) date('Y');
        $seminarYear = $month >= 9 ? $year + 1 : $year;

        foreach ($this->staffAssignments as $assignment) {
            if ($assignment->getSeminarYear() === $seminarYear) {
                return $assignment;
            }
        }
        return null;
    }

    public function getAssignmentForYear(int $year): ?StaffAssignment
    {
        foreach ($this->staffAssignments as $assignment) {
            if ($assignment->getSeminarYear() === $year) {
                return $assignment;
            }
        }
        return null;
    }

    // ========== Identity fields (on User) ==========

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getUserIdentifier(): string { return (string) $this->email; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    public function getNewPassword(): string { return (string) $this->newPassword; }
    public function setNewPassword($newPassword): self { $this->newPassword = $newPassword; return $this; }

    public function eraseCredentials(): void { $this->newPassword = null; }

    public function isVerified(): bool { return $this->isVerified; }
    public function setIsVerified(bool $isVerified): self { $this->isVerified = $isVerified; return $this; }

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $firstName): self { $this->firstName = $firstName; return $this; }

    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $lastName): self { $this->lastName = $lastName; return $this; }

    public function getPrefName(): ?string { return $this->prefName; }
    public function setPrefName(?string $prefName): self { $this->prefName = $prefName; return $this; }

    public function getConsolidatedFirstName(): ?string
    {
        if (empty($this->prefName) || ($this->prefName == $this->firstName)) return $this->firstName;
        else return $this->prefName;
    }

    public function getFullName(): ?string
    {
        return $this->getConsolidatedFirstName() . ' ' . $this->getLastName();
    }

    public function getCellPhone(): ?string { return $this->cellPhone; }
    public function setCellPhone(?string $cellPhone): self { $this->cellPhone = $cellPhone; return $this; }

    public function getGender(): ?string { return $this->gender; }
    public function setGender(?string $gender): self { $this->gender = $gender; return $this; }

    public function getPronouns(): ?string { return $this->pronouns; }
    public function setPronouns(?string $pronouns): self { $this->pronouns = $pronouns; return $this; }

    // ========== Proxy getters — delegate to active StaffAssignment ==========

    public function getPhoto(): ?string { return $this->getActiveAssignment()?->getPhoto(); }
    public function getPosition(): ?string { return $this->getActiveAssignment()?->getPosition(); }
    public function getShirtSize(): ?string { return $this->getActiveAssignment()?->getShirtSize(); }
    public function getAge(): ?int { return $this->getActiveAssignment()?->getAge(); }
    public function getLetterGroup(): ?LetterGroup { return $this->getActiveAssignment()?->getLetterGroup(); }
    public function getDormRoom(): ?DormRoom { return $this->getActiveAssignment()?->getDormRoom(); }
    public function getDorm(): ?string { return $this->getActiveAssignment()?->getDorm() ?? ''; }
    public function getRoom(): ?string { return $this->getActiveAssignment()?->getRoom() ?? ''; }

    public function getControlledAge(): ?int
    {
        $assignment = $this->getActiveAssignment();
        return $assignment ? $assignment->getControlledAge() : 100;
    }

    public function getSortRank(): ?int
    {
        $assignment = $this->getActiveAssignment();
        return $assignment ? $assignment->getSortRank() : 0;
    }

    public function getCofacilitators(): ?Collection
    {
        $lg = $this->getLetterGroup();
        if (is_null($lg)) return null;
        return $lg->getFacilitatorsExcept($this->getId());
    }

    public function getEcFirstName(): ?string { return $this->getActiveAssignment()?->getEcFirstName(); }
    public function getEcLastName(): ?string { return $this->getActiveAssignment()?->getEcLastName(); }
    public function getEcRelationship(): ?string { return $this->getActiveAssignment()?->getEcRelationship(); }
    public function getEcPhone1(): ?string { return $this->getActiveAssignment()?->getEcPhone1(); }
    public function getEcPhone2(): ?string { return $this->getActiveAssignment()?->getEcPhone2(); }

    public function getDietInfo(): ?string { return $this->getActiveAssignment()?->getDietInfo(); }
    public function getDietRestrictions(): ?string { return $this->getActiveAssignment()?->getDietRestrictions(); }
    public function getDietSeverity(): ?string { return $this->getActiveAssignment()?->getDietSeverity(); }

    public function getCurrentRx(): ?string { return $this->getActiveAssignment()?->getCurrentRx(); }
    public function getCurrentConditions(): ?string { return $this->getActiveAssignment()?->getCurrentConditions(); }
    public function getExerciseLimits(): ?string { return $this->getActiveAssignment()?->getExerciseLimits(); }
    public function getAllergies(): ?string { return $this->getActiveAssignment()?->getAllergies(); }
    public function getMedAllergies(): ?string { return $this->getActiveAssignment()?->getMedAllergies(); }

    public function isPaperworkComplete(): ?bool { return $this->getActiveAssignment()?->isPaperworkComplete(); }
    public function isHobyAppComplete(): ?bool { return $this->getActiveAssignment()?->isHobyAppComplete(); }
    public function isHoursComplete(): ?bool { return $this->getActiveAssignment()?->isHoursComplete(); }
    public function isAmbRegistered(): ?bool { return $this->getActiveAssignment()?->isAmbRegistered(); }
    public function isFundraisingComplete(): ?bool { return $this->getActiveAssignment()?->isFundraisingComplete(); }
    public function isBgCheckSubmitted(): ?bool { return $this->getActiveAssignment()?->isBgCheckSubmitted(); }
    public function isBgCheckComplete(): ?bool { return $this->getActiveAssignment()?->isBgCheckComplete(); }
    public function getRequirementNotes(): ?string { return $this->getActiveAssignment()?->getRequirementNotes(); }
    public function getPsmsUploadedOn(): ?string { return $this->getActiveAssignment()?->getPsmsUploadedOn(); }

    public function getEvalPros(): ?string { return $this->getActiveAssignment()?->getEvalPros(); }
    public function getEvalCons(): ?string { return $this->getActiveAssignment()?->getEvalCons(); }
    public function getEvalDiscussions(): ?string { return $this->getActiveAssignment()?->getEvalDiscussions(); }
    public function getEvalEnthusiastic(): ?string { return $this->getActiveAssignment()?->getEvalEnthusiastic(); }
    public function getEvalOrganized(): ?string { return $this->getActiveAssignment()?->getEvalOrganized(); }
    public function getEvalEqually(): ?string { return $this->getActiveAssignment()?->getEvalEqually(); }
    public function getEvalResponsible(): ?string { return $this->getActiveAssignment()?->getEvalResponsible(); }
    public function getEvalAttentive(): ?string { return $this->getActiveAssignment()?->getEvalAttentive(); }
    public function getEvalInclude(): ?string { return $this->getActiveAssignment()?->getEvalInclude(); }
    public function getEvalProfessional(): ?string { return $this->getActiveAssignment()?->getEvalProfessional(); }
    public function getEvalPunctual(): ?string { return $this->getActiveAssignment()?->getEvalPunctual(); }
    public function getEvalWhynot(): ?string { return $this->getActiveAssignment()?->getEvalWhynot(); }
    public function getEvalComments(): ?string { return $this->getActiveAssignment()?->getEvalComments(); }
    public function isEvalStatus(): ?bool { return $this->getActiveAssignment()?->isEvalStatus() ?? false; }

    public function getAssignmentCheckIn(): ?string { return $this->getActiveAssignment()?->getAssignmentCheckIn(); }
    public function getAssignmentClosingCeremonies(): ?string { return $this->getActiveAssignment()?->getAssignmentClosingCeremonies(); }
    public function getAssignmentCheckOut(): ?string { return $this->getActiveAssignment()?->getAssignmentCheckOut(); }
    public function getAssignmentCheckInNotes(): ?string { return $this->getActiveAssignment()?->getAssignmentCheckInNotes(); }
    public function getAssignmentClosingCeremoniesNotes(): ?string { return $this->getActiveAssignment()?->getAssignmentClosingCeremoniesNotes(); }
    public function getAssignmentCheckOutNotes(): ?string { return $this->getActiveAssignment()?->getAssignmentCheckOutNotes(); }
}
