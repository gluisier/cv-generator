<?php

namespace App\Entity;

use App\Entity\Embeddable\Address;
use App\Entity\Translation\PersonTranslation;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[Gedmo\TranslationEntity(class: PersonTranslation::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", hardDelete: false)]
class Person
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column(length: 3)]
    private ?string $id;

    #[ORM\Column]
    private string $fullName;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Translatable]
    private ?string $position;

    #[ORM\Column(type: "date", nullable: true)]
    private \DateTimeInterface $birthDate;

    #[ORM\Embedded(class: Address::class, columnPrefix: false)]
    private $address;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Translatable]
    private ?string $nationality = null;
    
    #[ORM\Column(nullable: true)]
    #[Gedmo\Translatable]
    private ?string $maritalStatus = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Gedmo\Translatable]
    private ?string $summary = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "referrals")]
    private Person $person;

    #[ORM\OneToMany(targetEntity: Person::class, mappedBy: "person", cascade: ["persist"])]
    #[ORM\OrderBy(["birthDate" => "DESC"])]
    private $referrals;

    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: "trainee", cascade: ["persist"])]
    #[ORM\OrderBy(["startDate" => "DESC"])]
    private $trainings;

    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: "employee", cascade: ["persist"])]
    #[ORM\OrderBy(["startDate" => "DESC"])]
    private $experiences;

    #[ORM\OneToMany(targetEntity: Hobby::class, mappedBy: "person", cascade: ["persist"])]
    private $hobbies;

    #[ORM\OneToMany(
       targetEntity: PersonTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: "persons")]
    private $companies;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: "people", cascade: ["persist"])]
    #[ORM\InverseJoinColumn(referencedColumnName: "name")]
    #[ORM\OrderBy(["lft" => "ASC"])]
    private $skills;

    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: "people", cascade: ["persist"])]
    #[ORM\InverseJoinColumn(referencedColumnName: "code")]
    #[ORM\OrderBy(["code" => "DESC"])]
    private $languages;

    #[ORM\Column(type: "datetime", nullable: true)]
    private \DateTimeInterface $deletedAt;

    public function __construct()
    {
        $this->address = new Address();
        $this->referrals = new ArrayCollection();
        $this->trainings = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->hobbies = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->languages = new ArrayCollection();
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

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(?string $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPerson(): ?self
    {
        return $this->person;
    }

    public function setPerson(?self $person): self
    {
        $this->person = $person;

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
     * @return Collection<int, Person>
     */
    public function getReferrals(): Collection
    {
        return $this->referrals;
    }

    public function addReferral(Person $referral): self
    {
        if (!$this->referrals->contains($referral)) {
            $this->referrals->add($referral);
            $referral->setPerson($this);
        }

        return $this;
    }

    public function removeReferral(Person $referral): self
    {
        if ($this->referrals->removeElement($referral)) {
            // set the owning side to null (unless already changed)
            if ($referral->getPerson() === $this) {
                $referral->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Experience $training): self
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->setTrainee($this);
        }

        return $this;
    }

    public function removeTraining(Experience $training): self
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getTrainee() === $this) {
                $training->setTrainee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setEmployee($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getEmployee() === $this) {
                $experience->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobby $hobby): self
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
            $hobby->setPerson($this);
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): self
    {
        if ($this->hobbies->removeElement($hobby)) {
            // set the owning side to null (unless already changed)
            if ($hobby->getPerson() === $this) {
                $hobby->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PersonTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(PersonTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(PersonTranslation $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->addPerson($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            $company->removePerson($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->languages->removeElement($language);

        return $this;
    }

    public function getAge(): int
    {
        return $this->birthDate->diff(new \DateTime)->y;
    }
}
