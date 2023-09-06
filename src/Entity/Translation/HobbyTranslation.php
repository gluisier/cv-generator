<?php

namespace App\Entity\Translation;

use App\Entity\Hobby;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "hobby_translation")]
#[ORM\Index(name: "hobby_translation_idx", columns: ["hobby_id", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class HobbyTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Hobby::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "hobby_id", nullable: false)]
    protected $object;

    public function getSource(): ?Hobby
    {
        return $this->object;
    }

    public function setSource(?Hobby $object): self
    {
        $this->object = $object;

        return $this;
    }
}
