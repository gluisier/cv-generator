<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", hardDelete: false)]
class Technology
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column(nullable: true)]
    private ?string $version;

    #[ORM\Id()]
    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: "technologies")]
    #[ORM\JoinColumn(name: "skill_name", referencedColumnName: "name", nullable: false)]
    private Skill $skill;

    #[ORM\Id()]
    #[ORM\ManyToOne(targetEntity: Realisation::class, inversedBy: "technologies")]
    #[ORM\JoinColumn(nullable: false)]
    private Realisation $realisation;

    #[ORM\Column(type: "datetime", nullable: true)]
    private \DateTimeInterface $deletedAt;

    public function __construct(?Skill $skill = null, ?string $version = null)
    {
        $this->skill = $skill;
        $this->version = $version;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

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
