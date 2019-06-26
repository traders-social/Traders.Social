<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
  /**
   * @Route("/", name="home")
   *
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function home(Request $request)
  {
    return $this->render('home/index.html.twig', []);
  }
  
}