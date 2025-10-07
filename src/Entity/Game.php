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

    /**
     * @var Collection<int, TypeGame>
     */
    #[ORM\ManyToMany(targetEntity: TypeGame::class, inversedBy: 'games')]
    private Collection $Type;

    /**
     * @var Collection<int, StudioGame>
     */
    #[ORM\ManyToMany(targetEntity: StudioGame::class, inversedBy: 'games')]
    private Collection $StudioGame;

    public function __construct()
    {
        $this->Type = new ArrayCollection();
        $this->StudioGame = new ArrayCollection();
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
     * @return Collection<int, TypeGame>
     */
    public function getType(): Collection
    {
        return $this->Type;
    }

    public function addType(TypeGame $type): static
    {
        if (!$this->Type->contains($type)) {
            $this->Type->add($type);
        }

        return $this;
    }

    public function removeType(TypeGame $type): static
    {
        $this->Type->removeElement($type);

        return $this;
    }

    /**
     * @return Collection<int, StudioGame>
     */
    public function getStudioGame(): Collection
    {
        return $this->StudioGame;
    }

    public function addStudioGame(StudioGame $studioGame): static
    {
        if (!$this->StudioGame->contains($studioGame)) {
            $this->StudioGame->add($studioGame);
        }

        return $this;
    }

    public function removeStudioGame(StudioGame $studioGame): static
    {
        $this->StudioGame->removeElement($studioGame);

        return $this;
    }
}
