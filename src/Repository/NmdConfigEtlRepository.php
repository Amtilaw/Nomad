<?php

namespace App\Repository;

use App\Entity\NmdConfigEtl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConfigEtl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigEtl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigEtl[]    findAll()
 * @method ConfigEtl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdConfigEtlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdConfigEtl::class);
    }

    // /**
    //  * @return ConfigEtl[] Returns an array of ConfigEtl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConfigEtl
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
