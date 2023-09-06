<?php

namespace App\Entity;

use App\Entity\Translation\LanguageTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[Gedmo\TranslationEntity(class: LanguageTranslation::class)]
class Language
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column(length: 2)]
    private string $code;

    #[ORM\Column]
    #[Gedmo\Translatable]
    private string $level;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Translatable]
    private ?string $meaning;

    #[ORM\OneToMany(
       targetEntity: LanguageTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    #[ORM\ManyToMany(
       targetEntity: Person::class,
       mappedBy: "languages",
       cascade: ["persist", "remove"]
    )]
    private $people;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getMeaning(): ?string
    {
        return $this->meaning;
    }

    public function setMeaning(?string $meaning): self
    {
        $this->meaning = $meaning;

        return $this;
    }
}
