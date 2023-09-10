<?php

namespace App\Entity;

use App\Entity\Translation\TaskTranslation;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[Gedmo\TranslationEntity(class: TaskTranslation::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", hardDelete: false)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: "text")]
    #[Gedmo\Translatable]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Realisation::class, inversedBy: "tasks")]
    #[ORM\JoinColumn(nullable: false)]
    private Realisation $realisation;

    #[ORM\OneToMany(
       targetEntity: TaskTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    #[ORM\Column(type: "datetime", nullable: true)]
    private \DateTimeInterface $deletedAt;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRealisation(): ?Realisation
    {
        return $this->realisation;
    }

    public function setRealisation(?Realisation $realisation): self
    {
        $this->realisation = $realisation;

        return $this;
    }

    /**
     * @return Collection<int, TaskTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(TaskTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(TaskTranslation $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }

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
}
