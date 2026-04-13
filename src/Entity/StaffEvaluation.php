<?php

namespace App\Entity;

use App\Repository\StaffEvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaffEvaluationRepository::class)]
#[ORM\UniqueConstraint(name: 'staff_eval_unique', columns: ['subject_id', 'evaluator_id', 'seminar_year'])]
class StaffEvaluation
{
    public function __toString(): string
    {
        $subject = $this->subject?->getFullName() ?? 'Unknown';
        $evaluator = $this->evaluator?->getFullName() ?? 'Unknown';
        return "{$subject} evaluated by {$evaluator} ({$this->seminarYear})";
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // The staff member being evaluated
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'subject_id', nullable: false)]
    private ?StaffAssignment $subject = null;

    // The staff member doing the evaluating (null for backfilled/legacy records)
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'evaluator_id', nullable: true)]
    private ?StaffAssignment $evaluator = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    #[ORM\Column(length: 20)]
    private string $status = 'draft';

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $submittedAt = null;

    // Rating fields
    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalDiscussions = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalEnthusiastic = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalOrganized = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalEqually = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalResponsible = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalAttentive = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalInclude = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalProfessional = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $evalPunctual = null;

    // Text fields
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalPros = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalCons = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalWhynot = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $evalComments = null;

    // ===== Getters & Setters =====

    public function getId(): ?int { return $this->id; }

    public function getSubject(): ?StaffAssignment { return $this->subject; }
    public function setSubject(?StaffAssignment $subject): self { $this->subject = $subject; return $this; }

    public function getEvaluator(): ?StaffAssignment { return $this->evaluator; }
    public function setEvaluator(?StaffAssignment $evaluator): self { $this->evaluator = $evaluator; return $this; }

    public function getSeminarYear(): ?int { return $this->seminarYear; }
    public function setSeminarYear(int $seminarYear): self { $this->seminarYear = $seminarYear; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }

    public function isSubmitted(): bool { return $this->status === 'submitted'; }

    public function getSubmittedAt(): ?\DateTimeInterface { return $this->submittedAt; }
    public function setSubmittedAt(?\DateTimeInterface $submittedAt): self { $this->submittedAt = $submittedAt; return $this; }

    public function getEvalDiscussions(): ?string { return $this->evalDiscussions; }
    public function setEvalDiscussions(?string $v): self { $this->evalDiscussions = $v; return $this; }

    public function getEvalEnthusiastic(): ?string { return $this->evalEnthusiastic; }
    public function setEvalEnthusiastic(?string $v): self { $this->evalEnthusiastic = $v; return $this; }

    public function getEvalOrganized(): ?string { return $this->evalOrganized; }
    public function setEvalOrganized(?string $v): self { $this->evalOrganized = $v; return $this; }

    public function getEvalEqually(): ?string { return $this->evalEqually; }
    public function setEvalEqually(?string $v): self { $this->evalEqually = $v; return $this; }

    public function getEvalResponsible(): ?string { return $this->evalResponsible; }
    public function setEvalResponsible(?string $v): self { $this->evalResponsible = $v; return $this; }

    public function getEvalAttentive(): ?string { return $this->evalAttentive; }
    public function setEvalAttentive(?string $v): self { $this->evalAttentive = $v; return $this; }

    public function getEvalInclude(): ?string { return $this->evalInclude; }
    public function setEvalInclude(?string $v): self { $this->evalInclude = $v; return $this; }

    public function getEvalProfessional(): ?string { return $this->evalProfessional; }
    public function setEvalProfessional(?string $v): self { $this->evalProfessional = $v; return $this; }

    public function getEvalPunctual(): ?string { return $this->evalPunctual; }
    public function setEvalPunctual(?string $v): self { $this->evalPunctual = $v; return $this; }

    public function getEvalPros(): ?string { return $this->evalPros; }
    public function setEvalPros(?string $v): self { $this->evalPros = $v; return $this; }

    public function getEvalCons(): ?string { return $this->evalCons; }
    public function setEvalCons(?string $v): self { $this->evalCons = $v; return $this; }

    public function getEvalWhynot(): ?string { return $this->evalWhynot; }
    public function setEvalWhynot(?string $v): self { $this->evalWhynot = $v; return $this; }

    public function getEvalComments(): ?string { return $this->evalComments; }
    public function setEvalComments(?string $v): self { $this->evalComments = $v; return $this; }
}
