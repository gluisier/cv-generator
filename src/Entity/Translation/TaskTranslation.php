<?php

namespace App\Entity\Translation;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "task_translation")]
#[ORM\Index(name: "task_translation_idx", columns: ["task_id", "locale", "field"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class TaskTranslation extends AbstractTranslation
{
    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: "translations")]
    #[ORM\JoinColumn(name: "task_id", nullable: false)]
    protected $object;

    public function getSource(): ?Task
    {
        return $this->object;
    }

    public function setSource(?Task $object): self
    {
        $this->object = $object;

        return $this;
    }
}
