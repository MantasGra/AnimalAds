<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Boost", inversedBy="ad", cascade={"persist", "remove"})
     */
    private $boost;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewCount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden;

    /**
     * @ORM\Column(type="integer")
     */
    private $reportCount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SavedAd", mappedBy="ad")
     */
    private $savedAds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="ad", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdAds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Animal", inversedBy="ad", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $animal;

    public function __construct()
    {
        $this->hidden = false;
        $this->viewCount = 0;
        $this->reportCount = 0;
        $this->createdAt = new \DateTime();
        $this->savedAds = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBoost(): ?Boost
    {
        return $this->boost;
    }

    public function setBoost(?Boost $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): self
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getReportCount(): ?int
    {
        return $this->reportCount;
    }

    public function setReportCount(int $reportCount): self
    {
        $this->reportCount = $reportCount;

        return $this;
    }

    /**
     * @return Collection|SavedAd[]
     */
    public function getSavedAds(): Collection
    {
        return $this->savedAds;
    }

    public function addSavedAd(SavedAd $savedAd): self
    {
        if (!$this->savedAds->contains($savedAd)) {
            $this->savedAds[] = $savedAd;
            $savedAd->setAd($this);
        }

        return $this;
    }

    public function removeSavedAd(SavedAd $savedAd): self
    {
        if ($this->savedAds->contains($savedAd)) {
            $this->savedAds->removeElement($savedAd);
            // set the owning side to null (unless already changed)
            if ($savedAd->getAd() === $this) {
                $savedAd->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

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

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }
}
