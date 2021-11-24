<?php

namespace App\Repository;

use App\Entity\NmdRhManagement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdRhManagement|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdRhManagement|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdRhManagement[]    findAll()
 * @method NmdRhManagement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdRhManagementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdRhManagement::class);
    }

    // /**
    //  * @return NmdRhManagement[] Returns an array of NmdRhManagement objects
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
    public function findOneBySomeField($value): ?NmdRhManagement
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
