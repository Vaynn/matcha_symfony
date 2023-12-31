<?php

namespace App\Entity;

use App\Repository\GenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GenderRepository::class)]
#[UniqueEntity('name')]
class Gender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:1, max:100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'gender', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'gender', targetEntity: User::class)]
    private Collection $users_id;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(max: 300)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Preferences::class, mappedBy: 'genders')]
    private Collection $preferences_id;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGender($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGender() === $this) {
                $user->setGender(null);
            }
        }

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
            $usersId->setGender($this);
        }

        return $this;
    }

    public function removeUsersId(User $usersId): static
    {
        if ($this->users_id->removeElement($usersId)) {
            // set the owning side to null (unless already changed)
            if ($usersId->getGender() === $this) {
                $usersId->setGender(null);
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
            $preferencesId->addGender($this);
        }

        return $this;
    }

    public function removePreferencesId(Preferences $preferencesId): static
    {
        if ($this->preferences_id->removeElement($preferencesId)) {
            $preferencesId->removeGender($this);
        }

        return $this;
    }
}
