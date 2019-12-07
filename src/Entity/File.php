<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PlumTreeSystems\FileBundle\Entity\File as PTSFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File extends PTSFile
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
    private $type;

    public function getId(): ?int
    {
        return $this->id;
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
}