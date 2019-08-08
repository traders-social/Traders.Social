<?php

namespace App\Security;

use App\Entity\User;
use App\Form\User\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserLoginAuthenticator extends AbstractFormLoginAuthenticator
{
  use TargetPathTrait;
  
  private $entityManager;
  private $urlGenerator;
  private $csrfTokenManager;
  private $passwordEncoder;
  
  public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
  {
    $this->entityManager = $entityManager;
    $this->urlGenerator = $urlGenerator;
    $this->csrfTokenManager = $csrfTokenManager;
    $this->passwordEncoder = $passwordEncoder;
  }
  
  public function supports(Request $request)
  {
    return 'user.login' === $request->attributes->get('_route')
      && $request->isMethod('POST');
  }
  
  public function getCredentials(Request $request)
  {
    $form_data = $request->request->get('login');
    $credentials = [
      'username' => $form_data['username'],
      'password' => $form_data['password'],
      'csrf_token' => $form_data['_token'],
    ];
    $request->getSession()->set(
      Security::LAST_USERNAME,
      $credentials['username']
    );
    
    return $credentials;
  }
  
  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    $token = new CsrfToken(LoginType::class, $credentials['csrf_token']);
    if (!$this->csrfTokenManager->isTokenValid($token)) {
      throw new InvalidCsrfTokenException();
    }
    
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);
    
    if (!$user) {
      // fail authentication with a custom error
      throw new CustomUserMessageAuthenticationException('Username could not be found.');
    }
    
    return $user;
  }
  
  public function checkCredentials($credentials, UserInterface $user)
  {
    return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
  }
  
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
      return new RedirectResponse($targetPath);
    }
    
    return new RedirectResponse($this->urlGenerator->generate('user.profile'));
  }
  
  protected function getLoginUrl()
  {
    return $this->urlGenerator->generate('user.login');
  }
}
