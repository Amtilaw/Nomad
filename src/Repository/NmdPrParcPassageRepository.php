<?php

namespace App\Repository;

use App\Entity\NmdPrParcPassage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdPrParcPassage|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdPrParcPassage|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdPrParcPassage[]    findAll()
 * @method NmdPrParcPassage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdPrParcPassageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdPrParcPassage::class);
    }

    //-------------------------------    V2    -------------------------------------

    public function totalNbLogtClustersByWeek( $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total  , C.code_cluster, C.lbl_cluster as libelle_cluster , SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtDatesByWeek( $cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total , MONTH(dat_prem_comm_adrs) as month_dat_prem_comm_adrs, YEAR(dat_prem_comm_adrs) as year_dat_prem_comm_adrs  , dat_prem_comm_adrs , SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                $sql_second 
                GROUP BY  MONTH(dat_prem_comm_adrs), YEAR(dat_prem_comm_adrs) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeek($codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total , C.vill, C.cod_insee , C.code_cluster , SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY  C.vill 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeek($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total , C.vill, C.cod_insee , C.code_cluster , C.nom_voie , SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                $sql_second 
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeek($codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total , C.vill, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie , SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }


    public function totalNbLogtClustersByWeekDirector( $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtDatesByWeekDirector( $director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.dat_prem_comm_adrs  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                GROUP BY  DATE(dat_prem_comm_adrs) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekDirector($codeCluster, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.vill, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                GROUP BY  C.vill 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekDirector($codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.vill, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekDirector($codeCluster,$ville,$nom_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.vill, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }


    public function totalNbLogtClustersByWeekManager( $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.code_cluster, C.lbl_cluster as libelle_cluster 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY  C.code_cluster
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtDatesByWeekManager( $manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.dat_prem_comm_adrs  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sql_second 
                GROUP BY DATE(dat_prem_comm_adrs) 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtVillesByClusterWeekManager($codeCluster, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.vill, C.cod_insee , C.code_cluster 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY  C.vill 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtRuesByVilleClusterWeekManager($codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.vill, C.cod_insee , C.code_cluster , C.nom_voie 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY C.nom_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function totalNbLogtNumRuesByRueVilleClusterWeekManager($codeCluster,$ville,$nom_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(`nb_logm`) as total, SUM(CASE WHEN user_id IS NULL THEN + nb_logm ELSE 0 END) AS totalPrisesNoaffected , SUM(CASE WHEN user_id IS NOT NULL THEN + nb_logm ELSE 0 END) AS totalPrisesaffected  , C.vill, C.cod_insee , C.code_cluster , C.nom_voie , C.numr_voie 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                GROUP BY C.numr_voie
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function affectPrisesSfrOrCompany($cpv=null,  $userSelected,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "UPDATE `nmd_pr_parc_passage` 
                SET user_id = '$userSelected' , is_affected=1, affected_at=NOW(), affected_by= '$affected_by', manager_id ='$manager_id', director_id = '$director_id', beneficiary_roles='$beneficiary_roles'  
                WHERE id>0 
                $sql_second 
                $sqlJourLivraison
                ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery();

    }

    public function maxDateNbLogtClustersByWeek( $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtDatesByWeek( $cpv=null, $sql_second='')
    {

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                $sql_second 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeek($codeCluster, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeek($codeCluster,$ville, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeek($codeCluster,$ville,$nom_voie, $cpv=null, $sqlJourLivraison='')
    {
        $sql_second='';

        if($cpv!=null and $cpv!='' and $cpv!='all') $sql_second.=" AND cpv = '$cpv'  ";
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.nom_voie='$nom_voie'  
                $sql_second 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }


    public function maxDateNbLogtClustersByWeekDirector( $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtDatesByWeekDirector( $director_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sql_second 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekDirector($codeCluster, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekDirector($codeCluster,$ville, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id)  
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekDirector($codeCluster,$ville,$nom_voie, $director_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.nom_voie='$nom_voie'  
                AND C.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }


    public function maxDateNbLogtClustersByWeekManager( $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtDatesByWeekManager( $manager_id, $sql_second='')
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0  
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sql_second 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtVillesByClusterWeekManager($codeCluster, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtRuesByVilleClusterWeekManager($codeCluster,$ville, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date 
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
                AND C.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
                $sqlJourLivraison 
                ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetch();

    }
    public function maxDateNbLogtNumRuesByRueVilleClusterWeekManager($codeCluster,$ville,$nom_voie, $manager_id, $sqlJourLivraison='')
    {
        $sql_second='';

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MAX(dat_prem_comm_adrs) AS max_date  
                FROM `nmd_pr_parc_passage` C 
                WHERE id>0 
                AND C.code_cluster='$codeCluster' 
                AND C.vill='$ville' 
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
        $sql = "SELECT SUM(`nb_logm`) as total, U.firstname, U.lastname  
                FROM `nmd_pr_parc_passage` C 
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
        $sql = "SELECT SUM(`nb_logm`) as total, U.firstname, U.lastname  
                FROM `nmd_pr_parc_passage` C 
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
        $sql = "SELECT SUM(`nb_logm`) as total, U.firstname, U.lastname  
                FROM `nmd_pr_parc_passage` C 
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
}
