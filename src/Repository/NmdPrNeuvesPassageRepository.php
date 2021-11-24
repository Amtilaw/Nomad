<?php

namespace App\Repository;

use App\Entity\NmdPrNeuvesPassage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdPrNeuvesPassage|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdPrNeuvesPassage|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdPrNeuvesPassage[]    findAll()
 * @method NmdPrNeuvesPassage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdPrNeuvesPassageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdPrNeuvesPassage::class);
    }

    //-------------------------------    V2    -------------------------------------

    public function totalNbLogtLForPartner($cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.id>0 
                $sql_second    
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalNbLogtLast8Weeks($cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, MAX(sem_livraison) as sem_livraison , WEEK(jour_livraison,1) as week, YEAR(jour_livraison) as annee , SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.id>0 
                $sql_second 
                GROUP BY WEEK(jour_livraison,1) 
                ORDER BY WEEK(jour_livraison,1) DESC, YEAR(jour_livraison) DESC
                LIMIT 8          
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtClustersByWeek($weekChoice, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl , C.code_cluster, C.lbl_cluster as libelle_cluster , SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtDatesByWeek($weekChoice, $cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total , jour_livraison , SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                $sql_second 
                GROUP BY  DATE(jour_livraison) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeek($weekChoice,$codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  C.ville 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeek($weekChoice,$codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , C.nom_voie , SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeek($weekChoice,$codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie , SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function totalNbLogtLast8WeeksDirector($director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  , MAX(sem_livraison) as sem_livraison  , WEEK(jour_livraison,1) as week, YEAR(jour_livraison) as annee 
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sql_second 
                GROUP BY WEEK(jour_livraison,1) 
                ORDER BY WEEK(jour_livraison,1) DESC, YEAR(jour_livraison) DESC
                LIMIT 8 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtClustersByWeekDirector($weekChoice, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtDatesByWeekDirector($weekChoice, $director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  , C.jour_livraison  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                GROUP BY  DATE(jour_livraison) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekDirector($weekChoice,$codeCluster, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                GROUP BY  C.ville 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekDirector($weekChoice,$codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekDirector($weekChoice,$codeCluster,$ville,$nom_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function totalNbLogtLast8WeeksManager($manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected , MAX(sem_livraison) as sem_livraison  , WEEK(jour_livraison) as week, YEAR(jour_livraison) as annee 
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sql_second 
                GROUP BY WEEK(jour_livraison) 
                ORDER BY WEEK(jour_livraison) DESC, YEAR(jour_livraison) DESC
                LIMIT 8 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtClustersByWeekManager($weekChoice, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtDatesByWeekManager($weekChoice, $manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  , C.jour_livraison  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sql_second 
                GROUP BY DATE(jour_livraison) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekManager($weekChoice,$codeCluster, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY  C.ville 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekManager($weekChoice,$codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekManager($weekChoice,$codeCluster,$ville,$nom_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logt ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logt ELSE 0 END) AS totalPrisesaffected  ,SUM(`nb_fyr_adsl`) as total_adsl ,SUM(`nb_fyr_mob_multi_adsl`) as total_mob_adsl, C.ville, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function affectPrisesSfrOrCompany($cpv=null, $weekChoice, $userSelected,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "UPDATE `nmd_pr_neuves_passage` 
                SET user_id='$userSelected' , is_affected=1, affected_at=NOW(), affected_by='$affected_by', manager_id='$manager_id', director_id='$director_id', beneficiary_roles='$beneficiary_roles'  
                WHERE `sem_livraison` = '$weekChoice' 
                $sql_second 
                $sqlJourLivraison
                ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery();

    }

    public function maxDateNbLogtLast8Weeks($cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.id>0 
                $sql_second 
                GROUP BY WEEK(jour_livraison,1) 
                ORDER BY WEEK(jour_livraison,1) DESC, YEAR(jour_livraison) DESC
                LIMIT 8          
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtClustersByWeek($weekChoice, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtDatesByWeek($weekChoice, $cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                $sql_second 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeek($weekChoice,$codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeek($weekChoice,$codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeek($weekChoice,$codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function maxDateNbLogtLast8WeeksDirector($director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sql_second 
                GROUP BY WEEK(jour_livraison,1) 
                ORDER BY WEEK(jour_livraison,1) DESC, YEAR(jour_livraison) DESC
                LIMIT 8 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtClustersByWeekDirector($weekChoice, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtDatesByWeekDirector($weekChoice, $director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sql_second 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekDirector($weekChoice,$codeCluster, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekDirector($weekChoice,$codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekDirector($weekChoice,$codeCluster,$ville,$nom_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function maxDateNbLogtLast8WeeksManager($manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sql_second 
                GROUP BY WEEK(jour_livraison) 
                ORDER BY WEEK(jour_livraison) DESC, YEAR(jour_livraison) DESC
                LIMIT 8 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtClustersByWeekManager($weekChoice, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtDatesByWeekManager($weekChoice, $manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sql_second 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekManager($weekChoice,$codeCluster, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekManager($weekChoice,$codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekManager($weekChoice,$codeCluster,$ville,$nom_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C 
                WHERE `sem_livraison` = '$weekChoice' 
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function listOfUsersAffected($cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, U.firstname, U.lastname  
                FROM `nmd_pr_neuves_passage` C 
                LEFT JOIN user U ON U.id = C.user_id 
                WHERE C.id>0 
                AND C.user_id is not null 
                $sql_second 
                GROUP BY C.user_id         
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function listOfUsersAffectedDirector($director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, U.firstname, U.lastname  
                FROM `nmd_pr_neuves_passage` C 
                LEFT JOIN user U ON U.id = C.user_id 
                WHERE C.id>0 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                AND C.user_id is not null 
                $sql_second 
                GROUP BY C.user_id         
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function listOfUsersAffectedManager($manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, U.firstname, U.lastname  
                FROM `nmd_pr_neuves_passage` C 
                LEFT JOIN user U ON U.id = C.user_id 
                WHERE C.id>0 
                AND C.user_id is not null 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                      
                $sql_second 
                GROUP BY C.user_id         
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    /* -- Prises existent dans cbl -- */

    public function totalNbLogtClustersByWeekTauxPenetration($cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total , C.code_cluster, C.lbl_cluster as libelle_cluster
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekTauxPenetration($codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total , C.ville, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  C.ville 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekTauxPenetration($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total , C.ville, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F  
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekTauxPenetration($codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND C.cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total , C.ville, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie   
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalPrisesRecusClustersByWeekTauxPenetration($cpv=null,$code_cluster, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total
                FROM `nmd_pr_neuves_passage` C 
                WHERE  C.code_cluster='$code_cluster'    
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusVillesByClusterWeekTauxPenetration($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total  
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.ville = '$ville'  
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusRuesByVilleClusterWeekTauxPenetration($codeCluster,$ville,$nomVoie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total  
                FROM `nmd_pr_neuves_passage` C   
                WHERE  C.nom_voie = '$nomVoie'  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusNumRuesByRueVilleClusterWeekTauxPenetration($codeCluster,$ville,$nom_voie,$numr_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND C.cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total    
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.numr_voie = '$numr_voie'   
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function groupByCblClustersByWeekTauxPenetration($cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total , MONTH(F.date_vente_valid_b) as month_date_vente_valid_b, YEAR(F.date_vente_valid_b) as year_date_vente_valid_b 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY MONTH(F.date_vente_valid_b)
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function groupByCblVillesByClusterWeekTauxPenetration($codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total , MONTH(F.date_vente_valid_b) as month_date_vente_valid_b, YEAR(F.date_vente_valid_b) as year_date_vente_valid_b 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  MONTH(F.date_vente_valid_b)  
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function groupByCblRuesByVilleClusterWeekTauxPenetration($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total , MONTH(F.date_vente_valid_b) as month_date_vente_valid_b, YEAR(F.date_vente_valid_b) as year_date_vente_valid_b 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F  
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY MONTH(F.date_vente_valid_b) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function groupByCblNumRuesByRueVilleClusterWeekTauxPenetration($codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND C.cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total , MONTH(F.date_vente_valid_b) as month_date_vente_valid_b, YEAR(F.date_vente_valid_b) as year_date_vente_valid_b   
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY MONTH(F.date_vente_valid_b) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalPrisesRecusClustersByWeekTauxPenetrationByMonth($cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total
                FROM `nmd_pr_neuves_passage` C 
                WHERE  C.id>0    
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusVillesByClusterWeekTauxPenetrationByMonth($codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total  
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.id>0     
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusRuesByVilleClusterWeekTauxPenetrationByMonth($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total  
                FROM `nmd_pr_neuves_passage` C   
                WHERE  C.id>0    
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusNumRuesByRueVilleClusterWeekTauxPenetrationByMonth($codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND C.cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total    
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.id>0      
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }


    public function totalNbLogtClustersByWeekDirectorTauxPenetration( $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total, C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc   
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekDirectorTauxPenetration($codeCluster, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total, C.ville, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                GROUP BY  C.ville 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekDirectorTauxPenetration($codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total, C.ville, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekDirectorTauxPenetration($codeCluster,$ville,$nom_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total, C.ville, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie 
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalPrisesRecusClustersByWeekDirectorTauxPenetration( $director_id,$code_cluster, $sqlJourLivraison='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total
                FROM `nmd_pr_neuves_passage` C
                WHERE  C.code_cluster='$code_cluster'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusVillesByClusterWeekDirectorTauxPenetration($codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total 
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.ville = '$ville'   
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusRuesByVilleClusterWeekDirectorTauxPenetration($codeCluster,$ville,$nomVoie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total
                FROM `nmd_pr_neuves_passage` C 
                WHERE  C.nom_voie = '$nomVoie'  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusNumRuesByRueVilleClusterWeekDirectorTauxPenetration($codeCluster,$ville,$nom_voie,$numr_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total 
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.numr_voie = '$numr_voie'  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function totalNbLogtClustersByWeekManagerTauxPenetration($manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total, C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc    
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekManagerTauxPenetration($codeCluster, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id)  as total, C.ville, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc   
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY  C.ville 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekManagerTauxPenetration($codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total, C.ville, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F  
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekManagerTauxPenetration($codeCluster,$ville,$nom_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(F.id) as total, C.ville, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie 
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalPrisesRecusClustersByWeekManagerTauxPenetration($manager_id,$code_cluster, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total 
                FROM `nmd_pr_neuves_passage` C 
                WHERE  C.code_cluster='$code_cluster'     
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusVillesByClusterWeekManagerTauxPenetration($codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`)  as total 
                FROM `nmd_pr_neuves_passage` C 
                WHERE  C.ville = '$ville'     
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusRuesByVilleClusterWeekManagerTauxPenetration($codeCluster,$ville,$nomVoie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total, 
                FROM `nmd_pr_neuves_passage` C 
                WHERE  C.nom_voie = '$nomVoie'   
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function totalPrisesRecusNumRuesByRueVilleClusterWeekManagerTauxPenetration($codeCluster,$ville,$nom_voie,$numr_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total 
                FROM `nmd_pr_neuves_passage` C  
                WHERE  C.numr_voie = '$numr_voie'   
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function maxDateNbLogtClustersByWeekTauxPenetration($cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F  
                WHERE  C.`code_hexc` = F.cbl_code_hexc   
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekTauxPenetration($codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc   
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekTauxPenetration($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc   
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekTauxPenetration($codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function maxDateNbLogtClustersByWeekDirectorTauxPenetration($director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc   
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekDirectorTauxPenetration($codeCluster, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekDirectorTauxPenetration($codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekDirectorTauxPenetration($codeCluster,$ville,$nom_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function maxDateNbLogtClustersByWeekManagerTauxPenetration($manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekManagerTauxPenetration($codeCluster, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekManagerTauxPenetration($codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date 
                FROM `nmd_pr_neuves_passage` C ,nmd_cbl F 
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekManagerTauxPenetration($codeCluster,$ville,$nom_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(jour_livraison) AS max_date  
                FROM `nmd_pr_neuves_passage` C  ,nmd_cbl F
                WHERE  C.`code_hexc` = F.cbl_code_hexc  
                AND C.code_cluster='$codeCluster' 
                AND C.ville='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }


    public function totalPrisesClustersByWeekView($view, $sqlJourLivraison='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total , C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `$view` C 
                WHERE C.id>0  
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function totalPrisesFermesClustersByWeekView($view, $codeCluster, $sqlJourLivraison='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logt`) as total 
                FROM `$view` C 
                WHERE is_open_close = 1 
                AND code_cluster = '$codeCluster'   
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    public function totalPrisesParcClustersByWeekView($codeCluster, $sqlJourLivraison='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total
                FROM nmd_pr_parc_passage C 
                WHERE C.id>0  
                AND code_cluster = '$codeCluster'   
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }

    // ----------------------- End V2

    public function totalPrisesNeuvesCompany($cpvCourtier, $month, $year, $code_cluster){

        $conn = $this->getEntityManager()->getConnection();

        $sql="        SELECT SUM(nb_logt) AS total
                      FROM nmd_pr_neuves_passage 
                      WHERE MONTH(jour_livraison) = '$month'  
                      AND YEAR(jour_livraison) = '$year'  
                      AND code_cluster = '$code_cluster'  
                      AND cpv='$cpvCourtier' 

                    
                      ";

        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetch();
    }

    public function totalPrisesNeuvesDirector($director_id, $month, $year, $code_cluster){

        $conn = $this->getEntityManager()->getConnection();

        $sql="        SELECT SUM(nb_logt) AS total
                      FROM nmd_pr_neuves_passage 
                      WHERE MONTH(jour_livraison) = '$month'  
                      AND YEAR(jour_livraison) = '$year'  
                      AND code_cluster = '$code_cluster'  
                      AND user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  

                    
                      ";

        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetch();
    }
}
