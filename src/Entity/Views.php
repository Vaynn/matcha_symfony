<?php

namespace App\Entity;

use App\Repository\ViewsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViewsRepository::class)]
class Views
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $isView = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $viewBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $viewedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsView(): ?User
    {
        return $this->isView;
    }

    public function setIsView(?User $isView): static
    {
        $this->isView = $isView;

        return $this;
    }

    public function getViewBy(): ?User
    {
        return $this->viewBy;
    }

    public function setViewBy(?User $viewBy): static
    {
        $this->viewBy = $viewBy;

        return $this;
    }

    public function getViewedAt(): ?\DateTimeImmutable
    {
        return $this->viewedAt;
    }

    public function setViewedAt(\DateTimeImmutable $viewedAt): static
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }
}
