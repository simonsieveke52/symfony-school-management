<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\Prof;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;
use  Symfony\Component\Form\Extension\Core\Type\TextType;
use  Symfony\Component\Form\Extension\Core\Type\MoneyType;
use  Symfony\Component\Form\Extension\Core\Type\EmailType;
use  Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Classe;
use App\Entity\Student;


class ProfType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Nom de prof", "Donnez un nom de nouveau prof ..."))
            ->add('email', EmailType::class,  $this->getConfiguration("Email de prof :", "Donnez leur email de connexion ..."))
            ->add('phone', TextType::class, $this->getConfiguration("Tél de Prof", "Donnez le tél du prof ..."))
            ->add('salaire', MoneyType::class,  $this->getConfiguration("Salaire", "Donnez leur salaire ..."))
            ->add('password', PasswordType::class,  $this->getConfiguration("Mot de passe", "Donnez un mot de passe pour que prof pet connecté ..."))
            ->add('matter', TextType::class,  $this->getConfiguration("Matière à enseigner :", "Donnez un mot un matière à enseigner ..."))
            ->add('classes', EntityType::class,[
                 'class'=>Classe::class,
                 'choice_label' => 'name',
                  'multiple' => true,
                  'expanded' => true,
                 'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC');
    },

            ])
            ->add('submit', SubmitType::class,[
      'attr'=>['class'=>'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prof::class,
        ]);
    }
}
