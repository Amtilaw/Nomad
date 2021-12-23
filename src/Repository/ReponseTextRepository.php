<?php

namespace App\Repository;

use App\Entity\ReponseText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseText|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseText|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseText[]    findAll()
 * @method ReponseText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseText::class);
    }

    // /**
    //  * @return ReponseText[] Returns an array of ReponseText objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReponseText
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
