<?php

namespace App\Controller\Payement;

use App\Repository\PayementRepository;
use App\Repository\MonthRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Month;
use App\Form\MonthType;

/**
 * 
 * @IsGranted("ROLE_ADMIN")
 */
class MonthController extends AbstractController
{   
    /**
     * @Route("/admin/months", name="admin_months")
     * @param  MonthRepository $monthRepo [description]
     * @return [Response]                     [description]
     */
    public function months(MonthRepository $monthRepo): Response
    {
        return $this->render('admin/payement/months.html.twig', [
           'months'=>$monthRepo->findAll(),
        ]);
    }

	/**
	 * @Route("/admin/edit/month/{id}", name="admin_edit_month")
     * @Route("/admin/add/month", name="admin_add_month")
	 */
	public function month(Month $month=null, Request $request, EntityManagerInterface $em) : Response
	{ 
	    if ($month) {

		   $form = $this->createForm(MonthType::class, $month);
		   $form->handleRequest($request);

           if ($form->isSubmitted() && $form->isValid()) {
           	  $month->setName($form->get('name')->getData())
                    ->setOutputs($form->get('outputs')->getData())
                    ->setInputs($form->get('inputs')->getData())
           	  ;
              
        	  $em->persist($month);
        	  $em->flush();
        	  $this->addFlash("info", "Votre mois est bien modifiée !");
        	  return $this->redirectToRoute('admin_months');
           }
	    }else{
		   $month = new Month();
		   $form = $this->createForm(MonthType::class, $month);
		   $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {
        	 $em->persist($month);
        	 $em->flush();
        	 $this->addFlash("info", "Votre mois est bien ajouté !");
        	 return $this->redirectToRoute('admin_months');
           }
        }

		return $this->render('admin/payement/month.html.twig', [
         'isNew'=>$month->getId()?false: true,
         'monthForm'=>$form->createView()
		]);
	}
    
    /**
     * @Route("/admin/payement/month/remove/{id}", name="admin_remove_month")
     * @param  Moth                   $month [description]
     * @param  EntityManagerInterface $em    [description]
     * @return [Response]                        [description]
     */
	public function removeMonth(Month $month, EntityManagerInterface $em): Response
	{
		if (!$month) {
			throw new NotFoundException("Le mois n'est pas !", 1);			
		}
        $em->remove($month);
        $em->flush();
        $this->addFlash("info", "Votre mois est bien supprimer !");
        return $this->redirectToRoute('admin_add_month');
	}
}
