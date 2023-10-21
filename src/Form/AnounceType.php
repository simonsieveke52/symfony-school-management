<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\Anounce;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;
use  Symfony\Component\Form\Extension\Core\Type\TextType;
use  Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AnounceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre de l'annonce ", "Entrer votre titre...",[            
            ]) )
            ->add('content', TextareaType::class, $this->getConfiguration("Contenu de l'annonce ", "Entrer votre contenu...",[           
            ]))
            ->add('classe', EntityType::class, [
               'class'=>Classe::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Anounce::class,
        ]);
    }
}
