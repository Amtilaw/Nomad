<?php

namespace App\Repository;

use App\Entity\NmdObjectifClusters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdObjectifClusters|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdObjectifClusters|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdObjectifClusters[]    findAll()
 * @method NmdObjectifClusters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdObjectifClustersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdObjectifClusters::class);
    }

    // /**
    //  * @return NmdObjectifClusters[] Returns an array of NmdObjectifClusters objects
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
    public function findOneBySomeField($value): ?NmdObjectifClusters
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
