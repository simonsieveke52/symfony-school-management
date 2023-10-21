<?php

namespace App\Controller\Prof;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnounceRepository;
use App\Repository\StudentRepository;
use App\Repository\ProfRepository;
use App\Entity\Prof;
use App\Entity\Note;
use App\Entity\Anounce;
use App\Form\AnounceType;
use App\Form\NoteType;
use App\Entity\Course;
use App\Form\CourseType;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\FileUploaderService;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @IsGranted("ROLE_PROF")
 */
class ProfController extends AbstractController
{
    /**
     * @Route("/prof", name="prof")
     *
     */
    public function index()
    {
        return $this->render('prof/index.html.twig', [
            
        ]);
    }

    /**
     * @Route("/prof/profile/{id}", name="prof_profile")
     *  @Security("is_granted('ROLE_PROF') and user === prof", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    public function profile(Prof $prof)
    {
      $this->denyAccessUnlessGranted('view', $prof);
    	return $this->render("prof/profile.html.twig",[
          'prof'=>$prof
    	]);
    }

    /**
     * @Route("/prof/course/add", name="prof_add_course")
     * @Security("is_granted('ROLE_PROF')", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    
    public function addCourse(Request $request, EntityManagerInterface $manager, FileUploaderService $fileUploader)
    {
           $course = new Course();
        $form=$this->createForm(CourseType::class, $course);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          
           /** @var UploadedFile $brochureFile */
           $brochureFile = $form['brochure']->getData();
           $pictureFile = $form['picture']->getData();

          if ($brochureFile) {
             $brochureFileName = $fileUploader->upload($brochureFile);
             $course->setBrochureFilename($brochureFileName);
          }

          $pictureFileName = $fileUploader->upload($pictureFile);
          $course->setPicture($pictureFileName);

          $course->setCreatedAt(new \DateTime())
                 ->setProf($this->getUser());

            $manager->persist($course);
            $manager->flush();
            $this->addFlash('success', 'the Course has been added successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
        }
    	return $this->render("prof/course.html.twig",[
    'courseForm'=>$form->createView()
    	]);
    }

   /**
    * @Route("/prof/cour/edit/{id}", name="prof_edit_course")
    * @Security("is_granted('ROLE_PROF') and user===course.prof", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
    */
    public function editCourse(Course $course, Request $request, EntityManagerInterface $manager, FileUploaderService $fileUploader)
    {
      $this->denyAccessUnlessGranted('view', $course->getProf());

       $form=$this->createForm(CourseType::class, $course);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          $data = $request->request;
          $course->setTitle($data->get('course')['title'])
                 ->setContent($data->get('course')['content']);
                   $brochureFile = $form['brochure']->getData();
                 
                if ($brochureFile) {
                   $brochureFileName = $fileUploader->upload($brochureFile);
                   $course->setBrochureFilename($brochureFileName);
                 }
                  $pictureFile = $form['picture']->getData();
                  $pictureFileName = $fileUploader->upload($pictureFile);
                  $course->setPicture($pictureFileName);
              

            $manager->persist($course);
            $manager->flush();
            $this->addFlash('success', 'the Course has been edited successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
        }
        return $this->render("prof/course.html.twig",[
    'courseForm'=>$form->createView()
      ]);
    }


    /**
     * @Route("/profile/course/remove/{id}", name="prof_remove_course")
     * @Security("is_granted('ROLE_PROF') and user === course.prof", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    
    public function removeCourse(Course $course, EntityManagerInterface $maneger)
    {
      $this->denyAccessUnlessGranted('edit', $course->getProf());

      $maneger->remove($course);
      $maneger->flush();
      $this->addFlash('success', 'the Course has been elimited successfly !');
      return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
    }
    /**
     * @Route("/prof/anounce/edit/{id}", name="prof_edit_anounce")
       * @Route("/prof/anounce/add", name="prof_add_anounce")
       * @IsGranted("ROLE_PROF")
     */
    public function anounce(Anounce $anounce=null, Request $request, EntityManagerInterface $manager, AnounceRepository $anounceRepo)
    {
        if ($anounce) {
      $this->denyAccessUnlessGranted('view', $$anounce->getProf());

           $form = $this->createForm(AnounceType::class, $anounce);
           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {  
                 $data = $request->request->get('anounce');
                   $anounce->setTitle($data['title'])
                           ->setContent($data['content']);
                   $manager->persist($anounce);
                   $manager->flush();
            $this->addFlash('success', 'the Anounce has been edited successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
           }
        }else{
          $anounce = new Anounce();
        $form = $this->createForm(AnounceType::class, $anounce);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {  
          
          $anounce->setCreatedAt(new \DateTime())
                 ->setProf($this->getUser());

            $manager->persist($anounce);
            $manager->flush();
            $this->addFlash('success', 'the Anounce has been added successfly !');
            return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
        }

        }
        return $this->render("prof/anounce.html.twig", [
        'anounceForm'=>$form->createView()
         ]);
    }
 
     /**
     * @Route("/profile/anounce/remove/{id}", name="prof_remove_anounce")
     * @Security("is_granted('ROLE_PROF') and user === anounce.prof", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    
    public function removeAnounce(Anounce $anounce, EntityManagerInterface $maneger)
    {
      $this->denyAccessUnlessGranted('view', $anounce->getProf());

      $maneger->remove($anounce);
      $maneger->flush();
      $this->addFlash('success', 'the Anounce has been elimited successfly !');
      return $this->redirectToRoute('prof_profile', ['id'=>$this->getUser()->getId()]);
    }

     /**
      * @Route("/prof/synthese/{id}", name="prof_synthese")
      * @Security("is_granted('ROLE_PROF') and user === prof", message="Ce profile ne vous appartient pas, vous ne pouvez pas la modifier")
      */
    public function synthese(Prof $prof)
    {
      $this->denyAccessUnlessGranted('view', $prof);
      
     
      return $this->render("prof/synthese.html.twig", [
        'prof'=>$prof
      ]);
    }
}
