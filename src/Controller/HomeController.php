<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CourseRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CourseRepository $courseRepo)
    {
    	
        return $this->render('home/index.html.twig', [
            'courses'=>$courseRepo->findAll()
        ]);
    }
}
