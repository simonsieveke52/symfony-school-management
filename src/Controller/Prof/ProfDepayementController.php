<?php

namespace App\Controller\Prof;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DepayementRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Prof;

/**
 * @IsGranted("ROLE_PROF")
 */
class ProfDepayementController extends AbstractController
{   
	/**
	 *@Route("/prof/payement/{id}", name="prof_depayement")
	 */
	public function index(Prof $prof, DepayementRepository $depayementRepo) : Response
	{  if (!$prof) {
		throw new NotFoundException("Prof n'est existe pas !", 1);
		
	}
		$this->denyAccessUnlessGranted('view', $prof);

		return $this->render('prof/depayement.html.twig', [
          'profDepayement'=>$depayementRepo->findByProf($prof),
		]);
	}
}
