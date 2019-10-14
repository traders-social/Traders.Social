<?php

namespace App\Entity;

use App\Entity\Auction\Bid;
use App\Entity\Security\Role;
use App\Entity\Traits\ModificationTimestamped;
use App\Entity\Traits\SoftDeleteable;
use App\Entity\User\Address;
use App\Entity\User\Info;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false,hardDelete=false)
 */
class User implements UserInterface
{
  use SoftDeleteable, ModificationTimestamped;
  
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;
  
  /**
   * @ORM\Column(type="string", length=180, unique=true)
   */
  private $uuid;
  
  /**
   * @var string The hashed password
   * @ORM\Column(type="string")
   */
  private $password;
  
  /**
   * @ORM\Column(type="string", length=255)
   */
  public $username;
  
  /**
   * @var Info $info
   * @ORM\OneToOne(targetEntity="App\Entity\User\Info", mappedBy="User", cascade={"persist", "remove"})
   */
  private $info;
  
  /**
   * @var Address[] $info
   * @ORM\OneToMany(targetEntity="App\Entity\User\Address", mappedBy="User", orphanRemoval=true)
   */
  private $addresses;
  
  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\Security\Role", inversedBy="users")
   * @ORM\JoinColumn(nullable=false)
   */
  private $role;
  
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Auction", mappedBy="created_by", orphanRemoval=true)
   */
  private $ownedAuctions;
  
  /**
   * @ORM\OneToMany(targetEntity="App\Entity\Auction\Bid", mappedBy="User", orphanRemoval=true)
   */
  private $bids;
  
  /**
   * @Gedmo\Slug(fields={"username","id"})
   * @ORM\Column(type="string", length=255, unique=true)
   */
  private $slug;
  
  public function __construct()
  {
    $this->addresses = new ArrayCollection();
    $this->ownedAuctions = new ArrayCollection();
    $this->bids = new ArrayCollection();
  }
  
  public function getId(): ?int
  {
    return $this->id;
  }
  
  public function getUuid(): ?string
  {
    return $this->uuid;
  }
  
  public function setUuid(string $uuid): self
  {
    $this->uuid = $uuid;
    
    return $this;
  }
  
  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string) $this->username;
  }
  
  /**
   * @param string $username
   * @return $this
   */
  public function setUsername(string $username) {
    $this->username = $username;
    
    return $this;
  }
  
  /**
   * @see UserInterface
   */
  public function getPassword(): string
  {
    return (string) $this->password;
  }
  
  public function setPassword(string $password): self
  {
    $this->password = $password;
    
    return $this;
  }
  
  /**
   * @see UserInterface
   */
  public function getPlainPassword(): string
  {
    return (string) $this->password;
  }
  
  public function setPlainPassword(string $password): self
  {
    $this->password = $password;
    
    return $this;
  }
  
  /**
   * @see UserInterface
   */
  public function getSalt()
  {
    // not needed when using the "bcrypt" algorithm in security.yaml
  }
  
  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }
  
  public function getEmail(): ?string
  {
    return $this->getInfo()->getEmail();
  }
  
  public function setEmail(string $email): self
  {
    $this->getInfo()->setEmail($email);
    
    return $this;
  }
  
  public function hasInfo(): bool
  {
    return !is_null($this->info) && $this->info;
  }
  
  public function getInfo(): ?Info
  {
    if (!$this->hasInfo()) {
      $this->setInfo(new Info());
    }
    return $this->info;
  }
  
  public function setInfo(Info $info): self
  {
    $this->info = $info;
    
    // set the owning side of the relation if necessary
    if ($this !== $info->getUser()) {
      $info->setUser($this);
    }
    
    return $this;
  }
  
  /**
   * @return Collection|Address[]
   */
  public function getAddresses(): Collection
  {
    return $this->addresses;
  }
  
  public function addAddress(Address $address): self
  {
    if (!$this->addresses->contains($address)) {
      $this->addresses[] = $address;
      $address->setUser($this);
    }
    
    return $this;
  }
  
  public function removeAddress(Address $address): self
  {
    if ($this->addresses->contains($address)) {
      $this->addresses->removeElement($address);
      // set the owning side to null (unless already changed)
      if ($address->getUser() === $this) {
        $address->setUser(null);
      }
    }
    
    return $this;
  }
  
  public function getRoles()
  {
    return [$this->getRole()];
  }
  
  public function getRole(): ?Role
  {
    return $this->role;
  }
  
  public function setRole(?Role $role): self
  {
    $this->role = $role;
    return $this;
  }
  
  /**
   * @return Collection|Auction[]
   */
  public function getOwnedAuctions(): Collection
  {
    return $this->ownedAuctions;
  }
  
  public function addOwnedAuction(Auction $ownedAuction): self
  {
    if (!$this->ownedAuctions->contains($ownedAuction)) {
      $this->ownedAuctions[] = $ownedAuction;
    }
    
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
      $bid->setUser($this);
    }
    
    return $this;
  }
  
  public function removeBid(Bid $bid): self
  {
    if ($this->bids->contains($bid)) {
      $this->bids->removeElement($bid);
      // set the owning side to null (unless already changed)
      if ($bid->getUser() === $this) {
        $bid->setUser(null);
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
}
