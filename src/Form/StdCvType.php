<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\StdCv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StdCvType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, $this->getConfiguration("Ville d'étude :", "Ville où vous avez étudier 1 bac :..."))
            ->add('school', TextType::class, $this->getConfiguration("Lycée d'étude :", "Lycée où vous avez obtenir 1 bac :..."))
            ->add('year', DateType::class, [
          'years' => range(date('Y')-3, date('Y')-1),
          'attr'=>[
            "placeholder"=>"quand vous avez étudié en 1 bac ..."
             ],
          'label'=>"Date d'étude :" 
            ])
            ->add('moyen', NumberType::class, $this->getConfiguration("Moyenne Générale :", "Moyenne Générale :..."))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StdCv::class,
        ]);
    }
}
