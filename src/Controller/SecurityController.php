<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): Response
    {
       
    }
    /**
     * @Route("/register", name="security_register")
     */
    public function register( Request $request, UserPasswordEncoderInterface $encoder): Response
    {   $manager = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setCreatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('security/register.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
