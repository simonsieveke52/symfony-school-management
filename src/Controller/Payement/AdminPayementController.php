<?php

namespace App\Controller\Payement;

use App\Repository\PayementRepository;
use App\Repository\DepayementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/*
* @IsGranted('ROLE_ADMIN')
*/

class AdminPayementController extends AbstractController
{
    /**
     * @Route("/admin/payement/students", name="admin_student_payement")
     * @return Response
     */
    public function std(PayementRepository $payementRepo): Response
    {
        $payements = $payementRepo->findAll();
        return $this->render("admin/payement/std_payement.html.twig", [
            "payements" => $payements
        ]);
    }

    /**
     * @Route("/admin/depayement/profs", name="admin_prof_depayement")
     * @return Response
     */
    public function prof(DepayementRepository $depayementRepo): Response
    {
        $depayements = $depayementRepo->findAll();
        return $this->render("admin/payement/prof_depayement.html.twig", [
            "depayements" => $depayements
        ]);
    }
}
