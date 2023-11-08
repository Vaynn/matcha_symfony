<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $likedBy = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $isLiked = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $likedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    public function setLikedBy(?User $likedBy): static
    {
        $this->likedBy = $likedBy;

        return $this;
    }

    public function getIsLiked(): ?User
    {
        return $this->isLiked;
    }

    public function setIsLiked(?User $isLiked): static
    {
        $this->isLiked = $isLiked;

        return $this;
    }

    public function getLikedAt(): ?\DateTimeImmutable
    {
        return $this->likedAt;
    }

    public function setLikedAt(\DateTimeImmutable $likedAt): static
    {
        $this->likeAt = $likedAt;

        return $this;
    }
}
