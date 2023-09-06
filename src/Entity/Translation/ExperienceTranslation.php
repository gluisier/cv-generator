<?php

namespace App\Entity\Translation;

use App\Entity\Experience;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "experience_translation")]
#[ORM\Index(name: "experience_translation_idx", columns: ["experience_id", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class ExperienceTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Experience::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "experience_id", nullable: false)]
    protected $object;

    public function getSource(): ?Experience
    {
        return $this->object;
    }

    public function setSource(?Experience $object): self
    {
        $this->object = $object;

        return $this;
    }
}
