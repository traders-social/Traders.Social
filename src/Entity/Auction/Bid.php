<?php

namespace App\Entity\Auction;

use App\Entity\Auction;
use App\Entity\Traits\ModificationAware;
use App\Entity\Traits\SoftDeleteable;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Auction\BidRepository") *
 * @ORM\HasLifecycleCallbacks
 */
class Bid
{
    use ModificationAware, SoftDeleteable;
  
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auction", inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Auction;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
