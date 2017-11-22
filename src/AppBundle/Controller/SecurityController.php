<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Member;
use AppBundle\Form\MemberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
    /**
     * @Route("new/login", name="login")
     */
    public function loginAction()
    {

        return $this->render('security/login.html.twig',[]);
    }

    /**
     * @Route("/logout")
     * @throws \RuntimeException
     */
    public function logoutAction()
    {
        throw new \RuntimeException('This should never be called directly');
    }

    /**
     * @Route("new/register", name="register")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function registerAction(Request $request)
    {
        $member = new Member() ;
        $form = $this ->createForm(MemberType::class,$member,[

        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $this->get('security.password_encoder')
                            ->encodePassword(
                                $member,
                                $member->getPlainPassword()

                            );
            $member->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            $token = new UsernamePasswordToken(
              $member,
              $password,
              'main',
              $member->getRoles()
            );

            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main',serialize($token));

            $this->addFlash(
                'notice',
                'You are successfully registered'
            );

            $this->redirectToRoute('homepage');


        }


        return $this->render('security/register.html.twig',[
            'register_form' => $form->createView()
        ]);
    }


}
