<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 24/10/2018
 * Time: 22:38
 */

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait ModificationAware
 *
 * @package App\Entity\Traits
 * @ORM\HasLifecycleCallbacks
 */
trait ModificationAware
{
  use ModificationTimestamped;
  
  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
   * @Gedmo\Blameable(on="create")
   */
  private $created_by;
  
  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
   * @Gedmo\Blameable(on="update")
   */
  private $updated_by;
  
  /**
   * @return User|null
   */
  public function getCreatedBy(): ?User
  {
    return $this->created_by;
  }
  
  /**
   * @param User $created_by
   * @return ModificationAware
   */
  public function setCreatedBy(User $created_by): self
  {
    $this->created_by = $created_by;
    
    // Creators update the entity as well
    if(null === $this->getUpdatedBy()) {
      $this->setUpdatedBy($created_by);
    }
    
    return $this;
  }
  
  /**
   * @return User|null
   */
  public function getUpdatedBy(): ?User
  {
    return $this->updated_by;
  }
  
  /**
   * @param User $updated_by
   * @return ModificationAware
   */
  public function setUpdatedBy(User $updated_by): self
  {
    if(null === $this->getCreatedBy()) {
      $this->setCreatedBy($updated_by);
    }
    
    $this->updated_by = $updated_by;
    
    return $this;
  }
}