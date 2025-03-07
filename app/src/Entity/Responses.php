<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\AnswerValue;
use App\Repository\ResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
#[ApiResource]
#[ORM\Table(name: "responses", uniqueConstraints: [
    new ORM\UniqueConstraint(name: "unique_evaluation_question", columns: ["evaluation_id", "question_id"])
], indexes: [
    new ORM\Index(name: "idx_evaluation", columns: ["evaluation_id"]),
    new ORM\Index(name: "idx_question", columns: ["question_id"])
])]
class Responses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Evaluation $evaluation = null;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Question $question = null;

    #[ORM\Column(type: Types::JSON)]
    private array $answerValue = [];

    #[ORM\Column]
    private ?bool $isCorrect = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): static
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return AnswerValue[]
     */
    public function getAnswerValue(): array
    {
        return $this->answerValue;
    }

    public function setAnswerValue(array $answerValue): static
    {
        $this->answerValue = $answerValue;
        return $this;
    }

    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }
}
