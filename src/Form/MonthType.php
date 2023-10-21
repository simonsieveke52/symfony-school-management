<?php

namespace App\Form;

use App\Entity\Month;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MonthType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('outputs', TextType::class, $this->getConfiguration("Revenus", "Entrez Revenues...") )
            ->add('inputs', TextType::class, $this->getConfiguration("Revenus", "Entrez Revenues ...") )
            ->add('name', TextType::class, $this->getConfiguration("Nom", "Donnez doit être contient le nom du mois ou une date ...") )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Month::class,
        ]);
    }
}
