<?php

namespace App\Controller\Std;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Repository\StdPerInfoRepository;
use App\Entity\StdPerInfo;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\StdCv;
use App\Form\StdCvType;
use App\Form\StdPerInfoType;
use App\Entity\StdChoice;
use App\Form\StdChoiceType;
use App\Entity\StdProfile;
use App\Form\StdProfileType;
use App\Entity\Student;
use App\Services\FileUploaderUserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class InscriptionController extends AbstractController
{
  
    /**
     * @Route("/student/info/edit/{id}", name="student_edit_info")
     * @Route("/student/info/add", name="student_add_info")
     */
    public function editInfo(StdPerInfo $stdInfo=null, EntityManagerInterface 
      $manager, Request $request, StudentRepository $stdRepo)
    {
      
        if ($stdInfo) {
           
           $this->denyAccessUnlessGranted('edit', $this->getUser());
           
              $finStd =  $stdRepo->findOneByStdperinfo($stdInfo);

              $form = $this->createForm(StdPerInfoType::class, $stdInfo);
              $form->handleRequest($request);
         
              if ($form->isSubmitted() && $form->isValid()) {
                   $finStd->setStdperinfo($stdInfo);
                   $manager->persist($stdInfo);
                   $manager->persist($finStd);
                   $manager->flush();
                   
                   $this->addFlash('message', 'Ok, Your Information edited successfly');
                   return $this->redirectToRoute('student_profile',['id'=>$finStd->getId()]);
              }
        } else {
            $stdInfo = new StdPerInfo();
            $finStd = $stdRepo->findOneByUser($this->getUser());
            $form = $this->createForm(StdPerInfoType::class, $stdInfo);
            $form->handleRequest($request);
       
            if ($form->isSubmitted() && $form->isValid()) {
             
            $data = $request->request->get('std_per_info');
         // dd($data, $request->request);
  $date = $data['age']['day'].'-'.$data['age']['month'].'-'.$data['age']['year'];
               
                 $stdInfo->setAge(date_create($date))
                         ->setCin($data['cin'])
                         ->setGendre($data['gendre'])
                         ->setCity($data['city'])
                         ->setPhone($data['phone'])
                  ;
                  $finStd->setStdperinfo($stdInfo);
                  $manager->persist($stdInfo);
                  $manager->persist($finStd);
                  $manager->flush();
                   
                  $this->addFlash('message', 'Ok, Your Information added successfly');
                  return $this->redirectToRoute('student_profile',['id'=>$finStd->getId()]);
              }
        }
        
        return $this->render('inscription/index.html.twig', [
           'stdForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("student/cursur-accademique/{id}", name="cv_student")
     */
    
    public function studentCv(Request $request, StdPerInfo $std, EntityManagerInterface $manager)
    {

      $finStd = $manager->getRepository(Student::class)->findOneByStdperinfo($std);
      if (!$finStd) {
        throw new NotFoundException("Etudiant non trouvé", 1);       
      }
      $this->denyAccessUnlessGranted('edit', $finStd->getUser());
    	if ($finStd->getStdcv()) {
        $stdCv = $finStd->getStdcv();

          $form = $this->createForm(StdCvType::class, $stdCv);
           $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {

              $day = $request->request->get('std_cv')['year']['day'];
              $month = $request->request->get('std_cv')['year']['month'];
              $year = $request->request->get('std_cv')['year']['year'];
              $date = $day.'-'.$month.'-'.$year;
                 $stdCv->setCity($request->request->get('std_cv')['city'])
                       ->setSchool($request->request->get('std_cv')['school'])
                       ->setYear(date_create($date))
                       ->setMoyen($request->request->get('std_cv')['moyen'])
                  ;     
                  $finStd->setStdcv($stdCv);
      
                  $manager->persist($stdCv);
                  $manager->persist($finStd);
                  $manager->flush();

                  $this->addFlash('success', 'Ok, Your Cv edited successfly!');
                  return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);      
             }

      }else{
            $stdCv = new StdCv();
      
             $form = $this->createForm(StdCvType::class, $stdCv);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
                 
                  $finStd->setStdcv($stdCv);
      
                  $manager->persist($stdCv);
                  $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('message', 'Ok, You are added successfly!');
                  return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);
      
             }
           }

        return $this->render('inscription/cv.html.twig', [
           'stdCvForm'=>$form->createView()
        ]);
    }

     /**
     * @IsGranted("ROLE_USER")
     * @Route("/student/choice/{id}", name="choice_student")
     */   
    public function studentChoice(Request $request, User $user, EntityManagerInterface $manager)
    {
     $this->denyAccessUnlessGranted('edit', $user);

      $finStd = $manager->getRepository(Student::class)->findOneByUser($user);

      if ($finStd->getStdchoice()) {
       $stdChoice = $finStd->getStdchoice();
         $form = $this->createForm(StdChoiceType::class, $stdChoice);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
            
                $choice = $request->request->get('std_choice')['bactype'];
                 $stdChoice->setBactype($choice);
                 $finStd->setStdchoice($stdChoice);
                  $manager->persist($stdChoice);
                 $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('success', 'Ok, Your Choice edited successfly!');
                 return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);
             }

      }else{
        $stdChoice = new StdChoice();
             $form = $this->createForm(StdChoiceType::class, $stdChoice);
             $form->handleRequest($request);
      
             if ($form->isSubmitted() && $form->isValid()) {
                  
                 $finStd->setStdchoice($stdChoice);
                  $manager->persist($stdChoice);
                 $manager->persist($finStd);
                  $manager->flush();
                  $this->addFlash('success', 'Ok, You are added successfly!');
                 return $this->redirectToRoute('student_profile', ['id'=>$finStd->getId()]);
             }
      }

        return $this->render('inscription/choice_student.html.twig', [
           'stdChoiceForm'=>$form->createView()
        ]);
    }



  }

     
