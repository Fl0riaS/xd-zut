<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $groupName = null;

    #[ORM\ManyToMany(targetEntity: Teacher::class, inversedBy: 'courses')]
    private Collection $teacher;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subject = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Raport::class, orphanRemoval: true)]
    private Collection $raports;

    public function __construct()
    {
        $this->teacher = new ArrayCollection();
        $this->raports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): static
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * @return Collection<int, Teacher>
     */
    public function getTeacher(): Collection
    {
        return $this->teacher;
    }

    public function addTeacher(Teacher $teacher): static
    {
        if (!$this->teacher->contains($teacher)) {
            $this->teacher->add($teacher);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): static
    {
        $this->teacher->removeElement($teacher);

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection<int, Raport>
     */
    public function getRaports(): Collection
    {
        return $this->raports;
    }

    public function addRaport(Raport $raport): static
    {
        if (!$this->raports->contains($raport)) {
            $this->raports->add($raport);
            $raport->setCourse($this);
        }

        return $this;
    }

    public function removeRaport(Raport $raport): static
    {
        if ($this->raports->removeElement($raport)) {
            // set the owning side to null (unless already changed)
            if ($raport->getCourse() === $this) {
                $raport->setCourse(null);
            }
        }

        return $this;
    }
}
