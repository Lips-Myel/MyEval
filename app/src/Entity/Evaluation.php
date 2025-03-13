<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['evaluation:read']],
    denormalizationContext: ['groups' => ['evaluation:write']]
)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['evaluation:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?string $finalScore = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'studentEvaluation')]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?User $teacher = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'autoEvaluation')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?User $student = null;

    #[ORM\OneToMany(targetEntity: Responses::class, mappedBy: 'evaluation', orphanRemoval: true)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private Collection $responses;

    #[ORM\Column(length: 255)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private ?string $title = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'evaluation')]
    #[Groups(['evaluation:read', 'evaluation:write'])]
    private Collection $questions;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
        $this->questions = new ArrayCollection();
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

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): static
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return Collection<int, Responses>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Responses $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setEvaluation($this);
        }

        return $this;
    }

    public function removeResponse(Responses $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getEvaluation() === $this) {
                $response->setEvaluation(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setEvaluation($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getEvaluation() === $this) {
                $question->setEvaluation(null);
            }
        }

        return $this;
    }

}
