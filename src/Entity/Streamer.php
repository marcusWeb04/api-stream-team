<?php

namespace App\Entity;

use App\Repository\StreamerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreamerRepository::class)]
class Streamer extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $channelName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $platform = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Stream>
     */
    #[ORM\OneToMany(targetEntity: Stream::class, mappedBy: 'streamer')]
    private Collection $streams;

    public function __construct()
    {
        parent::setRoles(['ROLE_STREAMER']);
        $this->streams = new ArrayCollection();
    }

    public function getChannelName(): ?string
    {
        return $this->channelName;
    }

    public function setChannelName(?string $channelName): static
    {
        $this->channelName = $channelName;
        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): static
    {
        $this->platform = $platform;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Stream>
     */
    public function getStreams(): Collection
    {
        return $this->streams;
    }

    public function addStream(Stream $stream): static
    {
        if (!$this->streams->contains($stream)) {
            $this->streams->add($stream);
            $stream->setStreamer($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): static
    {
        if ($this->streams->removeElement($stream)) {
            // set the owning side to null (unless already changed)
            if ($stream->getStreamer() === $this) {
                $stream->setStreamer(null);
            }
        }

        return $this;
    }
}
