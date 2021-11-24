<?php

namespace App\Repository;

use App\Entity\NmdPartner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdPartner|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdPartner|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdPartner[]    findAll()
 * @method NmdPartner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdPartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdPartner::class);
    }

    public function OperatorIdDetails($operatorId){

        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT * FROM `nmd_operators` WHERE `operator_id` = ?

        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $operatorId);

        return $stmt->executeQuery()->fetchOne();

    }

    public function partnersForSFR($parent_id=null){

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT
                *
            FROM
                `user` u
            WHERE u.roles LIKE '%ROLE_COMPANY%' AND u.is_enabled=1
        ";

        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function DirecteursForPartner($cpv){

        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT *
            
          FROM `user` 
            
          WHERE cpv_courtier_exploitant = ? 
          AND  roles LIKE '%ROLE_DIRECTOR%' 
          AND is_enabled = 1

        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cpv);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function managersForDirecteur($directeur_id){

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT
                *
            FROM
                `user` u
            WHERE
                u.`parent` = $directeur_id AND u.roles LIKE '%ROLE_MANAGER%' AND u.is_enabled=1
        ";
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function vendeursForManager($parent_id){

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT
                *
            FROM
                `user` u
            WHERE
                u.`parent` = $parent_id AND u.roles LIKE '%ROLE_SELLER%' AND u.is_enabled=1
        ";
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function partnerByCpv($cpv){

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            " SELECT *
                
            FROM `user` 
                
            WHERE cpv_courtier_exploitant = ? 

            AND  roles LIKE '%ROLE_COMPANY%' AND is_enabled = 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cpv);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetch();
    }

    public function selectAllPartnerInParner($responsable_email){

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT
                *
            FROM
                `nmd_partner` u 
        ";

        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function DirecteursForPartnerById($parent_id){

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            " SELECT *
                
            FROM `user` 
            WHERE parent=$parent_id
            AND  roles LIKE '%ROLE_DIRECTOR%' AND is_enabled = 1
        ";

        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
