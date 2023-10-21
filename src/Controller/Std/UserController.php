<?php

namespace App\Controller\Std;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\StdProfile;
use App\Entity\Student;
use App\Services\FileUploaderUserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserController extends AbstractController
{
      /**
     * @Route("/student/inscription", name="inscription")
     */
    public function index(Request $request, EntityManagerInterface $manager
    , UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer,
    TokenGeneratorInterface $tokenGenerator, FileUploaderUserService $fileUploader)
    {

              $user = new User();
              $finStd = new Student();
              $stdProfile = new StdProfile();

              $stdProfile->setState('NOT VERIFIED')
                           ->setNote(0);
              $form = $this->createForm(UserType::class, $user);
              $form->handleRequest($request);
               
              if ($form->isSubmitted() && $form->isValid()) {
                  
                   $pictureFile = $form['picture']->getData();
                   $pictureFileName = $fileUploader->upload($pictureFile);
                   $user->setPicture($pictureFileName);
              

                   $token =$tokenGenerator->generateToken();
                   $data = $request->request->get('user');
                   $user->setPassword(
                           $passwordEncoder->encodePassword(
                           $user,
                           $form->get('password')->getData()))
                        ->setActivationToken($token)
                   ;    

                   $finStd->setUser($user);
                   $finStd->setProfile($stdProfile);
                  
                 
                  $manager->persist($user);
                  $manager->persist($finStd);
                  $manager->persist($stdProfile);
                  $manager->flush();
                   
                  $this->addFlash('success', 'Ok, You are added successfly, Please Verify your email to continue !');
                  return $this->redirectToRoute('home');
              }
            return $this->render('inscription/index.html.twig', [
             'stdForm'=>$form->createView()
            ]);
    }

       /**
       * @Route("/student/inscription/edit/{id}", name="inscription_edit")
      */
    public function editInscription(User $user, Request $request, 
      EntityManagerInterface $manager, StudentRepository $finstdRepo
      , UserPasswordEncoderInterface $passwordEncoder,
    UserRepository $stdRepo, \Swift_Mailer $mailer, TokenGeneratorInterface$tokenGenerator, FileUploaderUserService $fileUploader)
    {
    	 $this->denyAccessUnlessGranted('edit', $user);

              $finStd =  $finstdRepo->findOneByUser($user);
       
              $form = $this->createForm(UserType::class, $user);
              $form->handleRequest($request);
       
              if ($form->isSubmitted() && $form->isValid()) {
        
                   $data = $request->request->get('user');
                    $token =$tokenGenerator->generateToken();
  
                   $user->setPassword(
                           $passwordEncoder->encodePassword(
                           $user,
                           $form->get('password')->getData()))
                        ->setName($data['name'])
                        ->setActivationToken($token)
                        ->setEmail($data['email'])
                   ;

                  $pictureFile = $form['picture']->getData();
                   $pictureFileName = $fileUploader->upload($pictureFile);
                   $user->setPicture($pictureFileName);


                  $finStd->setUser($user);
                  $manager->persist($user);
                  $manager->persist($finStd);
                  $manager->flush();
                   
                  $this->addFlash('success', 'Ok, Your Information edited successfly !');
                 return $this->redirectToRoute('app_user_logout');                
              }
           
        return $this->render('inscription/index.html.twig', [
           'stdForm'=>$form->createView()
        ]);
    }

}
