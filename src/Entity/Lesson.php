<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Klass::class)]
    private Collection $klasses;

    public function __construct()
    {
        $this->klasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Klass>
     */
    public function getKlasses(): Collection
    {
        return $this->klasses;
    }

    public function addKlass(Klass $klass): static
    {
        if (!$this->klasses->contains($klass)) {
            $this->klasses->add($klass);
            $klass->setLesson($this);
        }

        return $this;
    }

    public function removeKlass(Klass $klass): static
    {
        if ($this->klasses->removeElement($klass)) {
            // set the owning side to null (unless already changed)
            if ($klass->getLesson() === $this) {
                $klass->setLesson(null);
            }
        }

        return $this;
    }
}
