<?php

namespace App\Entity\Translation;

use App\Entity\Person;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "person_translation")]
#[ORM\Index(name: "person_translation_idx", columns: ["person_id", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class PersonTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "person_id", nullable: false)]
    protected $object;

    public function getSource(): ?Person
    {
        return $this->object;
    }

    public function setSource(?Person $object): self
    {
        $this->object = $object;

        return $this;
    }
}
