<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\TypeChoice;
use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $questionText = null;

    #[ORM\Column(enumType: TypeChoice::class)]
    private ?TypeChoice $questionTyÃpe = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createBy = null;

    #[ORM\OneToOne(mappedBy: 'questionId', cascade: ['persist', 'remove'])]
    private ?Response $response = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(string $questionText): static
    {
        $this->questionText = $questionText;

        return $this;
    }

    public function getQuestionTyÃpe(): ?TypeChoice
    {
        return $this->questionTyÃpe;
    }

    public function setQuestionTyÃpe(TypeChoice $questionTyÃpe): static
    {
        $this->questionTyÃpe = $questionTyÃpe;

        return $this;
    }

    public function getCreateBy(): ?User
    {
        return $this->createBy;
    }

    public function setCreateBy(?User $createBy): static
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): static
    {
        // unset the owning side of the relation if necessary
        if ($response === null && $this->response !== null) {
            $this->response->setQuestionId(null);
        }

        // set the owning side of the relation if necessary
        if ($response !== null && $response->getQuestionId() !== $this) {
            $response->setQuestionId($this);
        }

        $this->response = $response;

        return $this;
    }
}
