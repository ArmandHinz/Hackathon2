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

    /**
     * @ORM\OneToMany(targetEntity=Chanel::class, mappedBy="user")
     */
    private $chanels;

    /**
     * @ORM\ManyToOne(targetEntity=Techno::class, inversedBy="user")
     */
    private $technos;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
        $this->sujets = new ArrayCollection();
        $this->messageSujets = new ArrayCollection();
        $this->messageProjets = new ArrayCollection();
        $this->chanels = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getTechnos(): ArrayCollection
    {
        return $this->technos;
    }

    /**
     * @param ArrayCollection $technos
     */
    public function setTechnos(ArrayCollection $technos): void
    {
        $this->technos = $technos;
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

    /**

     * @return Collection|Chanel[]
     */
    public function getChanels(): Collection
    {
        return $this->chanels;
    }

    public function addChanel(Chanel $chanel): self
    {
        if (!$this->chanels->contains($chanel)) {
            $this->chanels[] = $chanel;
            $chanel->setUser($this);
        }

        return $this;
    }

    public function removeChanel(Chanel $chanel): self
    {
        if ($this->chanels->removeElement($chanel)) {
            // set the owning side to null (unless already changed)
            if ($chanel->getUser() === $this) {
                $chanel->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Transform to string
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();

    }
}
