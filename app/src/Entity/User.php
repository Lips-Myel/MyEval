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
     * @var Collection<int, Export>
     */
    #[ORM\OneToMany(targetEntity: Export::class, mappedBy: 'userId')]
    private Collection $exports;

    public function __construct()
    {
        $this->exports = new ArrayCollection();
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
     * @return Collection<int, Export>
     */
    public function getExports(): Collection
    {
        return $this->exports;
    }

    public function addExport(Export $export): static
    {
        if (!$this->exports->contains($export)) {
            $this->exports->add($export);
            $export->setUserId($this);
        }

        return $this;
    }

    public function removeExport(Export $export): static
    {
        if ($this->exports->removeElement($export)) {
            // set the owning side to null (unless already changed)
            if ($export->getUserId() === $this) {
                $export->setUserId(null);
            }
        }

        return $this;
    }
}
