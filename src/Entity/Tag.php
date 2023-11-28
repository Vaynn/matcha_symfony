<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[UniqueEntity('name')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:1, max:50)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tags')]
    private Collection $users_id;

    #[ORM\ManyToMany(targetEntity: Preferences::class, mappedBy: 'tags')]
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

    public function __toString(){
        return $this->getName();
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
            $usersId->addTag($this);
        }

        return $this;
    }

    public function removeUsersId(User $usersId): static
    {
        if ($this->users_id->removeElement($usersId)) {
            $usersId->removeTag($this);
        }

        return $this;
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
            $preferencesId->addTag($this);
        }

        return $this;
    }

    public function removePreferencesId(Preferences $preferencesId): static
    {
        if ($this->preferences_id->removeElement($preferencesId)) {
            $preferencesId->removeTag($this);
        }

        return $this;
    }
}
