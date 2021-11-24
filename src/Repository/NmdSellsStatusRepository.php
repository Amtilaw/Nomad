<?php

namespace App\Repository;

use App\Entity\NmdSellsStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdSellsStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdSellsStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdSellsStatus[]    findAll()
 * @method NmdSellsStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdSellsStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdSellsStatus::class);
    }

    // /**
    //  * @return NmdSellsStatus[] Returns an array of NmdSellsStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NmdSellsStatus
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
