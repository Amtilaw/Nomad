<?php

namespace App\Repository;

use App\Entity\NmdFermeture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdFermeture|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdFermeture|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdFermeture[]    findAll()
 * @method NmdFermeture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdFermetureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdFermeture::class);
    }

    // /**
    //  * @return NmdFermeture[] Returns an array of NmdFermeture objects
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
    public function findOneBySomeField($value): ?NmdFermeture
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
