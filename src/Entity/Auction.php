<?php

namespace App\Entity;

use App\Entity\Auction\Bid;
use App\Entity\Auction\Media;
use App\Entity\Traits\ModificationAware;
use App\Entity\Traits\SoftDeletable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuctionRepository") *
 * @ORM\HasLifecycleCallbacks
 */
class Auction
{
  use SoftDeletable, ModificationAware;
  
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auction\Bid", mappedBy="Auction", orphanRemoval=true)
     */
    private $bids;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auction\Media", mappedBy="Auction", orphanRemoval=true)
     */
    private $media;

    public function __construct()
    {
        $this->bids = new ArrayCollection();
        $this->media = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setAuction($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);
            // set the owning side to null (unless already changed)
            if ($bid->getAuction() === $this) {
                $bid->setAuction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setAuction($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getAuction() === $this) {
                $medium->setAuction(null);
            }
        }

        return $this;
    }

    public function getDeletedBy(): ?User
    {
        return $this->deleted_by;
    }

    public function setDeletedBy(?User $deleted_by): self
    {
        $this->deleted_by = $deleted_by;

        return $this;
    }
}
