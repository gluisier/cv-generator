<?php

namespace App\Entity;

use App\Entity\Translation\RealisationTranslation;
use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RealisationRepository::class)]
#[Gedmo\TranslationEntity(class: RealisationTranslation::class)]
class Realisation
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[Gedmo\Translatable]
    private string $name;

    #[ORM\Column(nullable: true)]
    private ?string $link;

    #[ORM\Column(type: "text")]
    #[Gedmo\Translatable]
    private string $description;

    #[ORM\Column]
    #[Gedmo\SortablePosition]
    private int $importance;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: "realisation", cascade: ["persist", "remove"])]
    private $tasks;

    #[ORM\OneToMany(targetEntity: Technology::class, mappedBy: "realisation", cascade: ["persist", "remove"])]
    private $technologies;

    #[ORM\ManyToOne(targetEntity: Experience::class, inversedBy: "realisations")]
    #[ORM\JoinColumn(nullable: false)]
    private Experience $experience;

    #[ORM\OneToMany(
       targetEntity: RealisationTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    #[ORM\Column(type: "datetime", nullable: true)]
    private \DateTimeInterface $deletedAt;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

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
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setRealisation($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getRealisation() === $this) {
                $task->setRealisation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Technology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies->add($technology);
            $technology->setRealisation($this);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        if ($this->technologies->removeElement($technology)) {
            // set the owning side to null (unless already changed)
            if ($technology->getRealisation() === $this) {
                $technology->setRealisation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RealisationTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(RealisationTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(RealisationTranslation $translation): self
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
