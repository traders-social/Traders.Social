<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Event\Doctrine;

use Gedmo\Blameable\BlameableListener;
use Gedmo\Translatable\TranslatableListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class DoctrineExtensionSubscriber
 *
 * @package App\EventSubscriber
 */
class ExtensionSubscriber implements EventSubscriberInterface
{
  /**
   * @var BlameableListener
   */
  private $blameableListener;
  /**
   * @var TokenStorageInterface
   */
  private $tokenStorage;
  /**
   * @var TranslatableListener
   */
  private $translatableListener;
  
  public function __construct(
    TokenStorageInterface $tokenStorage,
    TranslatableListener $translatableListener,
    BlameableListener $blameableListener
  ) {
    $this->tokenStorage = $tokenStorage;
    $this->translatableListener = $translatableListener;
    $this->blameableListener = $blameableListener;
  }
  
  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::REQUEST => 'onKernelRequest',
      KernelEvents::FINISH_REQUEST => 'onLateKernelRequest',
    ];
  }
  
  public function onKernelRequest(): void
  {
    if ($this->tokenStorage !== null &&
      $this->tokenStorage->getToken() !== null &&
      $this->tokenStorage->getToken()->isAuthenticated() === true
    ) {
      $this->blameableListener->setUserValue($this->tokenStorage->getToken()->getUser());
    }
  }
  
  public function onLateKernelRequest(FinishRequestEvent $event): void
  {
    $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
  }
}