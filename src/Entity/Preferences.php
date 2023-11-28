<?php

namespace App\Entity;

use App\Repository\PreferencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PreferencesRepository::class)]
class Preferences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'preferences', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[Assert\Range(min: 18, max: 99)]
    #[ORM\Column(nullable: true)]
    private ?int $min_age = null;

    #[Assert\Range(min: 18, max: 99)]
    #[ORM\Column(nullable: true)]
    private ?int $max_age = null;

    #[ORM\ManyToMany(targetEntity: Sexuality::class, inversedBy: 'preferences_id')]
    private Collection $sexualities;

    #[ORM\ManyToMany(targetEntity: Gender::class, inversedBy: 'preferences_id')]
    private Collection $genders;

    #[ORM\Column(nullable: true)]
    private ?int $distance = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'preferences_id')]
    private Collection $tags;

    public function __construct()
    {
        $this->sexualities = new ArrayCollection();
        $this->genders = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->min_age;
    }

    public function setMinAge(?int $min_age): static
    {
        $this->min_age = $min_age;

        return $this;
    }

    public function getMaxAge(): ?int
    {
        return $this->max_age;
    }

    public function setMaxAge(?int $max_age): static
    {
        $this->max_age = $max_age;

        return $this;
    }

    /**
     * @return Collection<int, Sexuality>
     */
    public function getSexualities(): Collection
    {
        return $this->sexualities;
    }

    public function addSexuality(Sexuality $sexuality): static
    {
        if (!$this->sexualities->contains($sexuality)) {
            $this->sexualities->add($sexuality);
        }

        return $this;
    }

    public function removeSexuality(Sexuality $sexuality): static
    {
        $this->sexualities->removeElement($sexuality);

        return $this;
    }

    /**
     * @return Collection<int, Gender>
     */
    public function getGenders(): Collection
    {
        return $this->genders;
    }

    public function addGender(Gender $gender): static
    {
        if (!$this->genders->contains($gender)) {
            $this->genders->add($gender);
        }

        return $this;
    }

    public function removeGender(Gender $gender): static
    {
        $this->genders->removeElement($gender);

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function __toString(){
        return $this->getUser()->getUsername();
    }
}
