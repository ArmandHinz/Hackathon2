<?php

namespace App\Entity;

use App\Repository\ChanelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChanelRepository::class)
 */
class Chanel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValidate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budget;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="chanel")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chanels")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Techno::class, inversedBy="chanels")
     */
    private $techno;

    /**
     * @ORM\OneToMany(targetEntity=MessageChanel::class, mappedBy="chanel")
     */
    private $messageChanels;

    public function __construct()
    {
        $this->messageChanels = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsValidate(): ?bool
    {
        return $this->isValidate;
    }

    public function setIsValidate(?bool $isValidate): self
    {
        $this->isValidate = $isValidate;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

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

    public function getTechno(): ?Techno
    {
        return $this->techno;
    }

    public function setTechno(?Techno $techno): self
    {
        $this->techno = $techno;

        return $this;
    }

    /**
     * @return Collection|MessageChanel[]
     */
    public function getMessageChanels(): Collection
    {
        return $this->messageChanels;
    }

    public function addMessageChanel(MessageChanel $messageChanel): self
    {
        if (!$this->messageChanels->contains($messageChanel)) {
            $this->messageChanels[] = $messageChanel;
            $messageChanel->setChanel($this);
        }

        return $this;
    }

    public function removeMessageChanel(MessageChanel $messageChanel): self
    {
        if ($this->messageChanels->removeElement($messageChanel)) {
            // set the owning side to null (unless already changed)
            if ($messageChanel->getChanel() === $this) {
                $messageChanel->setChanel(null);
            }
        }

        return $this;
    }
}
