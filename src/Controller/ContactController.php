<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(EntityManagerInterface $manager, Request $request)
    {

        $contact = new Contact();
        $form=$this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {  
          
         $contact->setCreatedAt(new \DateTime());

          $manager->persist($contact);
          $manager->flush();
          $this->addFlash('success', 'the email has been sended successfly !');
          return $this->redirectToRoute('home');
        }
        return $this->render('contact/index.html.twig', [
            'contactForm'=>$form->createView()
        ]);
    }
}
