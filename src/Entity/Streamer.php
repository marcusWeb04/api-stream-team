<?php

namespace App\Entity;

use App\Repository\StreamerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreamerRepository::class)]
class Streamer extends User
{
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'streamers')]
    private Collection $followers;

    #[ORM\OneToMany(targetEntity: Clip::class, mappedBy: 'streamer')]
    private Collection $streamerClips;

    public function __construct()
    {
        parent::__construct();
        $this->followers = new ArrayCollection();
        $this->streamerClips = new ArrayCollection();
    }

    /** @return Collection<int, User> */
    public function getFollowers(): Collection 
    { 
        return $this->followers; 
    }

    public function addFollower(User $follower): static
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
        }
        return $this;
    }

    public function removeFollower(User $follower): static
    {
        $this->followers->removeElement($follower);
        return $this;
    }

    /** @return Collection<int, Clip> */
    public function getClips(): Collection 
    { 
        return $this->streamerClips; 
    }

    public function addClip(Clip $clip): static
    {
        if (!$this->streamerClips->contains($clip)) {
            $this->streamerClips->add($clip);
            $clip->setStreamer($this);
        }
        return $this;
    }

    public function removeClip(Clip $clip): static
    {
        if ($this->streamerClips->removeElement($clip)) {
            if ($clip->getStreamer() === $this) {
                $clip->setStreamer(null);
            }
        }
        return $this;
    }
}
