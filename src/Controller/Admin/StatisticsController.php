<?php

namespace App\Controller\Admin;

use App\Services\StatisticService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\PayementRepository;
use App\Repository\MonthRepository;
use App\Repository\DepayementRepository;
use App\Repository\ClasseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Month;
use App\Form\MonthType;



class StatisticsController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     * @Security("is_granted('ROLE_ADMIN')", message="Access Denied")
     * 
     */
    public function index(MonthRepository $monthRepo, PayementRepository $payementRepo, DepayementRepository $depayementRepo, ClasseRepository $classeRepo,  StatisticService $statsService): Response
    {

        $stats      = $statsService->getStats();



        $monthResults = [];
      $payements = 0;
      $depayements = 0;
      foreach ($monthRepo->findAll() as $month) {
 
        foreach ($month->getPayements() as $payement) {
          $payements += $payement->getPrice();
        }

        foreach ($month->getDepayements() as $depayement) {
          $depayements += $depayement->getPrice();
        }

        $monthResults[] = [
             'monthName'=>$month->getName(),
             'outputs'=>$depayements,
             'inputs'=>$payements
        ];
      }

      $classeResults = [];
      foreach ($classeRepo->findAll() as $classe) {
        $classeResults[]=[
               'classeName'=>$classe->getName(),
               'stdNumbers'=>count($classe->getStudents()),
               'profNumbers'=>count($classe->getProfs()),
               'payedStudents'=>$payementRepo->findStudentsByClasse($classe->getId()),
               'depayedProfs'=>$depayementRepo->findProfsByClasse($classe->getId())
        ];
      }
      

        return $this->render('admin/home.html.twig', [
            'stats'     => $stats,
            'months'=>$monthResults,
            'classes'=>$classeResults
          
        ]);
    }
}
