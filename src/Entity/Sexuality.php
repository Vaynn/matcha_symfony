<?php

namespace App\Entity;

use App\Repository\SexualityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SexualityRepository::class)]
class Sexuality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'sexuality', targetEntity: User::class)]
    private Collection $users_id;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->users_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersId(): Collection
    {
        return $this->users_id;
    }

    public function addUsersId(User $usersId): static
    {
        if (!$this->users_id->contains($usersId)) {
            $this->users_id->add($usersId);
            $usersId->setSexuality($this);
        }

        return $this;
    }

    public function removeUsersId(User $usersId): static
    {
        if ($this->users_id->removeElement($usersId)) {
            // set the owning side to null (unless already changed)
            if ($usersId->getSexuality() === $this) {
                $usersId->setSexuality(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }
}
