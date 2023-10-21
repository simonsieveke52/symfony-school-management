<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatisticService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats() {
        $users      = $this->getUsersCount();
        $profs        = $this->getProfsCount();
        $classes   = $this->getClassesCount();
        $courses   = $this->getCoursesCount();

        return compact('users', 'profs', 'classes', 'courses');
    }

    public function getUsersCount() {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getProfsCount() {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Prof a')->getSingleScalarResult();
    }

    public function getClassesCount() {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Classe b')->getSingleScalarResult();
    }
     public function getCoursesCount() {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Course c')->getSingleScalarResult();
    }

/*   public function getAdsStats($direction) {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c 
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }*/

}