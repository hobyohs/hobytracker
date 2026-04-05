<?php

namespace App\Entity;

use App\Repository\LetterGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'letterGroup', targetEntity: User::class)]
    private Collection $facilitators;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $interview_assignment = null;

    public function __construct()
    {
        $this->ambassadors = new ArrayCollection();
        $this->facilitators = new ArrayCollection();
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

    /**
     * @return Collection<int, Ambassador>
     */
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
            // set the owning side to null (unless already changed)
            if ($ambassador->getLetterGroup() === $this) {
                $ambassador->setLetterGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFacilitators(): Collection
    {
        return $this->facilitators;
    }
    
    public function getFacilitatorsExcept($userid): ArrayCollection
    {
        $return_array = new ArrayCollection();
        foreach($this->facilitators as $fac) {
            if ($fac->getId() != $userid) {
                $return_array[] = $fac;
            }
        }
        return $return_array;
    }
    
    public function getJuniorFacilitators(): ArrayCollection
    {
        $return_array = new ArrayCollection();
        foreach($this->facilitators as $fac) {
            if ($fac->getPosition() == "Junior Facilitator") {
                $return_array[] = $fac;
            }
        }
        return $return_array;
    }

    public function addFacilitator(User $facilitator): self
    {
        if (!$this->facilitators->contains($facilitator)) {
            $this->facilitators->add($facilitator);
            $facilitator->setLetterGroup($this);
        }

        return $this;
    }

    public function removeFacilitator(User $facilitator): self
    {
        if ($this->facilitators->removeElement($facilitator)) {
            // set the owning side to null (unless already changed)
            if ($facilitator->getLetterGroup() === $this) {
                $facilitator->setLetterGroup(null);
            }
        }

        return $this;
    }

    public function getInterviewAssignment(): ?string
    {
        return $this->interview_assignment;
    }

    public function setInterviewAssignment(?string $interview_assignment): self
    {
        $this->interview_assignment = $interview_assignment;

        return $this;
    }
}
