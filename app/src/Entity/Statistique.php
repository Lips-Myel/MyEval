<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StatistiqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatistiqueRepository::class)]
#[ApiResource]
class Statistique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?array $trend = null;

    #[ORM\ManyToOne(inversedBy: 'statistiques')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $student = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    private ?string $averageScore = null;

    #[ORM\Column(nullable: true)]
    private ?float $standardDeviation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    private ?string $teacherScore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrend(): ?array
    {
        return $this->trend;
    }

    public function setTrend(?array $trend): static
    {
        $this->trend = $trend;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $studentId): static
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getAverageScore(): ?string
    {
        return $this->averageScore;
    }

    public function setAverageScore(string $averageScore): static
    {
        $this->averageScore = $averageScore;

        return $this;
    }

    public function getStandardDeviation(): ?float
    {
        return $this->standardDeviation;
    }

    public function setStandardDeviation(?float $standardDeviation): static
    {
        $this->standardDeviation = $standardDeviation;

        return $this;
    }

    public function getTeacherScore(): ?string
    {
        return $this->teacherScore;
    }

    public function setTeacherScore(string $teacherScore): static
    {
        $this->teacherScore = $teacherScore;

        return $this;
    }
}
