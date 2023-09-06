<?php
namespace App\Entity\Translation;

use App\Entity\Skill;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "skill_translation")]
#[ORM\Index(name: "skill_translation_idx", columns: ["skill_name", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class SkillTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "skill_name", referencedColumnName: "name", nullable: false)]
    protected $object;

    public function getSource(): ?Skill
    {
        return $this->object;
    }

    public function setSource(?Skill $object): self
    {
        $this->object = $object;

        return $this;
    }
}
