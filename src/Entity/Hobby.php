<?php

namespace App\Entity;

use App\Entity\Translation\HobbyTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[Gedmo\TranslationEntity(class: HobbyTranslation::class)]
class Hobby
{
    #[ORM\Id()]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column]
    #[Gedmo\Translatable]
    private string $what;
    
    #[ORM\Column(type: "text")]
    #[Gedmo\Translatable]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "hobbies")]
    private Person $person;

    #[ORM\OneToMany(
       targetEntity: HobbyTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
