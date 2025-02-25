<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\AnswerValue;
use App\Repository\ResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
#[ApiResource]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evaluation $evaluationId = null;

    #[ORM\OneToOne(inversedBy: 'response', cascade: ['persist', 'remove'])]
    private ?Question $questionId = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: AnswerValue::class)]
    private array $answerValue = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluationId(): ?Evaluation
    {
        return $this->evaluationId;
    }

    public function setEvaluationId(?Evaluation $evaluationId): static
    {
        $this->evaluationId = $evaluationId;

        return $this;
    }

    public function getQuestionId(): ?Question
    {
        return $this->questionId;
    }

    public function setQuestionId(?Question $questionId): static
    {
        $this->questionId = $questionId;

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
}
