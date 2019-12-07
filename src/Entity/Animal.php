<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 */
class Animal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $breed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $volume;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bornAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $addictions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $trained;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vaccinated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $safeToTransport;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $friendly;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Ad", mappedBy="animal", cascade={"persist", "remove"})
     */
    private $ad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(?string $breed): self
    {
        $this->breed = $breed;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getBornAt(): ?\DateTimeInterface
    {
        return $this->bornAt;
    }

    public function setBornAt(\DateTimeInterface $bornAt): self
    {
        $this->bornAt = $bornAt;

        return $this;
    }

    public function getAddictions(): ?string
    {
        return $this->addictions;
    }

    public function setAddictions(?string $addictions): self
    {
        $this->addictions = $addictions;

        return $this;
    }

    public function getTrained(): ?bool
    {
        return $this->trained;
    }

    public function setTrained(?bool $trained): self
    {
        $this->trained = $trained;

        return $this;
    }

    public function getVaccinated(): ?bool
    {
        return $this->vaccinated;
    }

    public function setVaccinated(?bool $vaccinated): self
    {
        $this->vaccinated = $vaccinated;

        return $this;
    }

    public function getSafeToTransport(): ?bool
    {
        return $this->safeToTransport;
    }

    public function setSafeToTransport(?bool $safeToTransport): self
    {
        $this->safeToTransport = $safeToTransport;

        return $this;
    }

    public function getFriendly(): ?bool
    {
        return $this->friendly;
    }

    public function setFriendly(?bool $friendly): self
    {
        $this->friendly = $friendly;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(Ad $ad): self
    {
        $this->ad = $ad;

        // set the owning side of the relation if necessary
        if ($ad->getAnimal() !== $this) {
            $ad->setAnimal($this);
        }

        return $this;
    }
}
