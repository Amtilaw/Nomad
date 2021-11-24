<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * retourne la hierarchie de le user y compris le user connecté
     */
    public function userLegacyIncluded($userId){
        $conn = $this->getEntityManager()->getConnection();
      
        $sql=" SELECT id FROM `user` WHERE id
        IN(SELECT id FROM `user` WHERE `parent` IN
        (SELECT id FROM `user` WHERE `parent` = '$userId')
        OR id = '$userId' OR parent = '$userId')";
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * retourne la hierarchie de le user, sans le user connecté
     */
    public function userLegacyExcluded($userId){
        $conn = $this->getEntityManager()->getConnection();
      
        $sql=" SELECT id FROM `user` WHERE id
        IN(SELECT id FROM `user` WHERE `parent` IN
        (SELECT id FROM `user` WHERE `parent` = '$userId')
        OR parent = '$userId')";
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function DirecteursForPartner($cpv,$sqlIsEnabled=""){

        $conn = $this->getEntityManager()->getConnection();

        $sql =

            " SELECT *
            
          FROM `user` 
            
          WHERE cpv_courtier_exploitant = ? 
          AND  roles LIKE '%ROLE_DIRECTOR%' 
            $sqlIsEnabled 

        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cpv);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function allManagersForDirecteur($directeur_id, $sqlIsEnabled=""){

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT
                *
            FROM
                `user` u
            WHERE
                u.`parent` = $directeur_id AND u.roles LIKE '%ROLE_MANAGER%' $sqlIsEnabled
        ";
        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function allVendeursForManager($parent_id, $sqlIsEnabled=""){

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT
                *
            FROM
                `user` u
            WHERE
                u.`parent` = $parent_id AND u.roles LIKE '%ROLE_SELLER%' $sqlIsEnabled
        ";
        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function GetCompanyChildrenOfUserConnected($userId){

        $conn = $this->getEntityManager()->getConnection();

        $sql = " SELECT id, `firstname`, `lastname`, `company`, roles, cpv_courtier_exploitant
                 FROM `user` 
                 WHERE parent = ?
                 AND roles LIKE '%ROLE_COMPANY%'
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $userId);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function GetParentNewUser_fromSFRView(){

        $conn = $this->getEntityManager()->getConnection();


        $sql =

            "  SELECT id, UPPER(`firstname`) as firstname, UPPER(`lastname`) as lastname , roles  
            FROM `user`
            WHERE `roles` LIKE '%SFR%' 
           ORDER BY lastname ASC
        ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function GetParentNewUser_fromCompany($roleChoice, $userCpv){

        $conn = $this->getEntityManager()->getConnection();

        if($roleChoice == "director"){

            $complt = " 
            WHERE `roles`
            LIKE '%ROLE_COMPANY%'
            AND `cpv_courtier_exploitant` = '$userCpv'
            AND `is_enabled` = '1' 
            ";
        }
        elseif($roleChoice == "manager"){

            $complt = " 
            WHERE `roles`
            LIKE '%ROLE_DIRECTOR%' 
            AND `cpv_courtier_exploitant` = '$userCpv'
            AND `is_enabled` = '1' 
            ";
        }

        elseif($roleChoice == "teleOperator"){

            $complt = " 
            
            WHERE (`roles` LIKE '%ROLE_SFR%' 
            OR (`roles` LIKE '%ROLE_DIRECTOR%' and `cpv_courtier_exploitant` = '$userCpv' 
                 and `is_enabled` = '1'  ))

            ";
        }

        elseif($roleChoice == "encadrant"){

            $complt = " 
            WHERE `roles`
            LIKE '%ROLE_COMPANY%'
            AND `cpv_courtier_exploitant` = '$userCpv'
            AND `is_enabled` = '1' 
            ";
        }

        else{

            $complt = " 
            WHERE `roles`
            LIKE '%ROLE_MANAGER%' 
            AND `cpv_courtier_exploitant` = '$userCpv'
            AND `is_enabled` = '1' 
            ";
        }

        $sql =

            "  SELECT id, UPPER(`firstname`) as firstname, UPPER(`lastname`) as lastname , roles  FROM `user`
        
           $complt

           ORDER BY lastname ASC
        ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function GetParentNewUser_fromDirectorManager($roleChoice, $userCpv , $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        if($roleChoice == "manager" || $roleChoice == "teleOperator" ){
            $complt = " '%ROLE_DIRECTOR%' ";

        }else{
            $complt = " '%ROLE_MANAGER%' ";
        }

        $sql = "   SELECT id, UPPER(`firstname`) as firstname, UPPER(`lastname`) as lastname, roles  FROM `user`
        WHERE `roles` LIKE $complt
        AND `cpv_courtier_exploitant` = ?

        AND id

        IN(SELECT id FROM `user` WHERE `parent` IN
         (SELECT id FROM `user` WHERE `parent` = ? ) OR id = ? OR parent = ?)
        AND `is_enabled` = '1'

        ORDER BY lastname ASC
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $userCpv);
        $stmt->bindValue(2, $userId);
        $stmt->bindValue(3, $userId);
        $stmt->bindValue(4, $userId);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function GetParentNewUser_fromSeller($userParentId , $userCpv){

        $conn = $this->getEntityManager()->getConnection();

        $sql =

            "  SELECT id, UPPER(`firstname`) as firstname, UPPER(`lastname`) as lastname , roles  
           FROM `user`
           WHERE `id` = ?
           AND `cpv_courtier_exploitant` = ?
           AND `is_enabled` = '1'

           ORDER BY lastname ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $userParentId);
        $stmt->bindValue(2, $userCpv);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function GetParentNewUser_ForAutoModification($userParentId){

        $conn = $this->getEntityManager()->getConnection();

        $sql =

            "  SELECT id, UPPER(`firstname`) as firstname, UPPER(`lastname`) as lastname , roles  
           FROM `user`
           WHERE `id` = ?
           AND `is_enabled` = '1'

           ORDER BY lastname ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $userParentId);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function GetCompanyOfUserConnectedByCpv($cpv){

        $conn = $this->getEntityManager()->getConnection();

        $sql = " SELECT id, `firstname`, `lastname`, `company`, roles, cpv_courtier_exploitant
                 FROM `user` 
                 WHERE cpv_courtier_exploitant = ?
                 AND roles LIKE '%ROLE_COMPANY%'
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cpv);
        return $stmt->executeQuery()->fetch();
    }

    public function UsersInfosForSellsView_complete($userCpv){

        $conn = $this->getEntityManager()->getConnection();

        if($userCpv != "all" && $userCpv != null ){
            $sql_cpvChoice = " AND cpv_courtier_exploitant = $userCpv ";
        }else{
            $sql_cpvChoice = " ";
        }

        $sql=" SELECT 

        id,roles,firstname,lastname

        FROM `user` 
        WHERE  ( roles LIKE '%ROLE_DIRECTOR%' OR roles LIKE '%ROLE_MANAGER%' )
        
        ".$sql_cpvChoice. "
            
        ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
