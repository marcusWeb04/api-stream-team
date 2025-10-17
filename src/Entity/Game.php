<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: TypeGame::class, inversedBy: 'games')]
    private Collection $types;

    #[ORM\ManyToMany(targetEntity: StudioGame::class, inversedBy: 'games')]
    private Collection $studioGames;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->studioGames = new ArrayCollection();
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

    /** @return Collection<int, TypeGame> */
    public function getTypes(): Collection 
    { 
        return $this->types; 
    }

    public function addType(TypeGame $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }
        return $this;
    }

    public function removeType(TypeGame $type): static
    {
        $this->types->removeElement($type);
        return $this;
    }

    /** @return Collection<int, StudioGame> */
    public function getStudioGames(): Collection { return $this->studioGames; }

    public function addStudioGame(StudioGame $studioGame): static
    {
        if (!$this->studioGames->contains($studioGame)) {
            $this->studioGames->add($studioGame);
        }
        return $this;
    }

    public function removeStudioGame(StudioGame $studioGame): static
    {
        $this->studioGames->removeElement($studioGame);
        return $this;
    }
}
