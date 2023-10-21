<?php

namespace App\Controller\Prof;

use App\Form\NewPassType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Form\ResetPassType;
use Symfony\Component\Form\FormError;
use App\Entity\Prof;
use App\Repository\ProfRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfSecurityController extends AbstractController
{
    /**
     * @Route("/prof/login", name="app_prof_login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
          if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }
            $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
           $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('prof_security/index.html.twig',[
            'last_username' => $lastUsername, 'error' => $error
        ]);
    }

   
   /**
    * @Route("/prof/logout", name="app_prof_logout")
    */
    public function logout()
    {
    	throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/prof/password-update", name="prof_update_password")
     * @IsGranted("ROLE_PROF")
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


        return $this->render('prof/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

   /**
   * @Route("/account/forgotten-password", name="app_prof_forgotten_password")
   */
public function oubliPass(Request $request, ProfRepository $userRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
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
            return $this->redirectToRoute('app_prof_login');
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
            $this->addFlash('warning', $e->getsuccess());
            return $this->redirectToRoute('app_prof_login');
        }

        // On génère l'URL de réinitialisation de mot de passe
        $url = $this->generateUrl('app_prof_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

        // On génère l'e-mail
        $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('norpely@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site NajmiAccademy. Veuillez cliquer sur le lien suivant : " . $url,
                'text/html'
            )
        ;

        // On envoie l'e-mail
        $mailer->send($message);

        // On crée le success flash de confirmation
        $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');

        // On redirige vers la page de login
        return $this->redirectToRoute('app_prof_login');
    }

    // On envoie le formulaire à la vue
    return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
}

/**
 * @Route("/account/reset_pass/{token}", name="app_prof_reset_password")
 */
public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
{
    // On cherche un utilisateur avec le token donné
    $user = $this->getDoctrine()->getRepository(Prof::class)->findOneByResetToken($token);

    // Si l'utilisateur n'existe pas
    if ($user === null) {
        // On affiche une erreur
        $this->addFlash('danger', 'Token Inconnu');
        return $this->redirectToRoute('app_prof_login');
    }

    // Si le formulaire est envoyé en méthode post
  $form = $this->createForm(NewPassType::class);
   $form->handleRequest($request);

   if ($form->isSubmitted() && $form->isValid()) {
          $user->setResetToken(null);
        //  dd($request->request, $form->get('plainPassword')->getData());
          $user->setPassword($passwordEncoder->encodePassword($user,
            $form->get('plainPassword')->getData()));
    $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
         $this->addFlash('success', 'Mot de passe mis à jour');

        // On redirige vers la page de connexion
        return $this->redirectToRoute('app_prof_login');
  }
        // Si on n'a pas reçu les données, on affiche le formulaire
        return $this->render('security/reset_password.html.twig',[
            'passForm' => $form->createView()
        ]);


 }   
}
