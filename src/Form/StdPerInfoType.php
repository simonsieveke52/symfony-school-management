<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\StdPerInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class StdPerInfoType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('age', DateType::class,[
          'years' => range(date('Y')-16, date('Y')-30),
          'attr'=>[
            "placeholder"=>"Donnez votre date de naissance ..."
             ],
          'label'=>"Date de Naissance :" 
            ])
            ->add('cin')
            ->add('gendre', ChoiceType::class, [
    'choices'  => [
        'MALE' => 'MALE',
        'FEMALE' => 'FEMALE',
    ],
])
            ->add('city', TextType::class, $this->getConfiguration("Ville", "Donnez votre ville ..."))
            ->add('phone', TextType::class, $this->getConfiguration("Téléphone : ", "Donnez votre numéro de téléphone ..."))
            ->add('submit', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StdPerInfo::class,
        ]);
    }
}
