<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 24/10/2018
 * Time: 22:39
 */

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait SoftDeletable
 *
 * @package App\Entity\Traits
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false,hardDelete=false)
 */
trait SoftDeleteable
{
  /**
   * @ORM\Column(type="datetime",nullable=true)
   */
  private $deleted_at;
  
  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")   *
   * @ORM\JoinColumn(name="deleted_by", referencedColumnName="id")
   */
  private $deleted_by;
  
  public function getDeletedAt(): \DateTime {
    return $this->deleted_at;
  }
  
  public function setDeletedAt(\DateTime $time) {
    $this->deleted_at = $time;
  }
  
  public function getDeletedBy(): ?User
  {
    return $this->deleted_by;
  }
  
  public function setDeletedBy(?User $deleted_by) {
    $this->deleted_by = $deleted_by;
  }

  public function isDeleted()
  {
    return null !== $this->deleted_by;
  }
}