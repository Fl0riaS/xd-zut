<?php

namespace App\Entity;

use App\Repository\RaportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaportRepository::class)]
class Raport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalScore = null;

    #[ORM\Column(nullable: true)]
    private ?int $monthScore = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $generateIn = null;

    #[ORM\ManyToOne(inversedBy: 'raports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\OneToMany(mappedBy: 'raport', targetEntity: Opinion::class, orphanRemoval: true)]
    private Collection $opinions;

    public function __construct()
    {
        $this->opinions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTotalScore(): ?int
    {
        return $this->totalScore;
    }

    public function setTotalScore(?int $totalScore): static
    {
        $this->totalScore = $totalScore;

        return $this;
    }

    public function getMonthScore(): ?int
    {
        return $this->monthScore;
    }

    public function setMonthScore(?int $monthScore): static
    {
        $this->monthScore = $monthScore;

        return $this;
    }

    public function getGenerateIn(): ?\DateTimeInterface
    {
        return $this->generateIn;
    }

    public function setGenerateIn(\DateTimeInterface $generateIn): static
    {
        $this->generateIn = $generateIn;

        return $this;
    }

    public function getCourse(): ?course
    {
        return $this->course;
    }

    public function setCourse(?course $course): static
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): static
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions->add($opinion);
            $opinion->setRaport($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): static
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getRaport() === $this) {
                $opinion->setRaport(null);
            }
        }

        return $this;
    }
}
