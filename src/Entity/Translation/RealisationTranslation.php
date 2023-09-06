<?php

namespace App\Entity\Translation;

use App\Entity\Realisation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "realisation_translation")]
#[ORM\Index(name: "realisation_translation_idx", columns: ["realisation_id", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class RealisationTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Realisation::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "realisation_id", nullable: false)]
    protected $object;

    public function getSource(): ?Realisation
    {
        return $this->object;
    }

    public function setSource(?Realisation $object): self
    {
        $this->object = $object;

        return $this;
    }
}
