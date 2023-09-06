<?php

namespace App\Entity\Translation;

use App\Entity\Abbreviation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "abbreviation_translation")]
#[ORM\Index(name: "abbreviation_translation_idx", columns: ["abbreviation_short", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class AbbreviationTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Abbreviation::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "abbreviation_short", referencedColumnName: "short", nullable: false)]
    protected $object;

    public function getSource(): ?Abbreviation
    {
        return $this->object;
    }

    public function setSource(?Abbreviation $object): self
    {
        $this->object = $object;

        return $this;
    }
}
