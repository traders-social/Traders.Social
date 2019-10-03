<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller\Dashboard;

use App\Form\User\Profile\EditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ProfileController
 *
 * @package App\Controller\Dashboard
 */
class ProfileController  extends AbstractController
{
  /**
   * @Route("/manage/profile/edit", name="manage.profile")
   * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return Response
   */
  public function edit(Request $request): Response
  {
    $user = $this->getUser();
    $mergedData = array(
      'user' => $user,
      'info' => $user->getInfo()
    );
    
    $form = $this->createForm(EditType::class, $mergedData);
    return $this->render(
      'user/dashboard/manage/profile.html.twig',
      ['form' => $form->createView()]
    );
  }
}