<?php

namespace App\Repository;

use App\Entity\StdCv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StdCv|null find($id, $lockMode = null, $lockVersion = null)
 * @method StdCv|null findOneBy(array $criteria, array $orderBy = null)
 * @method StdCv[]    findAll()
 * @method StdCv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StdCvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StdCv::class);
    }

    // /**
    //  * @return StdCv[] Returns an array of StdCv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StdCv
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
