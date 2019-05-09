<?php
/**
 * fishconnect
 * @author Boris Wintein<boris.wintein@gmail.com>
 */

namespace App\Controller;


use App\Entity\Enthusiast;
use App\Form\EnthusiastSubscribeType;
use Doctrine\ORM\Id\UuidGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EnthusiastController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $enthusiast = new Enthusiast();
        $generator = new UuidGenerator();
        $uuid = $generator->generate($this->getDoctrine()->getManagerForClass(Enthusiast::class), $enthusiast);
        $enthusiast->setUuid($uuid);
        $enthusiast->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(EnthusiastSubscribeType::class, $enthusiast);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enthusiast);
            $entityManager->flush();
            return $this->redirectToRoute('landing.subscribed');
        }

        return $this->render(
            'enthusiasts/landing.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/subscribed", name="landing.subscribed")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success(Request $request)
    {
        return $this->render(
            'enthusiasts/success.html.twig'
        );
    }
}