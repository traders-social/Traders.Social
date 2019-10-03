<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class DashboardController
 *
 * @package App\Controller\Dashboard
 */
class DashboardController extends AbstractController
{
  /**
   * @Route("/manage/dashboard", name="manage.dashboard")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return Response
   */
  public function index(Request $request): Response
  {
    $feed = $this->getUser()->getFeed();
    return $this->render('user/dashboard.html.twig', ['feed' => $feed]);
  }
}
  
