<?php

namespace App\Repository;

use App\Entity\TargetList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TargetList|null find($id, $lockMode = null, $lockVersion = null)
 * @method TargetList|null findOneBy(array $criteria, array $orderBy = null)
 * @method TargetList[]    findAll()
 * @method TargetList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TargetListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TargetList::class);
    }

    // /**
    //  * @return TargetList[] Returns an array of TargetList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TargetList
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
