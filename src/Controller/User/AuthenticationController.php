<?php
/**
 * Created by PhpStorm.
 * User: Boris
 * Date: 28/10/2018
 * Time: 18:47
 */

namespace App\Controller\User;

use App\Entity\Security\Role;
use App\Entity\User;
use App\Form\User\LoginType;
use App\Form\User\RegisterType;
use Doctrine\ORM\Id\UuidGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
  
  /**
   * @param Request             $request
   * @param AuthenticationUtils $authenticationUtils
   *
   * @return Response
   * @Route("/login", name="user.login")
   */
  public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
  {
    $form = $this->createForm(LoginType::class);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      // Redirect to personal dashboard
      return $this->redirectToRoute('manage.dashboard');
    }
    
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    return $this->render('user/security/login.html.twig', ['form' => $form->createView(), 'error' => $error]);
  }
  
  /**
   * @Route("/logout", name="user.logout", methods={"GET"})
   */
  public function logout()
  {
    throw new HttpException(400, 'Something went wrong logging you out.');
  }
  
  /**
   * @param Request                      $request
   * @param UserPasswordEncoderInterface $passwordEncoder
   *
   * @return Response
   * @Route("/register", name="user.register")
   */
  public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
  {
    $user = new User();
    $info = $user->getInfo();
    $user->setRole($this->getDoctrine()->getRepository(Role::class)->find(Role::ROLE_USER));
    
    $generator = new UuidGenerator();
    $uuid = $generator->generate($this->getDoctrine()->getManagerForClass(User::class), $user);
    $user->setUuid($uuid);
    
    $mergedData = ['user' => $user, 'info' => $info, 'uuid' => $uuid];
    
    $form = $this->createForm(RegisterType::class, $mergedData);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
      $user->setPassword($password);
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      
      return $this->redirectToRoute('user.login');
    }
    
    return $this->render('user/security/register.html.twig', ['form' => $form->createView()]);
  }
}