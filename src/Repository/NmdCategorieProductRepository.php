<?php

namespace App\Repository;

use App\Entity\NmdCategorieProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdCategorieProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdCategorieProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdCategorieProduct[]    findAll()
 * @method NmdCategorieProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdCategorieProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdCategorieProduct::class);
    }

    // /**
    //  * @return NmdCategorieProduct[] Returns an array of NmdCategorieProduct objects
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
    public function findOneBySomeField($value): ?NmdCategorieProduct
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
