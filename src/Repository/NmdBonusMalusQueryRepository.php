<?php

namespace App\Repository;

use App\Entity\NmdBonusMalusQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdBonusMalusQuery|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdBonusMalusQuery|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdBonusMalusQuery[]    findAll()
 * @method NmdBonusMalusQuery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdBonusMalusQueryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdBonusMalusQuery::class);
    }

    public function executeRequestBonusMalus($sql,$user_id,$month,$year,$operator_id)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql=str_replace('$user_id',$user_id,$sql);
        $sql=str_replace('$year',$year,$sql);
        $sql=str_replace('$month',$month,$sql);
        $sql=str_replace('$operator_id',$operator_id,$sql);


        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    // /**
    //  * @return NmdBonusMalusQuery[] Returns an array of NmdBonusMalusQuery objects
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
    public function findOneBySomeField($value): ?NmdBonusMalusQuery
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
