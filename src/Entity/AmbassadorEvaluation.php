<?php

namespace App\Entity;

use App\Repository\AmbassadorEvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmbassadorEvaluationRepository::class)]
#[ORM\UniqueConstraint(name: 'amb_eval_year_unique', columns: ['ambassador_id', 'seminar_year'])]
class AmbassadorEvaluation
{
    public function __toString(): string
    {
        return ($this->ambassador?->getFullName() ?? 'Unknown') . ' (' . $this->seminarYear . ')';
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ambassador $ambassador = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    // The co-facilitator who submitted on behalf of the group (null for backfilled/legacy records)
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?StaffAssignment $submittedBy = null;

    #[ORM\Column(length: 20)]
    private string $status = 'draft';

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $submittedAt = null;

    // Rating fields (e.g. 1–5 stored as short strings)
    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalEngaged = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalService = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalRecommendation = null;

    // Text fields
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalPros = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalCons = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalComments = null;

    // ===== Getters & Setters =====

    public function getId(): ?int { return $this->id; }

    public function getAmbassador(): ?Ambassador { return $this->ambassador; }
    public function setAmbassador(?Ambassador $ambassador): self { $this->ambassador = $ambassador; return $this; }

    public function getSeminarYear(): ?int { return $this->seminarYear; }
    public function setSeminarYear(int $seminarYear): self { $this->seminarYear = $seminarYear; return $this; }

    public function getSubmittedBy(): ?StaffAssignment { return $this->submittedBy; }
    public function setSubmittedBy(?StaffAssignment $submittedBy): self { $this->submittedBy = $submittedBy; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }

    public function isSubmitted(): bool { return $this->status === 'submitted'; }

    public function getSubmittedAt(): ?\DateTimeInterface { return $this->submittedAt; }
    public function setSubmittedAt(?\DateTimeInterface $submittedAt): self { $this->submittedAt = $submittedAt; return $this; }

    public function getEvalEngaged(): ?string { return $this->evalEngaged; }
    public function setEvalEngaged(?string $v): self { $this->evalEngaged = $v; return $this; }

    public function getEvalService(): ?string { return $this->evalService; }
    public function setEvalService(?string $v): self { $this->evalService = $v; return $this; }

    public function getEvalRecommendation(): ?string { return $this->evalRecommendation; }
    public function setEvalRecommendation(?string $v): self { $this->evalRecommendation = $v; return $this; }

    public function getEvalPros(): ?string { return $this->evalPros; }
    public function setEvalPros(?string $v): self { $this->evalPros = $v; return $this; }

    public function getEvalCons(): ?string { return $this->evalCons; }
    public function setEvalCons(?string $v): self { $this->evalCons = $v; return $this; }

    public function getEvalComments(): ?string { return $this->evalComments; }
    public function setEvalComments(?string $v): self { $this->evalComments = $v; return $this; }
}
