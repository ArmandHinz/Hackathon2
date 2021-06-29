<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjetRepository::class)
 */
class Projet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlDrive;

    /**
     * @ORM\OneToMany(targetEntity=MessageProjet::class, mappedBy="projet")
     */
    private $messageProjet;

    /**
     * @ORM\OneToMany(targetEntity=Chanel::class, mappedBy="projet")
     */
    private $chanel;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projets")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="project")
     */
    private $files;

    public function __construct()
    {
        $this->messageProjet = new ArrayCollection();
        $this->chanel = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getUrlDrive(): ?string
    {
        return $this->urlDrive;
    }

    public function setUrlDrive(?string $urlDrive): self
    {
        $this->urlDrive = $urlDrive;

        return $this;
    }

    /**
     * @return Collection|MessageProjet[]
     */
    public function getMessageProjet(): Collection
    {
        return $this->messageProjet;
    }

    public function addMessageProjet(MessageProjet $messageProjet): self
    {
        if (!$this->messageProjet->contains($messageProjet)) {
            $this->messageProjet[] = $messageProjet;
            $messageProjet->setProjet($this);
        }

        return $this;
    }

    public function removeMessageProjet(MessageProjet $messageProjet): self
    {
        if ($this->messageProjet->removeElement($messageProjet)) {
            // set the owning side to null (unless already changed)
            if ($messageProjet->getProjet() === $this) {
                $messageProjet->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chanel[]
     */
    public function getChanel(): Collection
    {
        return $this->chanel;
    }

    public function addChanel(Chanel $chanel): self
    {
        if (!$this->chanel->contains($chanel)) {
            $this->chanel[] = $chanel;
            $chanel->setProjet($this);
        }

        return $this;
    }

    public function removeChanel(Chanel $chanel): self
    {
        if ($this->chanel->removeElement($chanel)) {
            // set the owning side to null (unless already changed)
            if ($chanel->getProjet() === $this) {
                $chanel->setProjet(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setProject($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProject() === $this) {
                $file->setProject(null);
            }
        }

        return $this;
    }
}
