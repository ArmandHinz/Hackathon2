<?php

namespace App\Entity;

use App\Repository\TechnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TechnoRepository::class)
 */
class Techno
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
     * @ORM\OneToMany(targetEntity=Chanel::class, mappedBy="techno")
     */
    private $chanels;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="techno")
     */
    private $users;

    public function __construct()
    {
        $this->chanels = new ArrayCollection();
        $this->user = new ArrayCollection();
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
            $chanel->setTechno($this);
        }

        return $this;
    }

    public function removeChanel(Chanel $chanel): self
    {
        if ($this->chanels->removeElement($chanel)) {
            // set the owning side to null (unless already changed)
            if ($chanel->getTechno() === $this) {
                $chanel->setTechno(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setTechno($this);
        }

        return $this;
    }


    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {

            // set the owning side to null (unless already changed)
            if ($user->getTechno() === $this) {
                $user->setTechno(null);
            }
        }

        return $this;
    }
}
