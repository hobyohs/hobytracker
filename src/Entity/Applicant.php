<?php

namespace App\Entity;

use App\Repository\ApplicantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicantRepository::class)]
class Applicant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prefName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pronouns = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cellPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $homePhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alumniInfo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $highSchool = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoLink = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $reviewer1Rating = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $reviewer2Rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reviewer1Notes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reviewer2Notes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q1 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q3 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q4 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q5 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q6 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q7 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $q8 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $decision = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eval_evaluator = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $eval_recommendation = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $eval_engaged = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $eval_service = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_pros = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_cons = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eval_comments = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seminarYear = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    
    public function getFirstAndPrefName(): ?string
    {
        // If there's a preferred name on record, use that. If not, use first name.        
        if (empty($this->prefName)) return $this->firstName;
        else return $this->prefName." (".$this->firstName.")";
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }
    
    public function getPositionClass(): ?string
    {
        if ($this->age < 21) {
            return "Junior (<21)";
        } elseif ($this->age >= 21) {
            return "Senior (21+)";
        } else {
            return "Age Unknown";
        }
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getPrefName(): ?string
    {
        return $this->prefName;
    }

    public function setPrefName(?string $prefName): static
    {
        $this->prefName = $prefName;

        return $this;
    }

    public function getPronouns(): ?string
    {
        return $this->pronouns;
    }

    public function setPronouns(?string $pronouns): static
    {
        $this->pronouns = $pronouns;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCellPhone(): ?string
    {
        return $this->cellPhone;
    }

    public function setCellPhone(?string $cellPhone): static
    {
        $this->cellPhone = $cellPhone;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->homePhone;
    }

    public function setHomePhone(?string $homePhone): static
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    public function getAlumniInfo(): ?string
    {
        return $this->alumniInfo;
    }

    public function setAlumniInfo(?string $alumniInfo): static
    {
        $this->alumniInfo = $alumniInfo;

        return $this;
    }

    public function getHighSchool(): ?string
    {
        return $this->highSchool;
    }

    public function setHighSchool(?string $highSchool): static
    {
        $this->highSchool = $highSchool;

        return $this;
    }
    
    public function getVideoSource(): ?string
    {
        if(str_contains($this->videoLink, "drive.google.com")) {
            return "Google Drive";
        } elseif (str_contains($this->videoLink, "youtube.com") or str_contains($this->videoLink, "youtu.be")) {
            return "YouTube";
        } else {
            return "Unknown";
        }
        
    }

    public function getVideoLink(): ?string
    {
        if ($this->getVideoSource() == "Google Drive") {
            preg_match('~/d/\K[^/]+(?=/)~', $this->videoLink, $result);
            return "https://drive.google.com/file/d/".$result[0]."/preview";
        } elseif ($this->getVideoSource() == "YouTube") {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->videoLink, $result);
            return $result[1];
        } else {
            return $this->videoLink;
        }
    }
    
    public function getRawVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): static
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    public function getReviewer1Rating(): ?int
    {
        return $this->reviewer1Rating;
    }

    public function setReviewer1Rating(?int $reviewer1Rating): static
    {
        $this->reviewer1Rating = $reviewer1Rating;

        return $this;
    }

    public function getReviewer2Rating(): ?int
    {
        return $this->reviewer2Rating;
    }

    public function setReviewer2Rating(?int $reviewer2Rating): static
    {
        $this->reviewer2Rating = $reviewer2Rating;

        return $this;
    }

    public function getReviewer1Notes(): ?string
    {
        return $this->reviewer1Notes;
    }

    public function setReviewer1Notes(?string $reviewer1Notes): static
    {
        $this->reviewer1Notes = $reviewer1Notes;

        return $this;
    }

    public function getReviewer2Notes(): ?string
    {
        return $this->reviewer2Notes;
    }

    public function setReviewer2Notes(?string $reviewer2Notes): static
    {
        $this->reviewer2Notes = $reviewer2Notes;

        return $this;
    }

    public function getQ1(): ?string
    {
        return $this->q1;
    }

    public function setQ1(?string $q1): static
    {
        $this->q1 = $q1;

        return $this;
    }

    public function getQ2(): ?string
    {
        return $this->q2;
    }

    public function setQ2(?string $q2): static
    {
        $this->q2 = $q2;

        return $this;
    }

    public function getQ3(): ?string
    {
        return $this->q3;
    }

    public function setQ3(?string $q3): static
    {
        $this->q3 = $q3;

        return $this;
    }

    public function getQ4(): ?string
    {
        return $this->q4;
    }

    public function setQ4(?string $q4): static
    {
        $this->q4 = $q4;

        return $this;
    }

    public function getQ5(): ?string
    {
        return $this->q5;
    }

    public function setQ5(?string $q5): static
    {
        $this->q5 = $q5;

        return $this;
    }

    public function getQ6(): ?string
    {
        return $this->q6;
    }

    public function setQ6(?string $q6): static
    {
        $this->q6 = $q6;

        return $this;
    }

    public function getQ7(): ?string
    {
        return $this->q7;
    }

    public function setQ7(?string $q7): static
    {
        $this->q7 = $q7;

        return $this;
    }

    public function getQ8(): ?string
    {
        return $this->q8;
    }

    public function setQ8(?string $q8): static
    {
        $this->q8 = $q8;

        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(?string $decision): static
    {
        if($decision == "" or empty($decision)) {
            $this->decision = NULL;
        } else {
            $this->decision = $decision;
        }

        return $this;
    }
    
    public function getEvalEvaluator(): ?string
    {
        return $this->eval_evaluator;
    }
    
    public function setEvalEvaluator(?string $eval_evaluator): static
    {
        $this->eval_evaluator = $eval_evaluator;
    
        return $this;
    }

    public function getEvalRecommendation(): ?int
    {
        return $this->eval_recommendation;
    }

    public function setEvalRecommendation(?int $eval_recommendation): static
    {
        $this->eval_recommendation = $eval_recommendation;

        return $this;
    }

    public function getEvalEngaged(): ?int
    {
        return $this->eval_engaged;
    }

    public function setEvalEngaged(?int $eval_engaged): static
    {
        $this->eval_engaged = $eval_engaged;

        return $this;
    }

    public function getEvalService(): ?int
    {
        return $this->eval_service;
    }

    public function setEvalService(?int $eval_service): static
    {
        $this->eval_service = $eval_service;

        return $this;
    }

    public function getEvalPros(): ?string
    {
        return $this->eval_pros;
    }

    public function setEvalPros(?string $eval_pros): static
    {
        $this->eval_pros = $eval_pros;

        return $this;
    }

    public function getEvalCons(): ?string
    {
        return $this->eval_cons;
    }

    public function setEvalCons(?string $eval_cons): static
    {
        $this->eval_cons = $eval_cons;

        return $this;
    }

    public function getEvalComments(): ?string
    {
        return $this->eval_comments;
    }

    public function setEvalComments(?string $eval_comments): static
    {
        $this->eval_comments = $eval_comments;

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
