<?php

namespace App\Controller\Admin;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;
use App\Repository\ClasseRepository;
use App\Form\StdProfileType;
use App\Form\ProfType;
use App\Form\ClasseFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Prof;
use App\Entity\Student;
use App\Entity\Classe;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/students", name="admin_students")
     */
    public function index(Request $request,StudentRepository $stdRepo,  PaginatorInterface $paginator)
    {
       $students = $paginator->paginate(
    $stdRepo->findAll(), // Requête contenant les données à paginer (ici nos articles)
    $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
    4 // Nombre de résultats par page
);
      
        return $this->render('admin/students.html.twig', [
           'students'=>$students
        ]);
    }
    
    /**
     * @Route("/admin/student/profile/edit/{id}", name="admin_edit_profile_student")
     */
    public function editStudentProfile(Student $std, Request $request, EntityManagerInterface $manager, ClasseRepository $classeRepo)
    {
     
              $stdProfile = $std->getProfile();
                
              $form = $this->createForm(StdProfileType::class, $stdProfile);
              $form->handleRequest($request);
            
              if ($form->isSubmitted() && $form->isValid()) {
                $data = $request->request->get('std_profile');
                 //dd($classeRepo->findOneById((int)$data['classe']));
              $stdProfile->setState($data['state'])
                         ->setNote($data['note'])
                      ;
                 
                  $std->setProfile($stdProfile);
                  $manager->persist($std);

                   $manager->flush();
                   
                   $this->addFlash('success', 'Ok, User edited successfly');
                   return $this->redirectToRoute('admin_students');
              }

              return $this->render("admin/edit-student.html.twig",[
        'profileForm'=>$form->createView(),
        'info'=>$std->getStdperinfo(),
        'user'=>$std->getUser()
              ]);
    }



    /**
     * @Route("/admin/student/delete/{id}", name="admin_delete_student")
     */
    public function deleteStudent(Student $std,EntityManagerInterface $manager)
    {               
                  $manager->remove($std);

                   $manager->flush();
                   
                   $this->addFlash('success', 'Ok, User removed successfly');
                   return $this->redirectToRoute('admin_students');
    }

     

   /**
    * @Route("/admin/prof/new", name="admin_new_prof")
    * @Route("/admin/prof/edit/{id}", name="admin_edit_prof")
    */
    public function newProf(Prof $prof=null, Request $request, EntityManagerInterface $manager, ClasseRepository $classes, UserPasswordEncoderInterface $passwordEncoder) 
    {
        if ($prof) {
           $form=$this->createForm(ProfType::class, $prof);
           $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          
         $prof->setPassword($passwordEncoder->encodePassword($prof, $form->get('password')->getData()));

          $manager->persist($prof);
          $manager->flush();
          $this->addFlash('success', 'the prof has been edited successfly !');
          return $this->redirectToRoute('admin_profs');
        }
        } else {
          $prof = new Prof();
        $form=$this->createForm(ProfType::class, $prof);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          
         $prof->setPassword($passwordEncoder->encodePassword($prof, $form->get('password')->getData()));

          $manager->persist($prof);
          $manager->flush();
          $this->addFlash('success', 'the prof has been added successfly !');
          return $this->redirectToRoute('admin_profs');
        }
        }
        
       
        return $this->render('admin/prof.html.twig',[
       'profForm'=>$form->createView()
      ]);
     }

  /**
   * @Route("/admin/prof/remove/{id}", name="admin_remove_prof")
   */

 public function removeProf(Prof $prof, EntityManagerInterface $manager)
 {
      $manager->remove($prof);
      $manager->flush();
       $this->addFlash('success', 'the prof has been elimited successfly !');
          return $this->redirectToRoute('admin_profs');

 }

     /**
      * @Route("/admin/profs", name="admin_profs")
      */
     
     public function profs(Request $request,ProfRepository $profRepo, PaginatorInterface $paginator){
        $profs = $paginator->paginate(
          $profRepo->findAll(),
          $request->query->getInt('page', 1),
          4);
       return $this->render('admin/profs.html.twig', [
           'profs'=>$profs
       ]);
     }

     /**
      * @Route("/admin/classes", name="admin_classes")
      */
     
     public function classes(Request $request, ClasseRepository $classeRepo, PaginatorInterface $paginator ){
         $classes = $paginator->paginate(
          $classeRepo->findAll() ,
          $request->query->getInt('page', 1),
          4);
        
       return $this->render('admin/classes.html.twig', [
          'classes'=>$classes
       ]);
     }
     
     /**
      * @Route("/admin/classe/new", name="admin_add_class")
      * @Route("/admin/classe/edit/{id}", name="admin_edit_class")
      */
     public function editClass(Classe $classe = null, Request $request, EntityManagerInterface $manager, ClasseRepository $classes, ProfRepository $profRepo, StudentRepository $stdRepo) 
    {
        if ($classe) {
            $form=$this->createForm(ClasseFormType::class, $classe);
            $form->handleRequest($request);  
   
        if ($form->isSubmitted() && $form->isValid()) {  
          $data = $request->request->get('classe_form');
         
           $classe->setName($data['name']);
           
              foreach ($classe->getStudents() as $std) {
                $std->setClasse($classe);
               $manager->persist($std);
              }
                foreach ($classe->getProfs() as $prof) {
                $prof->addClass($classe);
               $manager->persist($prof);
              }
           //  dd(count($classe->getStudents()));
                 
          $manager->persist($classe);

          $manager->flush();
          $this->addFlash('success', 'the classe has been edited successfly !');
          return $this->redirectToRoute('admin_classes');
        }
        } else {
          $classe= new Classe();
          $form=$this->createForm(ClasseFormType::class, $classe);
          $form->handleRequest($request);  
          if ($form->isSubmitted() && $form->isValid()) { 
            $data = $request->request->get('classe_form');

              foreach ($classe->getStudents() as $std) {
                $std->setClasse($classe);
               $manager->persist($std);
              }
               foreach ($classe->getProfs() as $prof) {
                $prof->addClass($classe);
               $manager->persist($prof);
              }
            
             $manager->persist($classe);
             $manager->flush();
             $this->addFlash('success', 'the classe has been added successfly !');
             return $this->redirectToRoute('admin_classes');
          }   

        }
        
        
      return $this->render("admin/edit-classe.html.twig",[
          'classForm'=>$form->createView()
      ]);
     }

     /**
      * @Route("/admin/classe/remove/{id}", name="admin_remove_class")
      */
     public function remeveClasse(Classe $class, EntityManagerInterface $repo, ClasseRepository $classeRepo){
      $repo->remove($class);
      $repo->flush();
      return $this->render("admin/classes.html.twig",[
   'classes'=>$classeRepo->findAll()
      ]);
     }
}