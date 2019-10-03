<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 *
 * @package App\Controller
 */
class ProfileController extends AbstractController {
  
  /**
   * @Route("/user/{slug}", name="user.profile")
   *
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function view(Request $request) {
    $user = $this->getDoctrine()
      ->getManagerForClass(User::class)
      ->getRepository(User::class)
      ->findOneBy(['slug' => $request->get('slug')]);
    
    if (null === $user) $this->redirectToRoute('home');
    
    return $this->render('user/profile.html.twig', ['user' => $user]);
  }
}