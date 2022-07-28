<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $event_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $max_limit = null;

    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'event')]
    private Collection $persons;

    #[ORM\Column(length: 255)]
    private ?string $host_email = null;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(\DateTimeInterface $event_date): self
    {
        $this->event_date = $event_date;

        return $this;
    }

    public function getMaxLimit(): ?int
    {
        return $this->max_limit;
    }

    public function setMaxLimit(?int $max_limit): self
    {
        $this->max_limit = $max_limit;

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
            $this->persons[] = $person;
            $person->addEvent($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->persons->removeElement($person)) {
            $person->removeEvent($this);
        }

        return $this;
    }

    public function getHostEmail(): ?string
    {
        return $this->host_email;
    }

    public function setHostEmail(string $host_email): self
    {
        $this->host_email = $host_email;

        return $this;
    }

}
