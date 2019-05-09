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
use App\Form\UserRegisterType;
use Doctrine\ORM\Id\UuidGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthenticationController extends AbstractController
{
    
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
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

        $mergedData = array(
            'user' => $user,
            'info' => $info,
            'uuid' => $uuid
        );

        $form = $this->createForm(UserRegisterType::class, $mergedData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user.login');
        }

        return $this->render(
            'user/security/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}