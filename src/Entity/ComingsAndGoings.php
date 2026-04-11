<?php

namespace App\Entity;

use App\Repository\ComingsAndGoingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComingsAndGoingsRepository::class)]
class ComingsAndGoings
{
    public function __toString() {
        
        if (is_null($this->getDeparture()) AND is_null($this->getArrival())) {
            return null;
        } else if (is_null($this->getDeparture())) {
            return $this->getAmbassador() . " " . $this->getArrival()->format('Y-m-d H:i:s');
        } else if (is_null($this->getArrival())) {
            return $this->getAmbassador() . " " . $this->getDeparture()->format('Y-m-d H:i:s');
        } else {
            return $this->getAmbassador() . " " . $this->getDeparture()->format('Y-m-d H:i:s') . " - " . $this->getArrival()->format('Y-m-d H:i:s');
        }
        
    }
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $departure = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $arrival = null;

    #[ORM\ManyToOne(inversedBy: 'comingsAndGoings')]
    private ?Ambassador $ambassador = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checked_out = null;

    #[ORM\ManyToOne]
    private ?User $checkedOutBy = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checked_in = null;

    #[ORM\ManyToOne]
    private ?User $checkedInBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    #[ORM\Column]
    private bool $active = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparture(): ?\DateTimeInterface
    {
        return $this->departure;
    }

    public function setDeparture(?\DateTimeInterface $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?\DateTimeInterface
    {
        return $this->arrival;
    }

    public function setArrival(?\DateTimeInterface $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getAmbassador(): ?Ambassador
    {
        return $this->ambassador;
    }

    public function setAmbassador(?Ambassador $ambassador): self
    {
        $this->ambassador = $ambassador;

        return $this;
    }

    public function isCheckedOut(): ?bool
    {
        return $this->checked_out;
    }

    public function setCheckedOut(bool $checked_out): self
    {
        $this->checked_out = $checked_out;

        return $this;
    }

    public function getCheckedOutBy(): ?User
    {
        return $this->checkedOutBy;
    }

    public function setCheckedOutBy(?User $checkedOutBy): self
    {
        $this->checkedOutBy = $checkedOutBy;

        return $this;
    }

    public function isCheckedIn(): ?bool
    {
        return $this->checked_in;
    }

    public function setCheckedIn(bool $checked_in): self
    {
        $this->checked_in = $checked_in;

        return $this;
    }

    public function getCheckedInBy(): ?User
    {
        return $this->checkedInBy;
    }

    public function setCheckedInBy(?User $checkedInBy): self
    {
        $this->checkedInBy = $checkedInBy;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
