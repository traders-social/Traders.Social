<?php

namespace App\Entity;

use App\Entity\Auction\Bid;
use App\Entity\Auction\Media;
use App\Entity\Traits\ModificationAware;
use App\Entity\Traits\SoftDeleteable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AuctionRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false,hardDelete=false)
 */
class Auction
{
  use SoftDeleteable, ModificationAware;
  
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
   * @ORM\Column(type="float")
   */
  private $starting_bid;
  
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Auction\Bid", mappedBy="Auction", orphanRemoval=true)
   */
  private $bids;
  
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Auction\Media", mappedBy="Auction", orphanRemoval=true)
   */
  private $media;
  
  /**
   * @Gedmo\Slug(fields={"title","id"})
   * @ORM\Column(type="string", length=255, unique=true)
   */
  private $slug;
  
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
  
  public function getSlug(): ?string
  {
    return $this->slug;
  }
  
  public function setSlug(string $slug): self
  {
    $this->slug = $slug;
    
    return $this;
  }

  public function getStartingBid(): ?float
  {
      return $this->starting_bid;
  }

  public function setStartingBid(float $starting_bid): self
  {
      $this->starting_bid = $starting_bid;

      return $this;
  }
  
  public function getLatestBid() {
    return $this->bids->last();
  }
}
