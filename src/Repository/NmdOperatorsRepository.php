<?php

namespace App\Repository;

use App\Entity\NmdOperators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdOperators|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdOperators|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdOperators[]    findAll()
 * @method NmdOperators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdOperatorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdOperators::class);
    }

    // /**
    //  * @return NmdOperators[] Returns an array of NmdOperators objects
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
    public function findOneBySomeField($value): ?NmdOperators
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
