<?php

namespace App\Controller\Std;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Form\NewPassType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ResetPassType;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use App\Message\ResetPasswordEmailNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class StdSecurityController extends AbstractController
{
   /**
     * @Route("/student/login", name="app_user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, CacheInterface $cache): Response
    {
         $cache->delete("user-info");
         $cache->delete("user-notes");
         $cache->delete("user-courses");
         $cache->delete("user-per-info");

         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('std_security/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/student/logout", name="app_user_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

   /**
     * Permet de modifier le mot de passe
     *
     * @Route("/student/password-update", name="student_update_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager) {
        $passwordUpdate = new PasswordUpdate();
       
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())){
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('home');
            }
        }


        return $this->render('std_state/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Vérification de l'email
     * @Route("/account/confirmation/{token}", name="student_confirm_email")
     */
     public function confirmEmail(string $token=null, UserRepository $userRepo, EntityManagerInterface $manager){
        
         $user = $userRepo->findOneByActivationToken($token);
         if ($user) {
            $user->setActivationToken(null);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash("success", "Your email has been confirmed successfly ! Now complete your information please !");
            return $this->redirectToRoute('app_user_login');
         } else {
             $this->addFlash("danger", "Oops! something go wrong");
             return $this->redirectToRoute('home');
         }
         
    
     }
/**
 * @Route("/forgotten-password", name="app_forgotten_password")
 */
public function oubliPass(Request $request, UserRepository $userRepo, TokenGeneratorInterface $tokenGenerator, MessageBusInterface $bus
): Response
{
    // On initialise le formulaire
    $form = $this->createForm(ResetPassType::class);

    // On traite le formulaire
    $form->handleRequest($request);

    // Si le formulaire est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // On récupère les données
        $donnees = $form->getData();

        // On cherche un utilisateur ayant cet e-mail
        $user = $userRepo->findOneByEmail($donnees['email']);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On envoie une alerte disant que l'adresse e-mail est inconnue
            $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
            
            // On retourne sur la page de connexion
            return $this->redirectToRoute('app_user_login');
        }

        // On génère un token
        $token = $tokenGenerator->generateToken();

        // On essaie d'écrire le token en base de données
        try{

            $user->setResetToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        } catch (\Exception $e) {
            $this->addFlash('warning',"une erreur se produit réysser plus tard ");
            return $this->redirectToRoute('app_user_login');
        }

         $bus->dispatch(new ResetPasswordEmailNotification($user->getEmail()));

        $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');

        // On redirige vers la page de login
        return $this->redirectToRoute('app_user_login');
    }

    // On envoie le formulaire à la vue
    return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
}

/**
 * @Route("/reset-password/{token}", name="app_reset_password")
 */
public function resetPassword(Request $request, string $token=null, UserPasswordEncoderInterface $passwordEncoder)
{
    if (!$token) {
      throw new NOtFoundException("token n'est existe pas !", 1);
      
    }
    // On cherche un utilisateur avec le token donné
    $user = $this->getDoctrine()->getRepository(User::class)->findOneByResetToken($token);

    // Si l'utilisateur n'existe pas
    if ($user === null) {
        // On affiche une erreur
        $this->addFlash('danger', 'Token Inconnu');
        return $this->redirectToRoute('app_user_login');
    }

 
   $form = $this->createForm(NewPassType::class);
   $form->handleRequest($request);
   if ($form->isSubmitted() && $form->isValid()) {
          $user->setResetToken(null);
          $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
             $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
         $this->addFlash('success', 'Mot de passe mis à jour');

        // On redirige vers la page de connexion
        return $this->redirectToRoute('app_user_login');
  }
        // Si on n'a pas reçu les données, on affiche le formulaire
        return $this->render('security/reset_password.html.twig',[
            'passForm' => $form->createView()
        ]);
    

}
}
