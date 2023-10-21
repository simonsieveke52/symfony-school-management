<?php

namespace App\Controller\Std;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\NoteRepository;
use App\Repository\StdChoiceRepository;
use App\Repository\ProfRepository;
use App\Repository\CourseRepository;
use App\Entity\Student;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class StdStateController extends AbstractController
{
   /**
    * @Route("/student/coures-annonces/{id}", name="student_courses_anounces")
    * Security("is_granted('ROLE_USER') and user === std.user", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
    */
    public function myCoursesAndAnounces(Student $std, CourseRepository $courseRepo, ProfRepository $profRepo){
       $this->denyAccessUnlessGranted('view', $std->getUser()); 
      return $this->render('std_state/profile.html.twig',[
        'myclasse'=>$std->getClasse()
      ]);
   }

    /**
     * @Route("/student/profile/{id}", name="student_profile")
     * Security("is_granted('ROLE_USER') and user === std.user", message="Ce profile ne vous appartient pas, vous ne pouvez pas le modifier")
     */
    public function index(Student $std)
    {     
       $this->denyAccessUnlessGranted('view', $std->getUser());
        return $this->render('std_state/index.html.twig', [
          'user'=>$std->getUser(),
        'stdInfo'=>$std->getStdperinfo(),
        'stdProfile'=> $std->getProfile(),
        'stdChoice'=>$std->getStdchoice(),
        'stdCv'=>$std->getStdCv() 

        ]);
    }

   /**
    * @Route("/student/notes/{id}", name="student_note")
    * Security("is_granted('ROLE_USER') and user === student.user", message="Ce profile ne vous appartient pas, vous ne pouvez pas le modifier")
    */
 public function notes(Student $student, NoteRepository $noteRepo)
 {     
        $this->denyAccessUnlessGranted('view', $student->getUser());

         $notes = [];
         $moyen = 0; 
      if ($student->getProfile()->getState() == "ACCEPTED" ) {
       foreach ($student->getClasse()->getProfs() as $prof) {
         $note = $noteRepo->findOneByProf($prof);
         if ($note) {
            $notes[] = [
           'note'=>(int)$note->getNote(),
           'matter'=>$note->getProf()->getMatter()
           ];
         }
        
        }
      
        if (! count($notes) == 0) {
            
       foreach ($notes as $note) {
         $moyen = $moyen +$note['note'];
       }
       $moyen = $moyen/count($notes);
        } 
       
      }
         
         
    
       return $this->render("std_state/notes.html.twig",[
        'notes'=>$notes,
        'total'=>$moyen
        ]);
 }

}
