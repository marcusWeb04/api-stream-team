<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "discr", type: "string")]
#[ORM\DiscriminatorMap(["user" => User::class, "streamer" => Streamer::class])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Clip::class, mappedBy: 'user')]
    private Collection $clips;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'product_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Streamer::class, mappedBy: 'followers')]
    private Collection $streamers;

    public function __construct()
    {
        $this->clips = new ArrayCollection();
        $this->streamers = new ArrayCollection();
    }

    public function getId(): ?int 
    { 
        return $this->id; 
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

    public function getUserIdentifier(): string 
    { 
        return (string) $this->email; 
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string { return $this->password; }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File { 
        return $this->imageFile; 
    }

    public function setImageName(?string $imageName): void 
    { 
        $this->imageName = $imageName; 
    }

    public function getImageName(): ?string 
    { 
        return $this->imageName; 
    }

    public function getUpdatedAt(): ?\DateTimeImmutable 
    { 
        return $this->updatedAt; 
    }

    /** @return Collection<int, Clip> */
    public function getClips(): Collection 
    { 
        return $this->clips; 
    }

    public function addClip(Clip $clip): static
    {
        if (!$this->clips->contains($clip)) {
            $this->clips->add($clip);
            $clip->setUser($this);
        }
        return $this;
    }

    public function removeClip(Clip $clip): static
    {
        if ($this->clips->removeElement($clip)) {
            if ($clip->getUser() === $this) {
                $clip->setUser(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Streamer> */
    public function getStreamers(): Collection 
    { 
        return $this->streamers; 
    }

    public function addStreamer(Streamer $streamer): static
    {
        if (!$this->streamers->contains($streamer)) {
            $this->streamers->add($streamer);
            $streamer->addFollower($this);
        }
        return $this;
    }

    public function removeStreamer(Streamer $streamer): static
    {
        if ($this->streamers->removeElement($streamer)) {
            $streamer->removeFollower($this);
        }
        return $this;
    }
}
