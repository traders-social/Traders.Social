<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Services;


use App\Entity\Auction;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DashboardFeedService
{
  /**
   * @var EntityManagerInterface $entityManager
   */
  private $entityManager;
  
  /**
   * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
   */
  private $urlGenerator;
  
  public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $generator)
  {
    $this->entityManager = $entityManager;
    $this->urlGenerator = $generator;
  }
  
  /**
   * @param \App\Entity\User $user
   *
   * @return bool
   */
  public function getFeedData(User $user) {
    // Fetch bids, auctions by user
    $auctions = $this->entityManager->getRepository(Auction::class)
      ->findBy(['created_by' => $user],['created_at' => 'DESC']);
    
    $bids = $this->entityManager->getRepository(Auction\Bid::class)
      ->findBy(['created_by' => $user],['created_at' => 'DESC']);
    
    $feed = array_merge($auctions, $bids);
    usort($feed, [$this, 'sort']);
    return array_map([$this, 'extract'], $feed);
  }
  
  /**
   * @param \App\Entity\Auction\Bid|\App\Entity\Auction $a
   * @param \App\Entity\Auction\Bid|\App\Entity\Auction $b
   *
   * @return int
   */
  private function sort($a, $b) {
    if ($a->getCreatedAt() == $b->getCreatedAt()) {
      return 0;
    }
    return ($a->getCreatedAt() > $b->getCreatedAt()) ? +1 : -1;
  }
  
  /**
   * @param \App\Entity\Auction\Bid|\App\Entity\Auction $a
   * @return array
   */
  private function extract($a) {
    if ($a instanceof Auction) {
     
      return [
        'type' => 'auction',
        'title' => $a->getTitle(),
        'description' => $a->getDescription(),
        'timestamp' => $a->getCreatedAt(),
        'link' => $this->urlGenerator->generate('auction.view', ['slug' => $a->getSlug()])
      ];
    }
  
    /** @var \App\Entity\Auction\Bid $a */
    return [
      'type' => 'bid',
      'title' => "Added a bid on an Auction",
      'description' => sprintf('Bid %s on %s', $a->getAmount(), $a->getAuction()->getTitle()),
      'timestamp' => $a->getCreatedAt(),
      'link' => $this->urlGenerator->generate('auction.view', ['slug' => $a->getAuction()->getSlug()])
    ];
  }
}