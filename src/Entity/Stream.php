<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Stream
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'streams')]
    private ?Streamer $streamer = null;

    #[ORM\OneToMany(targetEntity: Clip::class, mappedBy: 'stream')]
    private Collection $clips;

    public function __construct()
    {
        $this->clips = new ArrayCollection();
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getName(): ?string { 
        return $this->name; 
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getStreamer(): ?Streamer { return $this->streamer; }

    public function setStreamer(?Streamer $streamer): static
    {
        $this->streamer = $streamer;
        return $this;
    }

    /** @return Collection<int, Clip> */
    public function getClips(): Collection { 
        return $this->clips; 
    }

    public function addClip(Clip $clip): static
    {
        if (!$this->clips->contains($clip)) {
            $this->clips->add($clip);
            $clip->setStream($this);
        }
        return $this;
    }

    public function removeClip(Clip $clip): static
    {
        if ($this->clips->removeElement($clip)) {
            if ($clip->getStream() === $this) {
                $clip->setStream(null);
            }
        }
        return $this;
    }
}
