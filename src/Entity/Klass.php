<?php

namespace App\Entity;

use App\Repository\KlassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KlassRepository::class)]
class Klass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\OneToMany(mappedBy: 'klass', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\ManyToOne(inversedBy: 'klasses')]
    private ?Lesson $lesson = null;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addStudent(Appointment $student): static
    {
        if (!$this->appointments->contains($student)) {
            $this->appointments->add($student);
            $student->setKlass($this);
        }

        return $this;
    }

    public function removeStudent(Appointment $student): static
    {
        if ($this->appointments->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getKlass() === $this) {
                $student->setKlass(null);
            }
        }

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}
