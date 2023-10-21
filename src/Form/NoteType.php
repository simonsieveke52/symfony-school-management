<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\Student;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;




class NoteType extends ApplicationType
{  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('note',null, $this->getConfiguration("Note finale de l'étudiant", "Donnez une note finale de votre étudiant ..."))
                 ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
