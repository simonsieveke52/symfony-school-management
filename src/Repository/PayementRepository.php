<?php

namespace App\Repository;

use App\Entity\Payement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payement[]    findAll()
 * @method Payement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payement::class);
    }

  
    public function findStudentsByClasse($classeId)
    {
        return $this->createQueryBuilder('p')
            ->select('p as payement')
            ->join('p.student', 's')
            ->join('s.classe', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $classeId)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Payement
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
