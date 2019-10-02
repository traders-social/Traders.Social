<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Entity\Traits;

trait ModificationTimestamped
{
  /**
   * @ORM\Column(type="datetime")
   */
  private $created_at;
  
  /**
   * @ORM\Column(type="datetime")
   */
  private $updated_at;
  
  public function getCreatedAt(): \DateTime {
    return $this->created_at;
  }
  
  protected function setCreatedAt(\DateTime $time) {
    $this->created_at = $time;
  }
  
  public function getUpdatedAt(): \DateTime {
    return $this->updated_at;
  }
  
  protected function setUpdatedAt(\DateTime $time) {
    $this->updated_at = $time;
  }
  
  /**
   * Gets triggered only on insert
   * @ORM\PrePersist
   */
  public function onPrePersist()
  {
    $this->setCreatedAt(new \DateTime("now"));
    $this->setUpdatedAt(new \DateTime("now"));
  }
  
  /**
   * Gets triggered every time on update
   
   * @ORM\PreUpdate
   */
  public function onPreUpdate()
  {
    $this->setUpdatedAt(new \DateTime("now"));
  }
  
}