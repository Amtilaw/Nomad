<?php

namespace App\Repository;

use App\Entity\NmdQualityBonus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdQualityBonus|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdQualityBonus|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdQualityBonus[]    findAll()
 * @method NmdQualityBonus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdQualityBonusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdQualityBonus::class);
    }

    public function qualityByCourtierrrrStausOperator($status,$cpv,$percentage,$bonus_malus_id) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_quality_bonus` S 
                WHERE code_courtier='$cpv' 
                AND $percentage > S.`step_start` AND $percentage <= S.`step_end` 
                AND bonus_malus_query_id=$bonus_malus_id 
                AND sellstatus='$status'";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetch();

    }

    // /**
    //  * @return NmdQualityBonus[] Returns an array of NmdQualityBonus objects
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
    public function findOneBySomeField($value): ?NmdQualityBonus
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
