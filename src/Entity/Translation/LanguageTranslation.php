<?php
namespace App\Entity\Translation;

use App\Entity\Language;
use App\Repository\Translation\LanguageTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;

#[ORM\Table(name: "language_translation")]
#[ORM\Index(name: "language_translation_idx", columns: ["language_code", "locale", "field"])]
#[ORM\Entity(repositoryClass: LanguageTranslationRepository::class)]
class LanguageTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "language_code", referencedColumnName: "code", nullable: false)]
    protected $object;

    public function getSource(): ?Language
    {
        return $this->object;
    }

    public function setSource(?Language $object): self
    {
        $this->object = $object;

        return $this;
    }
}
