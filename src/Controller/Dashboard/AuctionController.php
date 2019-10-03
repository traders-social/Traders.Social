<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller\Dashboard;


use App\Entity\Auction;
use App\Form\Auction\EditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AuctionController
 *
 * @package App\Controller\Dashboard
 */
class AuctionController  extends AbstractController
{
  /**
   * @Route("/manage/auction/{slug}/view", name="manage.auction.view")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   * @throws \Exception
   */
  public function view(Request $request): Response {
    $auction = $this->getDoctrine()
      ->getManagerForClass(Auction::class)
      ->getRepository(Auction::class)
      ->findOneBy([
        'slug' => $request->get('slug'),
        'created_by' => $this->getUser()->getId()
      ]);
  
    if (null === $auction) $this->redirectToRoute('manage.auction.list');
    
    return $this->render(
      'user/dashboard/manage/auction/view.html.twig',
      [ 'auction' => $auction ]
    );
  }
  
  /**
   * @Route("/manage/auction/create", name="manage.auction.create")
   * @Route("/manage/auction/{slug}/edit", name="manage.auction.edit")
   *
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
        'slug' => $request->get('slug'),
        'created_by' => $this->getUser()->getId()
      ]);
    
    if (null === $auction) $auction = new Auction();
    
    $form = $this->createForm(EditType::class, $auction);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $auction->setUpdatedBy($this->getUser());
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($auction);
      $entityManager->flush();
      return $this->redirectToRoute('manage.auction.list');
    }
    
    return $this->render(
      'user/dashboard/manage/auction/edit.html.twig',
      [
        'form' => $form->createView(),
        'create' => $auction->getCreatedBy() == null,
        'auction' => $auction
      ]
    );
  }
  
  /**
   * @Route("/manage/auction/{slug}/delete", name="manage.auction.delete")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function delete(Request $request) {
    $auction = $this->getDoctrine()
      ->getManagerForClass(Auction::class)
      ->getRepository(Auction::class)
      ->findOneBy([
        'slug' => $request->get('slug'),
        'created_by' => $this->getUser()->getId()
      ]);
    
    if (is_null($auction)) {
      return $this->redirectToRoute('manage.auction.list');
    }
    
    $entityManager = $this->getDoctrine()->getManager();
    $auction->setDeletedBy($this->getUser());
    $entityManager->persist($auction);
    $entityManager->remove($auction);
    $entityManager->flush();
    
    $this->redirectToRoute('manage.auction.list');
  }
  
  /**
   * @Route("/manage/auction/overview", name="manage.auction.list")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   * @throws \Exception
   */
  public function manage(Request $request): Response {
    $auctions = $this->getUser()->getOwnedAuctions();
    return $this->render(
      'user/dashboard/manage/auction/list.html.twig',
      ['auctions' => $auctions]
    );
  }
  
}