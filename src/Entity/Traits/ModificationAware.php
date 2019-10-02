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

/**
 * Trait ModificationAware
 *
 * @package App\Entity\Traits
 * @ORM\HasLifecycleCallbacks
 */
trait ModificationAware
{
  use ModificationTimestamped {
    ModificationTimestamped::onPrePersist as onParentPrePersists;
    ModificationTimestamped::onPreUpdate as onParentPreUpdate;
  }
  
  /**
   * @ORM\OneToOne(targetEntity="App\Entity\User")
   */
  private $created_by;
  
    /**
   * @ORM\OneToOne(targetEntity="App\Entity\User")
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
    if(is_null($this->getUpdatedBy())) {
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
    $this->updated_by = $updated_by;
    
    return $this;
  }
  
  /**
   * Gets triggered only on insert
   * @ORM\PrePersist
   */
  public function onPrePersist()
  {
    $this->onParentPrePersists();
    if (is_null($this->updated_by) || is_null($this->created_by)) {
      throw new \Exception(sprintf("%s is ModificationAware, it requires you to set the Creator and Updater before persisting", __CLASS__));
    }
  }
  
  /**
   * Gets triggered every time on update
   
   * @ORM\PreUpdate
   */
  public function onPreUpdate()
  {
    $this->onParentPreUpdate();
    if (is_null($this->updated_by)) {
      throw new \Exception(sprintf("%s is ModificationAware, it requires you to set the Updater before persisting", __CLASS__));
    }
  }
}