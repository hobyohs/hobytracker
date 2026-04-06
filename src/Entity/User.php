<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Repository\LetterGroupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    
    public function __toString() {
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

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;
    
    // Only for resetting user password in EasyAdmin 4
    private $newPassword;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prefName = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $cellPhone = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $shirtSize = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $position = null;

    #[ORM\ManyToOne(inversedBy: 'facilitators')]
    private ?LetterGroup $letterGroup = null;

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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dietInfo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dietRestrictions = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $dietSeverity = null;

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

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $requirementNotes = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $pronouns = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $psmsUploadedOn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_pros = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_cons = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_discussions = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_enthusiastic = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_organized = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_equally = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_responsible = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_attentive = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_include = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_professional = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $eval_punctual = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_whynot = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_comments = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $eval_status = false;

    #[ORM\ManyToOne(inversedBy: 'staff')]
    private ?DormRoom $dormRoom = null;

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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: StaffAssignment::class)]
    private Collection $staffAssignments;

    public function __construct()
    {
        $this->staffAssignments = new ArrayCollection();
    }

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * This is for setting user password in EasyAdmin 4
     */
    public function getNewPassword(): string
    {
        return (string) $this->newPassword;
    }
    
    public function setNewPassword($newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->newPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    
    public function getConsolidatedFirstName(): ?string
    {
        // If there's a preferred name on record, use that. If not, use first name.        
        if (empty($this->prefName) OR ($this->prefName == $this->firstName)) return $this->firstName;
        else return $this->prefName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
    
    public function getFullName(): ?string
    {
        return $this->getConsolidatedFirstName() . " " . $this->getLastName();
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

    public function getPrefName(): ?string
    {
        return $this->prefName;
    }

    public function setPrefName(?string $prefName): self
    {
        $this->prefName = $prefName;

        return $this;
    }

    public function getCellPhone(): ?string
    {
        return $this->cellPhone;
    }

    public function setCellPhone(?string $cellPhone): self
    {
        $this->cellPhone = $cellPhone;

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
    
    public function getDormRoom(): ?DormRoom
    {
        return $this->dormRoom;
    }
    
    public function setDormRoom(?DormRoom $dormRoom): self
    {
        $this->dormRoom = $dormRoom;
    
        return $this;
    }

    public function getDorm(): ?string
    {
        $dormroom = $this->dormRoom;
        return (!empty($dormroom)) ? $dormroom->getDorm() : "";
    }
    
    public function getRoom(): ?string
    {
        $dormroom = $this->dormRoom;
        return (!empty($dormroom)) ? $dormroom->getRoom() : "";
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

    public function getLetterGroup(): ?LetterGroup
    {
        return $this->letterGroup;
    }
    
    public function getCofacilitators(): ?Collection
    {
        
        if(is_null($this->letterGroup)) {
            return null;
        } else {
            return $this->letterGroup->getFacilitatorsExcept($this->getId());
        }
                   
    }

    public function setLetterGroup(?LetterGroup $letterGroup): self
    {
        $this->letterGroup = $letterGroup;

        return $this;
    }
    
    public function getSortRank(): ?int
    {
        if ($this->position == "Senior Facilitator") return 1;
        elseif ($this->position == "Junior Facilitator") return 2;
        elseif ($this->position == "J-Crew") return 3;
        else return 0;
    }

    public function getEcFirstName(): ?string
    {
        return $this->ecFirstName;
    }

    public function setEcFirstName(?string $ecFirstName): self
    {
        $this->ecFirstName = $ecFirstName;

        return $this;
    }

    public function getEcLastName(): ?string
    {
        return $this->ecLastName;
    }

    public function setEcLastName(?string $ecLastName): self
    {
        $this->ecLastName = $ecLastName;

        return $this;
    }

    public function getEcRelationship(): ?string
    {
        return $this->ecRelationship;
    }

    public function setEcRelationship(?string $ecRelationship): self
    {
        $this->ecRelationship = $ecRelationship;

        return $this;
    }

    public function getEcPhone1(): ?string
    {
        return $this->ecPhone1;
    }

    public function setEcPhone1(?string $ecPhone1): self
    {
        $this->ecPhone1 = $ecPhone1;

        return $this;
    }

    public function getEcPhone2(): ?string
    {
        return $this->ecPhone2;
    }

    public function setEcPhone2(?string $ecPhone2): self
    {
        $this->ecPhone2 = $ecPhone2;

        return $this;
    }

    public function getDietInfo(): ?string
    {
        return $this->dietInfo;
    }

    public function setDietInfo(?string $dietInfo): self
    {
        $this->dietInfo = $dietInfo;

        return $this;
    }

    public function getDietRestrictions(): ?string
    {
        return $this->dietRestrictions;
    }

    public function setDietRestrictions(?string $dietRestrictions): self
    {
        $this->dietRestrictions = $dietRestrictions;

        return $this;
    }

    public function getDietSeverity(): ?string
    {
        return $this->dietSeverity;
    }

    public function setDietSeverity(string $dietSeverity): self
    {
        $this->dietSeverity = $dietSeverity;

        return $this;
    }

    public function getCurrentRx(): ?string
    {
        return $this->currentRx;
    }

    public function setCurrentRx(?string $currentRx): self
    {
        $this->currentRx = $currentRx;

        return $this;
    }

    public function getCurrentConditions(): ?string
    {
        return $this->currentConditions;
    }

    public function setCurrentConditions(?string $currentConditions): self
    {
        $this->currentConditions = $currentConditions;

        return $this;
    }

    public function getExerciseLimits(): ?string
    {
        return $this->exerciseLimits;
    }

    public function setExerciseLimits(?string $exerciseLimits): self
    {
        $this->exerciseLimits = $exerciseLimits;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getMedAllergies(): ?string
    {
        return $this->medAllergies;
    }

    public function setMedAllergies(?string $medAllergies): self
    {
        $this->medAllergies = $medAllergies;

        return $this;
    }

    public function isPaperworkComplete(): ?bool
    {
        return $this->paperworkComplete;
    }

    public function setPaperworkComplete(?bool $paperworkComplete): self
    {
        $this->paperworkComplete = $paperworkComplete;

        return $this;
    }

    public function isHobyAppComplete(): ?bool
    {
        return $this->hobyAppComplete;
    }

    public function setHobyAppComplete(?bool $hobyAppComplete): self
    {
        $this->hobyAppComplete = $hobyAppComplete;

        return $this;
    }

    public function isHoursComplete(): ?bool
    {
        return $this->hoursComplete;
    }

    public function setHoursComplete(?bool $hoursComplete): self
    {
        $this->hoursComplete = $hoursComplete;

        return $this;
    }

    public function isAmbRegistered(): ?bool
    {
        return $this->ambRegistered;
    }

    public function setAmbRegistered(?bool $ambRegistered): self
    {
        $this->ambRegistered = $ambRegistered;

        return $this;
    }

    public function isFundraisingComplete(): ?bool
    {
        return $this->fundraisingComplete;
    }

    public function setFundraisingComplete(?bool $fundraisingComplete): self
    {
        $this->fundraisingComplete = $fundraisingComplete;

        return $this;
    }

    public function isBgCheckSubmitted(): ?bool
    {
        return $this->bgCheckSubmitted;
    }

    public function setBgCheckSubmitted(?bool $bgCheckSubmitted): self
    {
        $this->bgCheckSubmitted = $bgCheckSubmitted;

        return $this;
    }

    public function isBgCheckComplete(): ?bool
    {
        return $this->bgCheckComplete;
    }

    public function setBgCheckComplete(?bool $bgCheckComplete): self
    {
        $this->bgCheckComplete = $bgCheckComplete;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }
    
    public function getControlledAge(): ?int
    {
        if (is_null($this->age)) {
            if (in_array($this->position,array("J-Crew", "Junior Facilitator"))) {
                $age = 0;
            } else {
                $age = 100;
            }
        } else {
            $age = $this->age;
        }
        return $age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getRequirementNotes(): ?string
    {
        return $this->requirementNotes;
    }

    public function setRequirementNotes(?string $requirementNotes): self
    {
        $this->requirementNotes = $requirementNotes;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPronouns(): ?string
    {
        return $this->pronouns;
    }

    public function setPronouns(?string $pronouns): self
    {
        $this->pronouns = $pronouns;

        return $this;
    }

    public function getPsmsUploadedOn(): ?string
    {
        return $this->psmsUploadedOn;
    }

    public function setPsmsUploadedOn(?string $psmsUploadedOn): self
    {
        $this->psmsUploadedOn = $psmsUploadedOn;

        return $this;
    }

    public function getEvalPros(): ?string
    {
        return $this->eval_pros;
    }

    public function setEvalPros(?string $eval_pros): self
    {
        $this->eval_pros = $eval_pros;

        return $this;
    }

    public function getEvalCons(): ?string
    {
        return $this->eval_cons;
    }

    public function setEvalCons(?string $eval_cons): self
    {
        $this->eval_cons = $eval_cons;

        return $this;
    }

    public function getEvalDiscussions(): ?string
    {
        return $this->eval_discussions;
    }

    public function setEvalDiscussions(?string $eval_discussions): self
    {
        $this->eval_discussions = $eval_discussions;

        return $this;
    }

    public function getEvalEnthusiastic(): ?string
    {
        return $this->eval_enthusiastic;
    }

    public function setEvalEnthusiastic(string $eval_enthusiastic): self
    {
        $this->eval_enthusiastic = $eval_enthusiastic;

        return $this;
    }

    public function getEvalOrganized(): ?string
    {
        return $this->eval_organized;
    }

    public function setEvalOrganized(?string $eval_organized): self
    {
        $this->eval_organized = $eval_organized;

        return $this;
    }

    public function getEvalEqually(): ?string
    {
        return $this->eval_equally;
    }

    public function setEvalEqually(?string $eval_equally): self
    {
        $this->eval_equally = $eval_equally;

        return $this;
    }

    public function getEvalResponsible(): ?string
    {
        return $this->eval_responsible;
    }

    public function setEvalResponsible(?string $eval_responsible): self
    {
        $this->eval_responsible = $eval_responsible;

        return $this;
    }

    public function getEvalAttentive(): ?string
    {
        return $this->eval_attentive;
    }

    public function setEvalAttentive(?string $eval_attentive): self
    {
        $this->eval_attentive = $eval_attentive;

        return $this;
    }

    public function getEvalInclude(): ?string
    {
        return $this->eval_include;
    }

    public function setEvalInclude(?string $eval_include): self
    {
        $this->eval_include = $eval_include;

        return $this;
    }

    public function getEvalProfessional(): ?string
    {
        return $this->eval_professional;
    }

    public function setEvalProfessional(?string $eval_professional): self
    {
        $this->eval_professional = $eval_professional;

        return $this;
    }

    public function getEvalPunctual(): ?string
    {
        return $this->eval_punctual;
    }

    public function setEvalPunctual(?string $eval_punctual): self
    {
        $this->eval_punctual = $eval_punctual;

        return $this;
    }

    public function getEvalWhynot(): ?string
    {
        return $this->eval_whynot;
    }

    public function setEvalWhynot(?string $eval_whynot): self
    {
        $this->eval_whynot = $eval_whynot;

        return $this;
    }

    public function getEvalComments(): ?string
    {
        return $this->eval_comments;
    }

    public function setEvalComments(?string $eval_comments): self
    {
        $this->eval_comments = $eval_comments;

        return $this;
    }

    public function isEvalStatus(): ?bool
    {
        return $this->eval_status;
    }

    public function setEvalStatus(bool $eval_status): self
    {
        $this->eval_status = $eval_status;

        return $this;
    }

    public function getAssignmentCheckIn(): ?string
    {
        return $this->assignmentCheckIn;
    }

    public function setAssignmentCheckIn(?string $assignmentCheckIn): static
    {
        $this->assignmentCheckIn = $assignmentCheckIn;

        return $this;
    }

    public function getAssignmentClosingCeremonies(): ?string
    {
        return $this->assignmentClosingCeremonies;
    }

    public function setAssignmentClosingCeremonies(?string $assignmentClosingCeremonies): static
    {
        $this->assignmentClosingCeremonies = $assignmentClosingCeremonies;

        return $this;
    }

    public function getAssignmentCheckOut(): ?string
    {
        return $this->assignmentCheckOut;
    }

    public function setAssignmentCheckOut(?string $assignmentCheckOut): static
    {
        $this->assignmentCheckOut = $assignmentCheckOut;

        return $this;
    }

    public function getAssignmentCheckInNotes(): ?string
    {
        return $this->assignmentCheckInNotes;
    }

    public function setAssignmentCheckInNotes(?string $assignmentCheckInNotes): static
    {
        $this->assignmentCheckInNotes = $assignmentCheckInNotes;

        return $this;
    }

    public function getAssignmentClosingCeremoniesNotes(): ?string
    {
        return $this->assignmentClosingCeremoniesNotes;
    }

    public function setAssignmentClosingCeremoniesNotes(?string $assignmentClosingCeremoniesNotes): static
    {
        $this->assignmentClosingCeremoniesNotes = $assignmentClosingCeremoniesNotes;

        return $this;
    }

    public function getAssignmentCheckOutNotes(): ?string
    {
        return $this->assignmentCheckOutNotes;
    }

    public function setAssignmentCheckOutNotes(?string $assignmentCheckOutNotes): static
    {
        $this->assignmentCheckOutNotes = $assignmentCheckOutNotes;

        return $this;
    }
    
}
