<?php

namespace App\Repository;

use App\Entity\NmdPointSteps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdPointSteps|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdPointSteps|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdPointSteps[]    findAll()
 * @method NmdPointSteps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdPointStepsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdPointSteps::class);
    }

    public function stepsByVolume($user_id,$volume,$month='') {
        $sql_second='';
        if($month!=''){$sql_second=" AND $month>=MONTH(step_start_at) AND $month<=MONTH(step_end_at) "; }
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_point_steps` S 
                WHERE $volume >= S.`step_start_value` AND $volume <= S.`step_end_value` AND S.user_id=$user_id $sql_second";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetch();

    }

    public function stepsByVolumeMax($user_id,$volume,$month='') {

        $sql_second='';
        if($month!=''){$sql_second=" AND $month>=MONTH(step_start_at) AND $month<=MONTH(step_end_at) "; }
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_point_steps` S 
                WHERE $volume > S.`step_end_value` 
                AND S.user_id=$user_id 
                $sql_second 
                ORDER BY id DESC 
                ";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetch();

    }

    public function stepSup($user_id,$month='') {

        $sql_second='';
        if($month!=''){$sql_second=" AND $month>=MONTH(step_start_at) AND $month<=MONTH(step_end_at) "; }

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_point_steps` WHERE user_id=$user_id $sql_second ORDER BY step_start_value DESC LIMIT 1 ";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetch();

    }
    
    public function stepsByVolumeCompany($user_id,$operatorId,$volume,$month='') {
        $sql_second='';
        if($month!=''){$sql_second=" AND $month>=MONTH(step_start_at) AND $month<=MONTH(step_end_at) "; }
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_point_steps` S 
                WHERE $volume >= S.`step_start_value` AND $volume <= S.`step_end_value` AND S.user_id=$user_id AND S.operator_id=$operatorId $sql_second";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetch();

    }
    public function stepSupCompany($user_id,$operatorId,$month='') {

        $sql_second='';
        if($month!=''){$sql_second=" AND $month>=MONTH(step_start_at) AND $month<=MONTH(step_end_at) "; }

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM `nmd_point_steps` WHERE user_id=$user_id AND operator_id=$operatorId $sql_second ORDER BY step_start_value DESC LIMIT 1 ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetch();

    }

    // /**
    //  * @return NmdPointSteps[] Returns an array of NmdPointSteps objects
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
    public function findOneBySomeField($value): ?NmdPointSteps
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
