<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Event\Doctrine\Entity;


use App\Entity\Traits\SoftDeleteable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\SoftDeleteable\SoftDeleteableListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SoftDeletedListener
 *
 * @package App\Event\Doctrine\Entity
 */
class SoftDeletedListener implements EventSubscriber
{
  /**
   * @var TokenStorageInterface
   */
  private $tokenStorage;
  
  public function __construct(TokenStorageInterface $tokenStorage) {
    $this->tokenStorage = $tokenStorage;
  }
  
  public function getSubscribedEvents()
  {
    return [ SoftDeleteableListener::PRE_SOFT_DELETE ];
  }
  
  public function preSoftDelete(LifecycleEventArgs $args) {
    if ($args->getEntity() instanceof SoftDeleteable) {
      $args->getEntity()->setDeletedBy($this->tokenStorage->getToken()->getUser());
    }
  }
}