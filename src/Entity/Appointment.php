<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?Klass $klass = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?Student $student = null;

    #[ORM\OneToOne(mappedBy: 'appointment', cascade: ['persist', 'remove'])]
    private ?AppointmentFeedback $appointmentFeedback = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlass(): ?Klass
    {
        return $this->klass;
    }

    public function setKlass(?Klass $klass): static
    {
        $this->klass = $klass;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getAppointmentFeedback(): ?AppointmentFeedback
    {
        return $this->appointmentFeedback;
    }

    public function setAppointmentFeedback(AppointmentFeedback $appointmentFeedback): static
    {
        // set the owning side of the relation if necessary
        if ($appointmentFeedback->getAppointment() !== $this) {
            $appointmentFeedback->setAppointment($this);
        }

        $this->appointmentFeedback = $appointmentFeedback;

        return $this;
    }
}
