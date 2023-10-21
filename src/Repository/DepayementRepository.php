<?php

namespace App\Repository;

use App\Entity\Depayement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depayement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depayement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depayement[]    findAll()
 * @method Depayement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepayementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depayement::class);
    }

    // /**
    //  * @return Depayement[] Returns an array of Depayement objects
    //  */
    
    public function findProfsByClasse($classeId)
    {
        return $this->createQueryBuilder('d')
            ->select('d as depayement')
            ->join('d.prof', 'p')
            ->join('p.classes', 'c')
            ->groupBy('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $classeId)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Depayement
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
