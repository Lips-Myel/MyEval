<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use App\Controller\UserCreateController;
use App\Controller\UserUpdateController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/users',
            name: 'app_get_users',
            normalizationContext: ['groups' => ['user:read']],
            extraProperties: [
                'openapi' => [
                    'summary' => 'Récupérer tous les utilisateurs',
                    'description' => 'Retourne la liste complète des utilisateurs',
                ]
            ]
        ),
        new Get(
            uriTemplate: '/users/{id}',
            name: 'app_get_user_by_id',
            normalizationContext: ['groups' => ['user:read']],
            extraProperties: [
                'openapi' => [
                    'summary' => 'Récupérer un utilisateur par ID',
                    'description' => 'Retourne un utilisateur spécifique basé sur son ID',
                ]
            ]
        ),
        new Patch(
            uriTemplate: '/users/{id}',
            controller: UserUpdateController::class . '::updateUser',
            denormalizationContext: ['groups' => ['user:write']],
            extraProperties: [
                'openapi' => [
                    'summary' => 'Mettre à jour un utilisateur',
                    'description' => 'Modifie les informations d’un utilisateur, y compris son mot de passe',
                ]
            ]
        ),
        new Delete(
            uriTemplate: '/users/{id}',
            name: 'app_delete_user',
            extraProperties: [
                'openapi' => [
                    'summary' => 'Supprimer un utilisateur',
                    'description' => 'Supprime un utilisateur de la base de données',
                ]
            ]
        ),
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['user:read'])]
    private ?\DateTimeInterface $lastConnection = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: "role_id", referencedColumnName: "id", nullable: false)]
    #[Groups(['user:read', 'user:write'])]
    private ?Role $role = null;

    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'teacher')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $studentEvaluation;

    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'student')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $autoEvaluation;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'createBy')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $questions;

    #[ORM\OneToMany(targetEntity: Statistique::class, mappedBy: 'student', orphanRemoval: true)]
    #[Groups(['user:read', 'user:write'])]
    private Collection $statistiques;

    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'members')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $formations;

    #[ORM\OneToMany(targetEntity: Responses::class, mappedBy: 'student')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $responses;

    public function __construct()
    {
        $this->studentEvaluation = new ArrayCollection();
        $this->autoEvaluation = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->statistiques = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->role ? [$this->role->getName()] : ['ROLE_USER'];
    }

    public function eraseCredentials(): void {}

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


    public function getRole(): ?Role
    {
        return $this->role;
    }

    

    public function setRole(?Role $role): static
    {
        $this->role = $role;

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
            $studentEvaluation->setTeacher($this);
        }

        return $this;
    }

    public function removeStudentEvaluation(Evaluation $studentEvaluation): static
    {
        if ($this->studentEvaluation->removeElement($studentEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($studentEvaluation->getTeacher() === $this) {
                $studentEvaluation->setTeacher(null);
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
            $autoEvaluation->setStudent($this);
        }

        return $this;
    }

    public function removeAutoEvaluation(Evaluation $autoEvaluation): static
    {
        if ($this->autoEvaluation->removeElement($autoEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($autoEvaluation->getStudent() === $this) {
                $autoEvaluation->setStudent(null);
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
            $statistique->setStudent($this);
        }

        return $this;
    }

    public function removeStatistique(Statistique $statistique): static
    {
        if ($this->statistiques->removeElement($statistique)) {
            // set the owning side to null (unless already changed)
            if ($statistique->getStudent() === $this) {
                $statistique->setStudent(null);
            }
        }

        return $this;
    }

        /**
     * @return Collection<int, Formation>
     */
    
     public function getFormationNames(): array
     {
         return $this->formations->map(fn(Formation $formation) => $formation->getName())->toArray();
     }
     public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addMember($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeMember($this);
        }

        return $this;
    }

    // Ajoute cette méthode pour enrichir le token avec tes données personnalisées
    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'roles' => $this->getRoles(),
        ];
    }

    // Implémentation de la méthode obligatoire (peut rester vide si inutile)
    public static function createFromPayload($username, array $payload)
    {
        // Ici, tu peux récupérer les données du payload pour recréer un utilisateur si nécessaire
        return new self();
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
            $response->setStudent($this);
        }

        return $this;
    }

    public function removeResponse(Responses $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getStudent() === $this) {
                $response->setStudent(null);
            }
        }

        return $this;
    }

}