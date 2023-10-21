<?php

namespace App\Controller\Std;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PayementRepository;
use App\Repository\MonthRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Student;
use App\Entity\Month;
use App\Entity\Payement;
use App\Form\PayementType;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @IsGranted("ROLE_USER")
 */
class StdPayementController extends AbstractController
{
	/**
	 * @Route("/student/payement/{id}", name="std_payement")
	 */
	
	public function index(Student $student, PayementRepository $payementRepo, MonthRepository $monthRepo):Response
	{ 
		if (!$student) {
			throw new NotFoundException("Etudiant n'est pas existe !", 1);			
		}
		$this->denyAccessUnlessGranted('view', $student->getUser());

		return $this->render('std_state/payement.html.twig', [
		  'months'=>$monthRepo->findAll(),
          'myPayements'=> $payementRepo->findByStudent($student),
          'id'=>$student->getId()
		]);
	}

    /**
	 * @Route("/student/edit/payement/{id}", name="student_edit_payement")
	 */
	public function payement(Payement $payement, PayementRepository $payementRepo, Request $request, EntityManagerInterface $em) : Response
	{ 
		$this->denyAccessUnlessGranted('edit', $this->getUser());

	    if (!$payement) {
	    	throw new Exception("payement not found !", 1);
	    }
		   $form = $this->createForm(PayementType::class, $payement);

		   $form->handleRequest($request);
           
           if ($form->isSubmitted() && $form->isValid()) {
           	  $payement->setStudent($this->getUser()->getStudent())
                    ->setPrice($form->get('price')->getData())
           	  ;

        	  $em->persist($payement);
        	  $em->flush();
        	  $this->addFlash("info", "Votre paiement est bien modifiée !");
        	  return $this->redirectToRoute('std_payement', ['id'=> $this->getUser()->getStudent()->getId()]);
           }

		return $this->render('std_state/add_payement.html.twig', [
         'payementForm'=>$form->createView()
		]);
	
}

   /**
    * @Route("/student/add/payement/{id}", name="student_add_payement")
    */
	public function payThisMoth(Month $month, Request $request, EntityManagerInterface $em) : Response
	{      
		$this->denyAccessUnlessGranted('edit', $this->getUser());
		
       if (!$month) {
       	throw new Exception("Month not found", 1);
       }
       
	   $payement = new Payement();
	   $form = $this->createForm(PayementType::class, $payement);
	   $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
            $payement->setCreatedAt(new \DateTime())
                     ->setStudent($this->getUser()->getStudent())
                     ->setMonth($month)
            ;
        	$em->persist($payement);
        	$em->flush();
        	$this->addFlash("info", "Votre paiement est bien ajouté !");
        	return $this->redirectToRoute('std_payement', ['id'=> $this->getUser()->getStudent()->getId()]);
       }

       return $this->render('std_state/add_payement.html.twig', [
         'payementForm'=>$form->createView()
		]);
	}

}
