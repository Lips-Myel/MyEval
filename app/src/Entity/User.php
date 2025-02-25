<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastConnection = null;

    #[ORM\ManyToOne(inversedBy: 'role')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $hasRole = null;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'teacherId')]
    private Collection $studentEvaluation;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'studentId')]
    private Collection $autoEvaluation;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'createBy')]
    private Collection $questions;

    /**
     * @var Collection<int, Statistique>
     */
    #[ORM\OneToMany(targetEntity: Statistique::class, mappedBy: 'studentId', orphanRemoval: true)]
    private Collection $statistiques;

    public function __construct()
    {
        $this->studentEvaluation = new ArrayCollection();
        $this->autoEvaluation = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->statistiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->lastConnection;
    }

    public function setLastConnection(?\DateTimeInterface $lastConnection): static
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    public function getHasRole(): ?Role
    {
        return $this->hasRole;
    }

    public function setHasRole(?Role $hasRole): static
    {
        $this->hasRole = $hasRole;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getStudentEvaluation(): Collection
    {
        return $this->studentEvaluation;
    }

    public function addStudentEvaluation(Evaluation $studentEvaluation): static
    {
        if (!$this->studentEvaluation->contains($studentEvaluation)) {
            $this->studentEvaluation->add($studentEvaluation);
            $studentEvaluation->setTeacherId($this);
        }

        return $this;
    }

    public function removeStudentEvaluation(Evaluation $studentEvaluation): static
    {
        if ($this->studentEvaluation->removeElement($studentEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($studentEvaluation->getTeacherId() === $this) {
                $studentEvaluation->setTeacherId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getAutoEvaluation(): Collection
    {
        return $this->autoEvaluation;
    }

    public function addAutoEvaluation(Evaluation $autoEvaluation): static
    {
        if (!$this->autoEvaluation->contains($autoEvaluation)) {
            $this->autoEvaluation->add($autoEvaluation);
            $autoEvaluation->setStudentId($this);
        }

        return $this;
    }

    public function removeAutoEvaluation(Evaluation $autoEvaluation): static
    {
        if ($this->autoEvaluation->removeElement($autoEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($autoEvaluation->getStudentId() === $this) {
                $autoEvaluation->setStudentId(null);
            }
        }

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
            $question->setCreateBy($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCreateBy() === $this) {
                $question->setCreateBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Statistique>
     */
    public function getStatistiques(): Collection
    {
        return $this->statistiques;
    }

    public function addStatistique(Statistique $statistique): static
    {
        if (!$this->statistiques->contains($statistique)) {
            $this->statistiques->add($statistique);
            $statistique->setStudentId($this);
        }

        return $this;
    }

    public function removeStatistique(Statistique $statistique): static
    {
        if ($this->statistiques->removeElement($statistique)) {
            // set the owning side to null (unless already changed)
            if ($statistique->getStudentId() === $this) {
                $statistique->setStudentId(null);
            }
        }

        return $this;
    }
}
