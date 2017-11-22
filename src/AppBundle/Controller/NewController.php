<?php

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type as Type;

class NewController extends Controller
{
    /**
     * @Route("/news", name="new_list")
     */
    public function listAction()
    {
        //username logged:
         $auth_checker = $this->get('security.authorization_checker');
         $token = $this->get('security.token_storage')->getToken();
         $member = $token->getUsername();

        $news = $this->getDoctrine()
            ->getRepository('AppBundle:News')
            ->findBy(array('member' => $member));

        return $this->render('new/index.html.twig', array(
        'news' => $news
        ));
    }


    /**
        * @Route("new/create", name="new_create")
        */
        public function createAction(Request $request)
        {



            $news = new News;
            $form = $this->createFormBuilder($news)
                    ->add('name',Type\TextType::class,array('attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                    ->add('category',Type\TextType::class,array('attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                    ->add('description',Type\TextareaType::class,array('attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                    ->add('priority',Type\ChoiceType::class,array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'Hight' => 'Hight'), 'attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                    ->add('due_date',Type\DateTimeType::class,array('attr' => array('class' => 'formcontrol', 'style' =>'margin-bottom:15px')))
                    ->add('save',Type\SubmitType::class,array('label' => 'Create news', 'attr' => array('class' => 'btn btn-primary', 'style' =>'margin-bottom:15px')))

                    ->getForm();

             $form->handleRequest($request);

             if($form->isSubmitted() && $form->isValid()){



             $name = $form['name']->getData();
             $category = $form['category']->getData();
             $description = $form['description']->getData();
             $priority = $form['priority']->getData();
             $due_date = $form['due_date']->getData();

             $now = new\Datetime('now');
            //username logged:
            $auth_checker = $this->get('security.authorization_checker');
            $token = $this->get('security.token_storage')->getToken();
            $member = $token->getUsername();

             $news->setMember($member);
             $news->setName($name);
             $news->setCategory($category );
             $news->setDescription($description);
             $news->setPriority($priority);
             $news->setDueDate($due_date);
             $news->setCreateDate($now);

             $em = $this->getDoctrine()->getManager();

             $em->persist($news);
             $em->flush();

             $this->addFlash(
             'notice',
             'News Added'
             );

            return $this->redirectToRoute('new_list');
             }
            return $this->render('new/create.html.twig', array(
            'form' => $form->createView()
            ));
        }

    /**
             * @Route("new/edit/{id}", name="new_edit")
             */
            public function editAction($id, Request $request)
            {
                $news = $this->getDoctrine()
                     ->getRepository('AppBundle:News')
                      ->find($id);

                $news->setName($news->getName());
                $news->setCategory($news->getCategory() );
                $news->setDescription($news->getDescription());
                $news->setPriority($news->getPriority());
                $news->setDueDate($news->getDueDate());
                $news->setCreateDate(new\Datetime('now'));


                $form = $this->createFormBuilder($news)
                      ->add('name',Type\TextType::class,array('attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                      ->add('category',Type\TextType::class,array('attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                      ->add('description',Type\TextareaType::class,array('attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                      ->add('priority',Type\ChoiceType::class,array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'Hight' => 'Hight'), 'attr' => array('class' => 'form-control', 'style' =>'margin-bottom:15px')))
                      ->add('due_date',Type\DateTimeType::class,array('attr' => array('class' => 'formcontrol', 'style' =>'margin-bottom:15px')))
                      ->add('save',Type\SubmitType::class,array('label' => 'Update news', 'attr' => array('class' => 'btn btn-primary', 'style' =>'margin-bottom:15px')))

                      ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                          $name = $form['name']->getData();
                          $category = $form['category']->getData();
                          $description = $form['description']->getData();
                          $priority = $form['priority']->getData();
                          $due_date = $form['due_date']->getData();

                          $now = new\Datetime('now');

                          $em = $this->getDoctrine()->getManager();
                          $news = $em->getRepository('AppBundle:News')->find($id);

                          $news->setName($name);
                          $news->setCategory($category );
                          $news->setDescription($description);
                          $news->setPriority($priority);
                          $news->setDueDate($due_date);
                          $news->setCreateDate($now);


                          $em->flush();

                          $this->addFlash(
                          'notice',
                          'News Updated'
                          );

                         return $this->redirectToRoute('new_list');
                          }
                         return $this->render('new/edit.html.twig', array(
                         'news' => $news,
                         'form' => $form->createView()
                         ));}


    /**
             * @Route("new/details/{id}", name="new_details")
             */
            public function detailsAction($id)
            {
                $news = $this->getDoctrine()
                    ->getRepository('AppBundle:News')
                    ->find($id);

                 return $this->render('new/details.html.twig', array(
                 'news' => $news
                 ));
            }

/**
             * @Route("new/delete/{id}", name="new_delete")
             */
            public function deleteAction($id)
            {
                $em = $this->getDoctrine()->getManager();
                $news = $em->getRepository("AppBundle:News")->find($id);

                $em->remove($news);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Todo Removed'
                    );

                 return $this->redirectToRoute('new_list');

            }

}
