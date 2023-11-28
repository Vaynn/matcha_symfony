<?php

namespace App\Entity;

use App\Repository\SexualityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SexualityRepository::class)]
#[UniqueEntity('name')]
class Sexuality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:1, max:100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'sexuality', targetEntity: User::class)]
    private Collection $users_id;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(max: 300)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Preferences::class, mappedBy: 'sexualities')]
    private Collection $preferences_id;

    public function __construct()
    {
        $this->users_id = new ArrayCollection();
        $this->preferences_id = new ArrayCollection();
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

    /**
     * @return Collection<int, Preferences>
     */
    public function getPreferencesId(): Collection
    {
        return $this->preferences_id;
    }

    public function addPreferencesId(Preferences $preferencesId): static
    {
        if (!$this->preferences_id->contains($preferencesId)) {
            $this->preferences_id->add($preferencesId);
            $preferencesId->addSexuality($this);
        }

        return $this;
    }

    public function removePreferencesId(Preferences $preferencesId): static
    {
        if ($this->preferences_id->removeElement($preferencesId)) {
            $preferencesId->removeSexuality($this);
        }

        return $this;
    }
}
