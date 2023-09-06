<?php

namespace App\Entity;

use App\Entity\Translation\AbbreviationTranslation;
use App\Repository\AbbreviationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AbbreviationRepository::class)]
#[Gedmo\TranslationEntity(class: AbbreviationTranslation::class)]
class Abbreviation implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column]
    private ?string $short;

    #[Gedmo\Translatable]
    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\OneToMany(
       targetEntity: AbbreviationTranslation::class,
       mappedBy: "object",
       cascade: ["persist", "remove"]
    )]
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

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

    /**
     * @return Collection<int, AbbreviationTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(AbbreviationTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(AbbreviationTranslation $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'short' => $this->short,
            'description' => $this->description,
        ];
    }
}
