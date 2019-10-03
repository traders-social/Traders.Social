<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller;


use App\Entity\Auction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends AbstractController
{
  /**
   * @Route("/auction/list", name="auction.list")
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function overview(Request $request): Response {
    $auctions = $this->getDoctrine()
      ->getManagerForClass(Auction::class)
      ->getRepository(Auction::class)
      ->findAll();
  
    return $this->render(
      'auction/new.html.twig',
      [
        'auctions' => $auctions
      ]
    );
  }
  
  /**
   * @Route("/auction/{slug}/view", name="auction.view")
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function view(Request $request): Response {
    $auction = $this->getDoctrine()
      ->getManagerForClass(Auction::class)
      ->getRepository(Auction::class)
      ->findOneBy([
        'slug' => $request->get('slug')
      ]);
  
    if (is_null($auction)) {
      return $this->redirectToRoute('auction.manage');
    }
    
    return $this->render(
      'auction/view.html.twig',
      [
        'auction' => $auction
      ]
    );
  }

}