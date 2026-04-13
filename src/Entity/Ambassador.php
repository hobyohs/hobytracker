<?php

namespace App\Entity;

use App\Repository\AmbassadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmbassadorRepository::class)]
class Ambassador
{
    
    public function __toString() {
        return $this->getFullName();
    }
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prefName = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $ethnicity = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pronouns = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $county = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $homePhone = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $cellPhone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $school = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $shirtSize = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $parent1FirstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $parent1LastName = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $parent1Phone1 = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $parent1Phone2 = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $parent1Email = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $parent2FirstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $parent2LastName = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $parent2Phone1 = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $parent2Phone2 = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $parent2Email = null;

    #[ORM\Column]
    private ?bool $checkedIn = false;

    #[ORM\Column]
    private ?bool $checkedOut = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $checkout_deposit_decision = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checkin_paperwork = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checkin_deposit = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $checkin_deposit_method = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $checkin_deposit_notes = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checkin_meds = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cg_form = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $psms_uploaded_on = null;

    #[ORM\ManyToOne(inversedBy: 'ambassadors')]
    private ?LetterGroup $letterGroup = null;

    #[ORM\OneToMany(mappedBy: 'ambassador', targetEntity: ComingsAndGoings::class)]
    private Collection $comingsAndGoings;

    #[ORM\Column(nullable: true)]
    private ?bool $checkin_doctor_form = null;

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
    private ?string $dietRestrictions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dietInfo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $dietSeverity = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bedThursday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bedFriday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bedSaturday = null;

    #[ORM\ManyToOne]
    private ?User $bedThursdayUser = null;

    #[ORM\ManyToOne]
    private ?User $bedFridayUser = null;

    #[ORM\ManyToOne]
    private ?User $bedSaturdayUser = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $currentConditions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exerciseLimits = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $allergies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medAllergies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $approvedOtc = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $currentRx = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $thankyouType = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $thankyouName = null;

    #[ORM\Column]
    private ?bool $takingBus = FALSE;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $busToContact = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $busToPhone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $busFromContact = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $busFromPhone = null;

    #[ORM\Column]
    private ?bool $juniorCallMade = FALSE;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $juniorCallNotes = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $juniorCallDisposition = null;

    #[ORM\ManyToOne(inversedBy: 'ambassadors')]
    private ?DormRoom $dormRoom = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'registered';

    public function __construct()
    {
        $this->comingsAndGoings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsolidatedFirstName(): ?string
    {
        // If there's a preferred name on record, use that. If not, use first name.        
        if (empty($this->prefName) OR ($this->prefName == $this->firstName)) return $this->firstName;
        else return $this->prefName;
    }
    
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    
    public function getAlphaDisplayName()
    {
        return $this->lastName.", ".$this->getFirstName();
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

    public function getEthnicity(): ?string
    {
        return $this->ethnicity;
    }

    public function setEthnicity(?string $ethnicity): self
    {
        $this->ethnicity = $ethnicity;

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

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(?string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->homePhone;
    }

    public function setHomePhone(?string $homePhone): self
    {
        $this->homePhone = $homePhone;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;

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

    public function getParent1FirstName(): ?string
    {
        return $this->parent1FirstName;
    }

    public function setParent1FirstName(?string $parent1FirstName): self
    {
        $this->parent1FirstName = $parent1FirstName;

        return $this;
    }

    public function getParent1LastName(): ?string
    {
        return $this->parent1LastName;
    }

    public function setParent1LastName(?string $parent1LastName): self
    {
        $this->parent1LastName = $parent1LastName;

        return $this;
    }

    public function getParent1Phone1(): ?string
    {
        return $this->parent1Phone1;
    }

    public function setParent1Phone1(?string $parent1Phone1): self
    {
        $this->parent1Phone1 = $parent1Phone1;

        return $this;
    }

    public function getParent1Phone2(): ?string
    {
        return $this->parent1Phone2;
    }

    public function setParent1Phone2(?string $parent1Phone2): self
    {
        $this->parent1Phone2 = $parent1Phone2;

        return $this;
    }

    public function getParent1Email(): ?string
    {
        return $this->parent1Email;
    }

    public function setParent1Email(?string $parent1Email): self
    {
        $this->parent1Email = $parent1Email;

        return $this;
    }

    public function getParent2FirstName(): ?string
    {
        return $this->parent2FirstName;
    }

    public function setParent2FirstName(?string $parent2FirstName): self
    {
        $this->parent2FirstName = $parent2FirstName;

        return $this;
    }

    public function getParent2LastName(): ?string
    {
        return $this->parent2LastName;
    }

    public function setParent2LastName(?string $parent2LastName): self
    {
        $this->parent2LastName = $parent2LastName;

        return $this;
    }

    public function getParent2Phone1(): ?string
    {
        return $this->parent2Phone1;
    }

    public function setParent2Phone1(?string $parent2Phone1): self
    {
        $this->parent2Phone1 = $parent2Phone1;

        return $this;
    }

    public function getParent2Phone2(): ?string
    {
        return $this->parent2Phone2;
    }

    public function setParent2Phone2(?string $parent2Phone2): self
    {
        $this->parent2Phone2 = $parent2Phone2;

        return $this;
    }

    public function getParent2Email(): ?string
    {
        return $this->parent2Email;
    }

    public function setParent2Email(?string $parent2Email): self
    {
        $this->parent2Email = $parent2Email;

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

    public function setRoom(?string $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function isCheckedIn(): ?bool
    {
        return $this->checkedIn;
    }

    public function setCheckedIn(bool $checkedIn): self
    {
        $this->checkedIn = $checkedIn;

        return $this;
    }

    public function isCheckedOut(): ?bool
    {
        return $this->checkedOut;
    }

    public function setCheckedOut(bool $checkedOut): self
    {
        $this->checkedOut = $checkedOut;

        return $this;
    }

    public function getCheckoutDepositDecision(): ?string
    {
        return $this->checkout_deposit_decision;
    }

    public function setCheckoutDepositDecision(?string $checkout_deposit_decision): self
    {
        $this->checkout_deposit_decision = $checkout_deposit_decision;

        return $this;
    }

    public function isCheckinPaperwork(): ?bool
    {
        return $this->checkin_paperwork;
    }

    public function setCheckinPaperwork(?bool $checkin_paperwork): self
    {
        $this->checkin_paperwork = $checkin_paperwork;

        return $this;
    }

    public function isCheckinDeposit(): ?bool
    {
        return $this->checkin_deposit;
    }

    public function setCheckinDeposit(?bool $checkin_deposit): self
    {
        $this->checkin_deposit = $checkin_deposit;

        return $this;
    }

    public function getCheckinDepositMethod(): ?string
    {
        return $this->checkin_deposit_method;
    }

    public function setCheckinDepositMethod(?string $checkin_deposit_method): self
    {
        $this->checkin_deposit_method = $checkin_deposit_method;

        return $this;
    }

    public function getCheckinDepositNotes(): ?string
    {
        return $this->checkin_deposit_notes;
    }

    public function setCheckinDepositNotes(?string $checkin_deposit_notes): self
    {
        $this->checkin_deposit_notes = $checkin_deposit_notes;

        return $this;
    }

    public function isCheckinMeds(): ?bool
    {
        return $this->checkin_meds;
    }

    public function setCheckinMeds(?bool $checkin_meds): self
    {
        $this->checkin_meds = $checkin_meds;

        return $this;
    }

    public function isCgForm(): ?bool
    {
        return $this->cg_form;
    }

    public function setCgForm(?bool $cg_form): self
    {
        $this->cg_form = $cg_form;

        return $this;
    }

    public function getPsmsUploadedOn(): ?\DateTimeInterface
    {
        return $this->psms_uploaded_on;
    }

    public function setPsmsUploadedOn(?\DateTimeInterface $psms_uploaded_on): self
    {
        $this->psms_uploaded_on = $psms_uploaded_on;

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
    
    public function getFacilitators(): Collection
    {
        $lg = $this->letterGroup;
        if (is_null($lg)) return new ArrayCollection();
        else return $lg->getFacilitators();
    }

    /**
     * @return Collection<int, ComingsAndGoings>
     */
    public function getComingsAndGoings(): Collection
    {
        return $this->comingsAndGoings;
    }

    /**
     * @return Collection<int, ComingsAndGoings>
     */
    public function getActiveComingsAndGoings(): Collection
    {
        return $this->comingsAndGoings->filter(
            fn(ComingsAndGoings $cg) => $cg->isActive()
        );
    }

    public function addComingsAndGoing(ComingsAndGoings $comingsAndGoing): self
    {
        if (!$this->comingsAndGoings->contains($comingsAndGoing)) {
            $this->comingsAndGoings->add($comingsAndGoing);
            $comingsAndGoing->setAmbassador($this);
        }

        return $this;
    }

    public function removeComingsAndGoing(ComingsAndGoings $comingsAndGoing): self
    {
        if ($this->comingsAndGoings->removeElement($comingsAndGoing)) {
            // set the owning side to null (unless already changed)
            if ($comingsAndGoing->getAmbassador() === $this) {
                $comingsAndGoing->setAmbassador(null);
            }
        }

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

    public function isCheckinDoctorForm(): ?bool
    {
        return $this->checkin_doctor_form;
    }

    public function setCheckinDoctorForm(?bool $checkin_doctor_form): self
    {
        $this->checkin_doctor_form = $checkin_doctor_form;

        return $this;
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

    public function setEcPhone2(string $ecPhone2): self
    {
        $this->ecPhone2 = $ecPhone2;

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

    public function getDietInfo(): ?string
    {
        return $this->dietInfo;
    }

    public function setDietInfo(?string $dietInfo): self
    {
        $this->dietInfo = $dietInfo;

        return $this;
    }

    public function getDietSeverity(): ?string
    {
        return $this->dietSeverity;
    }

    public function setDietSeverity(?string $dietSeverity): self
    {
        $this->dietSeverity = $dietSeverity;

        return $this;
    }

    public function isBedThursday(): ?bool
    {
        return $this->bedThursday;
    }

    public function setBedThursday(?bool $bedThursday): self
    {
        $this->bedThursday = $bedThursday;

        return $this;
    }

    public function isBedFriday(): ?bool
    {
        return $this->bedFriday;
    }

    public function setBedFriday(?bool $bedFriday): self
    {
        $this->bedFriday = $bedFriday;

        return $this;
    }

    public function isBedSaturday(): ?bool
    {
        return $this->bedSaturday;
    }

    public function setBedSaturday(?bool $bedSaturday): self
    {
        $this->bedSaturday = $bedSaturday;

        return $this;
    }

    public function getBedThursdayUser(): ?User
    {
        return $this->bedThursdayUser;
    }

    public function setBedThursdayUser(?User $bedThursdayUser): self
    {
        $this->bedThursdayUser = $bedThursdayUser;

        return $this;
    }

    public function getBedFridayUser(): ?User
    {
        return $this->bedFridayUser;
    }

    public function setBedFridayUser(?User $bedFridayUser): self
    {
        $this->bedFridayUser = $bedFridayUser;

        return $this;
    }

    public function getBedSaturdayUser(): ?User
    {
        return $this->bedSaturdayUser;
    }

    public function setBedSaturdayUser(?User $bedSaturdayUser): self
    {
        $this->bedSaturdayUser = $bedSaturdayUser;

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

    public function getApprovedOtc(): ?string
    {
        return $this->approvedOtc;
    }

    public function setApprovedOtc(?string $approvedOtc): self
    {
        $this->approvedOtc = $approvedOtc;

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

    public function getThankyouType(): ?string
    {
        return $this->thankyouType;
    }

    public function setThankyouType(?string $thankyouType): self
    {
        $this->thankyouType = $thankyouType;

        return $this;
    }

    public function getThankyouName(): ?string
    {
        return $this->thankyouName;
    }

    public function setThankyouName(?string $thankyouName): self
    {
        $this->thankyouName = $thankyouName;

        return $this;
    }
    
    public function isTakingBus(): ?bool
    {
        return $this->takingBus;
    }
    
    public function setTakingBus(bool $takingBus): self
    {
        $this->takingBus = $takingBus;
    
        return $this;
    }

    public function getBusToContact(): ?string
    {
        return $this->busToContact;
    }

    public function setBusToContact(?string $busToContact): self
    {
        $this->busToContact = $busToContact;

        return $this;
    }

    public function getBusToPhone(): ?string
    {
        return $this->busToPhone;
    }

    public function setBusToPhone(?string $busToPhone): self
    {
        $this->busToPhone = $busToPhone;

        return $this;
    }

    public function getBusFromContact(): ?string
    {
        return $this->busFromContact;
    }

    public function setBusFromContact(?string $busFromContact): self
    {
        $this->busFromContact = $busFromContact;

        return $this;
    }

    public function getBusFromPhone(): ?string
    {
        return $this->busFromPhone;
    }

    public function setBusFromPhone(?string $busFromPhone): self
    {
        $this->busFromPhone = $busFromPhone;

        return $this;
    }

    public function isJuniorCallMade(): ?bool
    {
        return $this->juniorCallMade;
    }

    public function setJuniorCallMade(bool $juniorCallMade): self
    {
        $this->juniorCallMade = $juniorCallMade;

        return $this;
    }

    public function getJuniorCallNotes(): ?string
    {
        return $this->juniorCallNotes;
    }

    public function setJuniorCallNotes(?string $juniorCallNotes): self
    {
        $this->juniorCallNotes = $juniorCallNotes;

        return $this;
    }

    public function getJuniorCallDisposition(): ?string
    {
        return $this->juniorCallDisposition;
    }

    public function setJuniorCallDisposition(?string $juniorCallDisposition): self
    {
        $this->juniorCallDisposition = $juniorCallDisposition;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
