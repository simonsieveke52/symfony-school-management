<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\Course;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;
use  Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CourseType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre de cours", "Donnez un titre de votre cours ...") )

            ->add('picture', FileType::class, [
                 'attr'=>[
                            'placeholder'=>'Image de cours'
                        ],

                'label' => "Une image de cours",
                'multiple' => false,
                'mapped' => false,
                'required' => true
            ])
            ->add('content', TextareaType::class,  $this->getConfiguration("Contenu de cours", "Donnez un contenu de cours ..."))
            ->add('brochure', FileType::class, [
                 'attr'=>[
                            'placeholder'=>'PDF de cours'
                        ],
                'label' => 'Brochure (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                       ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
