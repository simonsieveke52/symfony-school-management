<?php

namespace App\Controller\Payement;

use  Doctrine\ORM\EntityNotFoundException;
use  Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Payement;

class AdminVerifiePayement extends AbstractController
{
	/**
	 * @Route("/admin/payemenet/{id}/verify", name="admin_verify_payement")
	 */
	public function verifie(Payement $payement, EntityManagerInterface $em)
	{
		if (!$payement) {
			throw new EntityNotFoundException("le paieent n'est pas trouvé", 1);			
		}
     if ($payement->getVerified()) {
     	 $payement->setVerified(false);
     	 $em->persist($payement);
     }else{
     	$payement->setVerified(true);
     	$em->persist($payement);
     }

     $em->flush();
     return $this->redirectToRoute("admin_student_payement");

	}
}
