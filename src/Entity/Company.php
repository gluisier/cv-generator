<?php

namespace App\Entity;

use App\Entity\Embeddable\Address;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column()]
    private ?string $id;

    #[ORM\Column()]
    private string $name;

    #[ORM\Column(nullable: true)]
    private ?string $link;

    #[ORM\Embedded(class: Address::class, columnPrefix: false)]
    private $address;

    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: "companies")]
    private $persons;

    public function __construct()
    {
        $this->person = new ArrayCollection();
        $this->address = new Address();
        $this->persons = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons->add($person);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        $this->persons->removeElement($person);

        return $this;
    }
}
