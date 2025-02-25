<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
#[ApiResource]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: 1)]
    private ?string $finalScore = null;

    #[ORM\ManyToOne(inversedBy: 'studentEvaluation')]
    private ?User $teacherId = null;

    #[ORM\ManyToOne(inversedBy: 'autoEvaluation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $studentId = null;

    /**
     * @var Collection<int, Response>
     */
    #[ORM\OneToMany(targetEntity: Response::class, mappedBy: 'evaluationId', orphanRemoval: true)]
    private Collection $responses;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getFinalScore(): ?string
    {
        return $this->finalScore;
    }

    public function setFinalScore(string $finalScore): static
    {
        $this->finalScore = $finalScore;

        return $this;
    }

    public function getTeacherId(): ?User
    {
        return $this->teacherId;
    }

    public function setTeacherId(?User $teacherId): static
    {
        $this->teacherId = $teacherId;

        return $this;
    }

    public function getStudentId(): ?User
    {
        return $this->studentId;
    }

    public function setStudentId(?User $studentId): static
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setEvaluationId($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getEvaluationId() === $this) {
                $response->setEvaluationId(null);
            }
        }

        return $this;
    }

}
