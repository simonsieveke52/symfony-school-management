<?php

namespace App\Controller\Payement;

use App\Repository\PayementRepository;
use App\Repository\DepayementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Depayement;
use App\Entity\Prof;
use App\Form\DepayementType;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AddDepayementController extends AbstractController
{   
	/**
	 * @Route("/admin/edit/depayement/{id}", name="admin_edit_depayement")
	 */
	public function depayement(Depayement $depayement, Request $request, EntityManagerInterface $em) : Response
	{ 
	    if (!$depayement) {
        throw new Exception("Depayement not found", 1);
      }

		   $form = $this->createForm(DepayementType::class, $depayement);

		   $form->handleRequest($request);
           
           if ($form->isSubmitted() && $form->isValid()) {
           	  $depayement
                        ->setMonth($form->get('month')->gatDate())
                        ->setPrice($form->get('price')->gatDate())
           	  ;

        	  $em->persist($depayement);
        	  $em->flush();
        	  $this->addFlash("info", "Votre depaiement est bien modifiée !");
        	  return $this->redirectToRoute('admin_prof_depayement');
           }
	   


		return $this->render('admin/payement/depayement.html.twig', [
         'depayementForm'=>$form->createView()
		]);
	}

  /**
   * @Route("/admin/add/depayement/{id}", name="admin_add_depayement")
   */
  public function depayProf(Prof $prof, Request $request, EntityManagerInterface $em): Response
  {
       $depayement = new Depayement();
       $form = $this->createForm(DepayementType::class, $depayement);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $depayement->setCreatedAt(new \DateTime())
                       ->setProf($prof)
            ;
            $em->persist($depayement);
            $em->flush();
            $this->addFlash("info", "Votre depaiement est bien ajouté !");
            return $this->redirectToRoute('admin_prof_depayement');
       }
       return $this->render('admin/payement/depayement.html.twig', [
         'depayementForm'=>$form->createView()
    ]);
  }

    
    /**
     * @Route("/admin/payement/depayement/remove/{id}", name="admin_remove_depayement")
     * @param  EntityManagerInterface $em    [description]
     */
	public function removeRepayement(Depayement $depayement, EntityManagerInterface $em): Response
	{
		if (!$depayement) {
			throw new NotFoundException("Le depayement n'est pas !", 1);			
		}
        $em->remove($depayement);
        $em->flush();
        $this->addFlash("info", "Votre depaiement est bien supprimer !");
        return $this->redirectToRoute('admin_prof_depayement');
	}
}
