<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait ModificationTimestamped
 *
 * @package App\Entity\Traits
 */
trait ModificationTimestamped
{
  /**
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="create")
   */
  private $created_at;
  
  /**
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="update")
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
}