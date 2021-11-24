<?php

namespace App\Repository;

use App\Entity\NmdOuverture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdOuverture|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdOuverture|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdOuverture[]    findAll()
 * @method NmdOuverture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdOuvertureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdOuverture::class);
    }

    // /**
    //  * @return NmdOuverture[] Returns an array of NmdOuverture objects
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
    public function findOneBySomeField($value): ?NmdOuverture
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
