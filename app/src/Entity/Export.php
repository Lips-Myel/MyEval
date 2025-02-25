<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExportRepository::class)]
#[ApiResource]
class Export
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $exportDate = null;

    #[ORM\ManyToOne(inversedBy: 'exports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExportDate(): ?\DateTimeInterface
    {
        return $this->exportDate;
    }

    public function setExportDate(\DateTimeInterface $exportDate): static
    {
        $this->exportDate = $exportDate;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }
}
