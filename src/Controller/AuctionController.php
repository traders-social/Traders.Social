<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller;


use App\Entity\Auction;
use App\Form\Auction\EditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends AbstractController
{
  /**
   * @Route("/auction/create", name="auction.create")   *
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   * @throws \Exception
   */
  public function create(Request $request): Response {
    
    $auction = new Auction();
    $form = $this->createForm(EditType::class, $auction);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $auction->setCreatedBy($this->getUser());
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($auction);
      $entityManager->flush();
      return $this->redirectToRoute('auction.manage');
    }
  
    return $this->render(
      'auction/edit.html.twig',
      [
        'form' => $form->createView(),
        'create' => $auction->getCreatedBy() == null,
        'user'
      ]
    );
  }
  
  /**
   * @Route("/auction/{id<\d+>}/edit", name="auction.edit")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   * @throws \Exception
   */
  public function edit(Request $request): Response {
    
    $auction = $this->getDoctrine()
      ->getManagerForClass(Auction::class)
      ->getRepository(Auction::class)
      ->findOneBy([
        'id' => $request->get('id'),
        'created_by' => $this->getUser()->getId()
      ]);
    
    if (is_null($auction)) {
      return $this->redirectToRoute('auction.create');
    }
    
    $form = $this->createForm(EditType::class, $auction);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $auction->setCreatedBy($this->getUser());
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($auction);
      $entityManager->flush();
      return $this->redirectToRoute('auction.manage');
    }
    
    return $this->render(
      'auction/edit.html.twig',
      [
        'form' => $form->createView(),
        'create' => $auction->getCreatedBy() == null,
        'user'
      ]
    );
  }
  
  /**
   * @Route("/auction/manage", name="auction.manage")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   * @throws \Exception
   */
  public function manage(Request $request): Response {
    
    $auctions = $this->getUser()->getOwnedAuction();
    return $this->render(
      'auction/list.html.twig',
      ['auctions' => $auctions]
    );
  }
}