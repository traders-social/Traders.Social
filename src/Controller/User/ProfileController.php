<?php
/**
 * Traders.Social
 *
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller\User;

use App\Form\User\Profile\EditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @return Response
   * @Route("/profile", name="user.profile")
   */
  public function profile(Request $request): Response
  {
    return $this->render('user/profile.html.twig');
  }
  
  /**
   * @param \Symfony\Component\Security\Core\Security $security
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @return Response
   * @Route("/profile/edit", name="user.profile.edit")
   */
  public function edit(Security $security, Request $request): Response
  {
    /** @var \App\Entity\User $user */
    $user = $security->getUser();
    $mergedData = array(
      'user' => $user,
      'info' => $user->getInfo()
    );
  
    $form = $this->createForm(EditType::class, $mergedData);
    return $this->render(
      'user/profile/edit.html.twig',
      ['form' => $form->createView()]
    );
  }
}