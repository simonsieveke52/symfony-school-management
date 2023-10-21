<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class UserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Nom Complète", "Donnez votre joulie nom ..."))

            ->add('picture', FileType::class,[
                'label' => "Votre image",
                'multiple' => false,
                'mapped' => false,
                'required' => true,
               
            ] )

            ->add('email', EmailType::class, $this->getConfiguration("Email", "Donnez votre email ..."))
             ->add('password', RepeatedType::class, [
    'type' => PasswordType::class,
    'invalid_message' => 'The password fields must match.',
    'options' => ['attr' => ['class' => 'password-field','placeholder'=>'Confirmer votre mot de passe']],
    'required' => true,
    'first_options'  => ['label' => 'Password' ,'attr'=>['placeholder'=>'Entrer votre mot de passe'],],
    'second_options' => ['label' => 'Confirmer votre  password'],
])
            //->add('captcha', CaptchaType::class)
            
            ->add('Inscription', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-warning p-3 font-weight-bold',

                      ]
                  ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
