<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SavedAdRepository")
 */
class SavedAd
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $savedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="savedAds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="savedAds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->savedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSavedAt(): ?\DateTimeInterface
    {
        return $this->savedAt;
    }

    public function setSavedAt(\DateTimeInterface $savedAt): self
    {
        $this->savedAt = $savedAt;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
