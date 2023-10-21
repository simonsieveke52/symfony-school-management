<?php

namespace App\Form;

use App\Entity\Prof;
use App\Entity\Classe;
use App\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClasseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
           
           
           ->add('profs', EntityType::class,[
                 'class'=>Prof::class,
                 'choice_label' => 'name',
                  'multiple' => true,
                  'expanded' => true,
                 'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC');
    },

            ])
            ->add('students', EntityType::class,[
                 'class'=>Student::class,
                 'choice_label' => 'user.name',
                  'multiple' => true,
                  'expanded' => true,
                 'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')

            ->join('u.user', 's')
            ->orderBy('s.name', 'ASC');

    },

            ])
            
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
