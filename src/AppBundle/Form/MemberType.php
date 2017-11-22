<?php

namespace AppBundle\Form;

use AppBundle\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username',TextType::class,['attr' => ['class' => 'form-control', 'style' =>'margin-bottom:15px']])
            ->add('email', EmailType::class,['attr' => ['class' => 'form-control', 'style' =>'margin-bottom:15px']])
            ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password' , 'attr' => ['class' => 'form-control', 'style' =>'margin-bottom:15px']
                ],
                'second_options' => [
                    'label' => 'Repeat Password' , 'attr' => ['class' => 'form-control', 'style' =>'margin-bottom:15px']
                ]
            ])
            ->add('register',SubmitType::class,['attr' => ['class' => 'btn btn-primary', 'style' =>'margin-bottom:15px']])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_member';
    }


}
