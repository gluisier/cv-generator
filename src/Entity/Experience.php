<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use App\Entity\Translation\ExperienceTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[Gedmo\TranslationEntity(class: ExperienceTranslation::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", hardDelete: false)]
class Experience
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: "text")]
    #[Gedmo\Translatable]
    private string $what;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "experiences")]
    private Person $employee;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "trainings")]
    private Person $trainee;

    #[ORM\OneToMany(targetEntity: Realisation::class, mappedBy: "experience", cascade: ["persist"], orphanRemoval: true)]
    #[ORM\OrderBy(["importance" => "DESC"])]
    private $realisations;

    #[ORM\OneToMany(
       targetEntity: ExperienceTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    #[ORM\Column(type: "datetime", nullable: true)]
    private \DateTimeInterface $deletedAt;

    public function __construct()
    {
        $this->realisations = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getWhat(): ?string
    {
        return $this->what;
    }

    public function setWhat(string $what): self
    {
        $this->what = $what;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getEmployee(): ?Person
    {
        return $this->employee;
    }

    public function setEmployee(?Person $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getTrainee(): ?Person
    {
        return $this->trainee;
    }

    public function setTrainee(?Person $trainee): self
    {
        $this->trainee = $trainee;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection<int, Realisation>
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisation $realisation): self
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations->add($realisation);
            $realisation->setExperience($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getExperience() === $this) {
                $realisation->setExperience(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExperienceTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ExperienceTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(ExperienceTranslation $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }

        return $this;
    }
}
