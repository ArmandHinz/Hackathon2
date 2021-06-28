<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Projet::class, mappedBy="user")
     */
    private $projets;

    /**
     * @ORM\OneToOne(targetEntity=Avatar::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Techno::class, mappedBy="user")
     */
    private $technos;

    /**
     * @ORM\OneToMany(targetEntity=Sujet::class, mappedBy="user")
     */
    private $sujets;

    /**
     * @ORM\OneToMany(targetEntity=MessageSujet::class, mappedBy="user")
     */
    private $messageSujets;

    /**
     * @ORM\OneToMany(targetEntity=MessageProjet::class, mappedBy="user")
     */
    private $messageProjets;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
        $this->technos = new ArrayCollection();
        $this->sujets = new ArrayCollection();
        $this->messageSujets = new ArrayCollection();
        $this->messageProjets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->setUser($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getUser() === $this) {
                $projet->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): self
    {
        // unset the owning side of the relation if necessary
        if ($avatar === null && $this->avatar !== null) {
            $this->avatar->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($avatar !== null && $avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Techno[]
     */
    public function getTechnos(): Collection
    {
        return $this->technos;
    }

    public function addTechno(Techno $techno): self
    {
        if (!$this->technos->contains($techno)) {
            $this->technos[] = $techno;
            $techno->setUser($this);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): self
    {
        if ($this->technos->removeElement($techno)) {
            // set the owning side to null (unless already changed)
            if ($techno->getUser() === $this) {
                $techno->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sujet[]
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): self
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets[] = $sujet;
            $sujet->setUser($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getUser() === $this) {
                $sujet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MessageSujet[]
     */
    public function getMessageSujets(): Collection
    {
        return $this->messageSujets;
    }

    public function addMessageSujet(MessageSujet $messageSujet): self
    {
        if (!$this->messageSujets->contains($messageSujet)) {
            $this->messageSujets[] = $messageSujet;
            $messageSujet->setUser($this);
        }

        return $this;
    }

    public function removeMessageSujet(MessageSujet $messageSujet): self
    {
        if ($this->messageSujets->removeElement($messageSujet)) {
            // set the owning side to null (unless already changed)
            if ($messageSujet->getUser() === $this) {
                $messageSujet->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MessageProjet[]
     */
    public function getMessageProjets(): Collection
    {
        return $this->messageProjets;
    }

    public function addMessageProjet(MessageProjet $messageProjet): self
    {
        if (!$this->messageProjets->contains($messageProjet)) {
            $this->messageProjets[] = $messageProjet;
            $messageProjet->setUser($this);
        }

        return $this;
    }

    public function removeMessageProjet(MessageProjet $messageProjet): self
    {
        if ($this->messageProjets->removeElement($messageProjet)) {
            // set the owning side to null (unless already changed)
            if ($messageProjet->getUser() === $this) {
                $messageProjet->setUser(null);
            }
        }

        return $this;
    }
}
