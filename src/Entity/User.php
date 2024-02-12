<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('username', message: "This username is already used.")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:2, max:180)]
    private ?string $username = null;


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?string $password = "pass";

    private ?string $plainPassword = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:2, max:50)]
    private ?string $lastName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:2, max:50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 180)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private array $roles = [];

    #[ORM\Column]
    private ?bool $isActive = false;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?int $fame_rating = 0;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $gpsPosition = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastConnection = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 300)]
    private ?string $biography = null;

    #[ORM\OneToMany(mappedBy: 'linkingUser', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likedUser;

    #[ORM\OneToMany(mappedBy: 'likedBy', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 18, max: 99)]
    private ?int $age = null;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Image::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $tokenActivation = null;

    #[ORM\ManyToOne(inversedBy: 'users_id')]
    private ?Gender $gender = null;

    #[ORM\ManyToOne(inversedBy: 'users_id')]
    private ?Sexuality $sexuality = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'users_id')]
    private Collection $tags;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Preferences $preferences = null;

    #[ORM\Column]
    private ?bool $haveNewNotif = false;



    public function __construct()
    {
        $this->likedUser = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getFameRating(): ?int
    {
        return $this->fame_rating;
    }

    public function setFameRating(int $fame_rating): static
    {
        $this->fame_rating = $fame_rating;

        return $this;
    }

    public function getGpsPosition(): ?string
    {
        return $this->gpsPosition;
    }

    public function setGpsPosition(?string $gpsPosition): static
    {
        $this->gpsPosition = $gpsPosition;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeImmutable
    {
        return $this->lastConnection;
    }

    public function setLastConnection(?\DateTimeImmutable $lastConnection): static
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }


    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikedUser(): Collection
    {
        return $this->likedUser;
    }

    public function addLikedUser(Like $likedUser): static
    {
        if (!$this->likedUser->contains($likedUser)) {
            $this->likedUser->add($likedUser);
            $likedUser->setLinkingUser($this);
        }

        return $this;
    }

    public function removeLikedUser(Like $likedUser): static
    {
        if ($this->likedUser->removeElement($likedUser)) {
            // set the owning side to null (unless already changed)
            if ($likedUser->getLinkingUser() === $this) {
                $likedUser->setLinkingUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setLikedBy($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getLikedBy() === $this) {
                $like->setLikedBy(null);
            }
        }

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUserId($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getUserId() === $this) {
                $image->setUserId(null);
            }
        }

        return $this;
    }

    public function getTokenActivation(): ?string
    {
        return $this->tokenActivation;
    }

    public function setTokenActivation(?string $tokenActivation): static
    {
        $this->tokenActivation = $tokenActivation;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getSexuality(): ?Sexuality
    {
        return $this->sexuality;
    }

    public function setSexuality(?Sexuality $sexuality): static
    {
        $this->sexuality = $sexuality;

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

    public function getPreferences(): ?Preferences
    {
        return $this->preferences;
    }

    public function setPreferences(?Preferences $preferences): static
    {
        // unset the owning side of the relation if necessary
        if ($preferences === null && $this->preferences !== null) {
            $this->preferences->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($preferences !== null && $preferences->getUser() !== $this) {
            $preferences->setUser($this);
        }

        $this->preferences = $preferences;

        return $this;
    }

    public function getHaveNewNotif(): ?bool
    {
        return $this->haveNewNotif;
    }

    public function setHaveNewNotif(bool $haveNewNotif): static
    {
        $this->haveNewNotif = $haveNewNotif;

        return $this;
    }

}
