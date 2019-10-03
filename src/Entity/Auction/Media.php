<?php

namespace App\Entity\Auction;

use App\Entity\Auction;
use App\Entity\Traits\ModificationAware;
use App\Entity\Traits\SoftDeleteable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Auction\MediaRepository") *
 * @ORM\HasLifecycleCallbacks
 */
class Media
{
    use ModificationAware, SoftDeleteable;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auction", inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Auction;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuction(): ?Auction
    {
        return $this->Auction;
    }

    public function setAuction(?Auction $Auction): self
    {
        $this->Auction = $Auction;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
