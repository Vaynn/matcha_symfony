<?php

namespace App\Entity;

use App\Repository\MatchsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchsRepository::class)]
class Matchs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $firstUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $secondUser = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $matchedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstUser(): ?User
    {
        return $this->firstUser;
    }

    public function setFirstUser(?User $firstUser): static
    {
        $this->firstUser = $firstUser;

        return $this;
    }

    public function getSecondUser(): ?User
    {
        return $this->secondUser;
    }

    public function setSecondUser(?User $secondUser): static
    {
        $this->secondUser = $secondUser;

        return $this;
    }

    public function getMatchedAt(): ?\DateTimeImmutable
    {
        return $this->matchedAt;
    }

    public function setMatchedAt(\DateTimeImmutable $matchedAt): static
    {
        $this->matchedAt = $matchedAt;

        return $this;
    }
}
