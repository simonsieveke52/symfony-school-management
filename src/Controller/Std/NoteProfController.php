<?php

namespace App\Controller\Std;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnounceRepository;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;
use App\Entity\Prof;
use App\Entity\Student;
use App\Entity\Note;
use App\Entity\Anounce;
use App\Form\AnounceType;
use App\Form\NoteType;
use App\Entity\Course;
use App\Form\CourseType;
use Doctrine\ORM\EntityManagerInterface;

class NoteProfController extends AbstractController
{
	  /**
	   * @Route("/prof/note/{id}", name="prof_add_note")
	   */
	  
	  public function addNote(Student $std ,Request $request, EntityManagerInterface $manager)
	  {             
                $hasNoted = false;
                $prof = $this->getUser();
                foreach ($std->getNotes() as $note) {
                  if ($note->getProf() == $prof) {
                     $hasNoted = true;

                $form=$this->createForm(NoteType::class, $note);
                $form->handleRequest($request);
                  
              if ($form->isSubmitted() && $form->isValid()) {  
                     $data = $request->request->get('note');

                 $note->setProf($this->getUser())
                      ->setNote((int)$data['note'])
                      ->addStudent($std);         
                  $manager->persist($note);
                  $manager->flush();
                  $this->addFlash('success', 'the note has been added successfly !');
                  return $this->redirectToRoute('prof_synthese', ['id'=>$this->getUser()->getId()]);
                  }
                }}

                if ($hasNoted == false) {
               
                 
               
                   $note = new Note();
                $form=$this->createForm(NoteType::class, $note);
                $form->handleRequest($request);
                  
              if ($form->isSubmitted() && $form->isValid()) {  
    
                 $note->setProf($this->getUser())
                      ->addStudent($std);
                 ;         
                  $manager->persist($note);
                  $manager->flush();
                  $this->addFlash('success', 'the note has been added successfly !');
                  return $this->redirectToRoute('prof_synthese', ['id'=>$this->getUser()->getId()]);
                }
                
              
	  	         
              }
              return $this->render("prof/note.html.twig",[
                  'noteForm'=>$form->createView(),
                  'std'=>$std
               ]);
            }

     
}
