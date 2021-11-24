<?php

namespace App\Repository;

use App\Entity\NmdRemunerationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdRemunerationStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdRemunerationStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdRemunerationStatus[]    findAll()
 * @method NmdRemunerationStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdRemunerationStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdRemunerationStatus::class);
    }

    // /**
    //  * @return NmdRemunerationStatus[] Returns an array of NmdRemunerationStatus objects
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
    public function findOneBySomeField($value): ?NmdRemunerationStatus
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
