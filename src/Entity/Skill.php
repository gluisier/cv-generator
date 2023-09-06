<?php

namespace App\Entity;

use App\Entity\Translation\SkillTranslation;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[Gedmo\Tree(type: "nested")]
#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[Gedmo\TranslationEntity(class: SkillTranslation::class)]
class Skill
{    
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column]
    #[Gedmo\Translatable]
    private string $name;

    #[ORM\Column(type: "boolean")]
    private bool $selectable;

    #[Gedmo\TreeLeft]
    #[ORM\Column(type: "integer", name: "lft")]
    private int $lft;

    #[Gedmo\TreeRight]
    #[ORM\Column(type: "integer", name: "rgt")]
    private int $rgt;

    #[Gedmo\TreeLevel]
    #[ORM\Column(type: "integer", name: "lvl")]
    private int $lvl;

    #[Gedmo\TreeRoot]
    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(name: "root_id", referencedColumnName: "name", onDelete: "CASCADE")]
    private Skill $root;

    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: "children")]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "name", onDelete: "CASCADE")]
    private Skill $parent;

    #[ORM\OneToMany(targetEntity: Skill::class, mappedBy: "parent")]
    private $children;

    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: "skills")]
    private $people;

    #[ORM\OneToMany(targetEntity: Technology::class, mappedBy: "skill", orphanRemoval: true)]
    private $technologies;

    #[ORM\OneToMany(
       targetEntity: SkillTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    #[ORM\JoinColumn(name: "skill_name")]
    private $translations;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->people = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->translations = new ArrayCollection();
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

    public function getSelectable(): ?string
    {
        return $this->selectable;
    }

    public function setSelectable(bool $selectable): self
    {
        $this->selectable = $selectable;

        return $this;
    }

    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }

    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setRoot(?self $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Skill $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Skill $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people->add($person);
            $person->addSkill($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->removeElement($person)) {
            $person->removeSkill($this);
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
            $technology->setSkill($this);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        if ($this->technologies->removeElement($technology)) {
            // set the owning side to null (unless already changed)
            if ($technology->getSkill() === $this) {
                $technology->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SkillTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(SkillTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(SkillTranslation $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }

        return $this;
    }

    public function isSelectable(): ?bool
    {
        return $this->selectable;
    }
}
