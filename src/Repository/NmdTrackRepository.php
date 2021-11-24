<?php

namespace App\Repository;

use App\Entity\NmdTrack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdTrack|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdTrack|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdTrack[]    findAll()
 * @method NmdTrack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdTrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdTrack::class);
    }

    public function AllSales($cpv)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT `num_commande` 
                  FROM `nmd_cbl`  
                  WHERE `code_point_vente` = ?
               ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cpv);
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function LoadTrackCompanyDataTableOld($cpv, $directorId , $months, $yearChoice, $sqlDate="")
    {
        $conn = $this->getEntityManager()->getConnection();

        if($months != "all"){
            $sql_months = " AND MONTH(`date`) IN ($months) AND YEAR(`date`) = $yearChoice ";
        }else{
            $sql_months = " AND YEAR(`date`) = $yearChoice ";
        }


        if($directorId != "all" ){
            $sql_directors = " AND A.user_id IN ( SELECT id FROM `user` WHERE `parent` IN ( SELECT id FROM `user` WHERE `parent` = '$directorId'  OR id = '$directorId' )) ";
        }else{

            $sql_directors = "";
        }

        $sql =
            "     SELECT A.* , B.*, C.*, P.*, O.*, 
                  T.lastname AS lastnameTelepro , 
                  T.firstname AS firstnameTelepro,
                  Ca.name as categoryName, 
                  DATEDIFF(DATE(NOW()), A.date) AS diff, 
                  Pr.ville as villeFromPrises, 
                  B.parent as parent

                  FROM `nmd_track` A
                
                  INNER JOIN `user` B ON A.`user_id` = B.id 
                  
                  LEFT JOIN `user` T ON A.`telepro_id` = T.id 
                
                  LEFT JOIN nmd_cbl C ON (C.`num_commande` = A.`num_cbl` AND C.`num_commande` <> '') 
                  
                  LEFT JOIN nmd_product P ON A.`product_id` = P.`id` 
                  
                  LEFT JOIN nmd_operators O ON P.`operator_id` = O.`id`
                  
                  LEFT JOIN nmd_pr_neuves_passage Pr ON Pr.`id` = A.`fdr_id`
                
                  LEFT JOIN nmd_categorie_product Ca ON Ca.id = P.category_id

                  WHERE A.`user_id` IN (SELECT id FROM user WHERE cpv_courtier_exploitant = '$cpv') 
                
                  AND A.etat = 1 
                  $sql_months 
                  $sql_directors 
                  $sqlDate

                   ORDER BY A.date DESC

                 ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function LoadTrackDirectorDataTable($userId, $managerId, $months, $yearChoice, $status_bo=null, $teleop_id=null, $operator_id=null, $hours=null, $sqlDate="")
    {
        $conn = $this->getEntityManager()->getConnection();

        if($months != "all"){
            $sql_months = " AND MONTH(date) IN($months) AND YEAR(date) = $yearChoice";

        }else{
            $sql_months = " AND YEAR(date) = $yearChoice ";
        }


        if($managerId != "all" ){

            $sql_managers = "  AND A.user_id 
                
            IN (SELECT id FROM `user` WHERE `parent` = '$managerId'  OR id = '$managerId' )   ";

        }else{

            $sql_managers = " AND A.user_id 
                
            IN(SELECT id FROM `user` WHERE `parent` 
            
            IN(SELECT id FROM `user` WHERE `parent` = '$userId'  OR id = '$userId' )) ";
        }

        if($status_bo!=null){
            $sql_managers.=((int)$status_bo>0)?" AND A.status_bo='$status_bo' ":(" AND (A.status_bo=0 OR A.status_bo IS NULL) ");
        }
        if($teleop_id!=null and (int)$teleop_id>0){
            $sql_managers.=((int)$teleop_id>0)?" AND A.telepro_id='$teleop_id' ":"";
        }elseif ((int)$teleop_id==-1){
            $sql_managers.=" AND (A.telepro_id='' OR A.telepro_id IS NULL) ";
        }

        if($operator_id!=null and (int)$operator_id>0){
            $sql_managers.=((int)$operator_id>0)?" AND A.operator_id='$operator_id' ":"";
        }

        if($hours!=null and (int)$status_bo==1){
            switch ((int)$hours){
                case -1:
                    $sql_managers.=' AND DATEDIFF(DATE(NOW()), A.date)<1';
                    break;
                case 24:
                    $sql_managers.=' AND DATEDIFF(DATE(NOW()), A.date)>=1 AND DATEDIFF(DATE(NOW()), A.date)<2';
                    break;
                case 48:
                    $sql_managers.=' AND DATEDIFF(DATE(NOW()), A.date)>=2';
                    break;
            }
        }

        $sql =
            " SELECT A.* , B.*, C.*, P.*, O.*, 
                  T.lastname AS lastnameTelepro , 
                  T.firstname AS firstnameTelepro,
                  Pr.ville as villeFromPrises, 
                  B.parent as parent,
                  Ca.name as categoryName
              
                  FROM `nmd_track` A
                  
                  INNER JOIN user B
                  ON A.`user_id` = B.id
                  
                  LEFT JOIN user T
                  ON A.`telepro_id` = T.id 
                
                  LEFT JOIN nmd_cbl C
                  ON C.`num_commande` = A.`num_cbl` 
                  
                  LEFT JOIN nmd_product P
                  ON A.`product_id` = P.`id` 
                  
                  LEFT JOIN nmd_operators O
                  ON P.`operator_id` = O.`id`
                  
                  LEFT JOIN nmd_pr_neuves_passage Pr
                  ON Pr.`id` = A.`fdr_id`
                   
                  LEFT JOIN nmd_categorie_product Ca ON Ca.id = P.category_id

                  WHERE A.etat = 1 
                "  .$sql_months
            .$sql_managers
            ."
                $sqlDate 
                   ORDER BY A.date DESC
                   
                 ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function LoadTrackManagerDataTable($userId, $months, $yearChoice, $status_bo=null, $teleop_id=null, $operator_id=null, $hours=null, $sqlDate="")
    {
        $conn = $this->getEntityManager()->getConnection();

        if($months != "all"){
            $sql_months = " AND MONTH(date) IN($months) AND YEAR(date) = $yearChoice";

        }else{
            $sql_months = " AND YEAR(date) = $yearChoice ";
        }

        if($status_bo!=null){
            $sql_months.=((int)$status_bo>0)?" AND A.status_bo='$status_bo' ":(" AND (A.status_bo=0 OR A.status_bo IS NULL) ");
        }
        if($teleop_id!=null and (int)$teleop_id>0){
            $sql_months.=((int)$teleop_id>0)?" AND A.telepro_id='$teleop_id' ":"";
        }elseif ((int)$teleop_id==-1){
            $sql_months.=" AND (A.telepro_id='' OR A.telepro_id IS NULL) ";
        }

        if($operator_id!=null and (int)$operator_id>0){
            $sql_months.=((int)$operator_id>0)?" AND A.operator_id='$operator_id' ":"";
        }

        if($hours!=null and (int)$status_bo==1){
            switch ((int)$hours){
                case -1:
                    $sql_months.=' AND DATEDIFF(DATE(NOW()), A.date)<1';
                    break;
                case 24:
                    $sql_months.=' AND DATEDIFF(DATE(NOW()), A.date)>=1 AND DATEDIFF(DATE(NOW()), A.date)<2';
                    break;
                case 48:
                    $sql_months.=' AND DATEDIFF(DATE(NOW()), A.date)>=2';
                    break;
            }
        }

        $sql =
            " SELECT A.* , B.*, C.*, P.*, O.*, 
                  T.lastname AS lastnameTelepro , 
                  T.firstname AS firstnameTelepro,
                  Pr.ville as villeFromPrises, 
                  B.parent as parent,
                  Ca.name as categoryName
              
                  FROM `nmd_track` A
                
                  INNER JOIN user B
                  ON A.`user_id` = B.id
                  
                  LEFT JOIN user T
                  ON A.`telepro_id` = T.id 
                
                  LEFT JOIN nmd_cbl C
                  ON C.`num_commande` = A.`num_cbl` 
                  
                  LEFT JOIN nmd_product P
                  ON A.`product_id` = P.`id` 
                  
                  LEFT JOIN nmd_operators O
                  ON P.`operator_id` = O.`id`
                  
                  LEFT JOIN nmd_pr_neuves_passage Pr
                  ON Pr.`id` = A.`fdr_id`
                   
                  LEFT JOIN nmd_categorie_product Ca ON Ca.id = P.category_id

                  WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` = '$userId'  OR id = '$userId' ) 
                  AND A.etat = 1 
                  $sqlDate 
                 ".$sql_months."
                          
                 ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function LoadTrackCompanyDataTableOldByDate($cpv, $directorId , $months, $yearChoice, $sqlDate="")
    {
        $conn = $this->getEntityManager()->getConnection();

        if($months != "all"){
            $sql_months = " AND MONTH(`date`) IN ($months) AND YEAR(`date`) = $yearChoice ";
        }else{
            $sql_months = " AND YEAR(`date`) = $yearChoice ";
        }


        if($directorId != "all" ){
            $sql_directors = " AND A.user_id IN ( SELECT id FROM `user` WHERE `parent` IN ( SELECT id FROM `user` WHERE `parent` = '$directorId'  OR id = '$directorId' )) ";
        }else{

            $sql_directors = "";
        }

        $sql =
            "     SELECT count(A.id) as total, A.date 
                  FROM `nmd_track` A 
                  WHERE A.`user_id` IN (SELECT id FROM user WHERE cpv_courtier_exploitant = '$cpv') 
                
                  AND A.etat = 1 
                  $sql_months 
                  $sql_directors 
                  $sqlDate
                    GROUP BY DATE(A.date) 
                   ORDER BY A.date DESC 

                 ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function LoadTrackDirectorDataTableByDate($userId, $managerId, $months, $yearChoice, $status_bo=null, $teleop_id=null, $operator_id=null, $hours=null, $sqlDate="")
    {
        $conn = $this->getEntityManager()->getConnection();

        if($months != "all"){
            $sql_months = " AND MONTH(date) IN($months) AND YEAR(date) = $yearChoice";

        }else{
            $sql_months = " AND YEAR(date) = $yearChoice ";
        }


        if($managerId != "all" ){

            $sql_managers = "  AND A.user_id 
                
            IN (SELECT id FROM `user` WHERE `parent` = '$managerId'  OR id = '$managerId' )   ";

        }else{

            $sql_managers = " AND A.user_id 
                
            IN(SELECT id FROM `user` WHERE `parent` 
            
            IN(SELECT id FROM `user` WHERE `parent` = '$userId'  OR id = '$userId' )) ";
        }

        if($status_bo!=null){
            $sql_managers.=((int)$status_bo>0)?" AND A.status_bo='$status_bo' ":(" AND (A.status_bo=0 OR A.status_bo IS NULL) ");
        }
        if($teleop_id!=null and (int)$teleop_id>0){
            $sql_managers.=((int)$teleop_id>0)?" AND A.telepro_id='$teleop_id' ":"";
        }elseif ((int)$teleop_id==-1){
            $sql_managers.=" AND (A.telepro_id='' OR A.telepro_id IS NULL) ";
        }

        if($operator_id!=null and (int)$operator_id>0){
            $sql_managers.=((int)$operator_id>0)?" AND A.operator_id='$operator_id' ":"";
        }

        if($hours!=null and (int)$status_bo==1){
            switch ((int)$hours){
                case -1:
                    $sql_managers.=' AND DATEDIFF(DATE(NOW()), A.date)<1';
                    break;
                case 24:
                    $sql_managers.=' AND DATEDIFF(DATE(NOW()), A.date)>=1 AND DATEDIFF(DATE(NOW()), A.date)<2';
                    break;
                case 48:
                    $sql_managers.=' AND DATEDIFF(DATE(NOW()), A.date)>=2';
                    break;
            }
        }

        $sql =
            " SELECT count(A.id) as total, A.date 
              
                  FROM `nmd_track` A 

                  WHERE A.etat = 1 
                "  .$sql_months
            .$sql_managers
            ."
                $sqlDate 
                GROUP BY DATE(A.date) 
                   ORDER BY A.date DESC 
                 ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function LoadTrackManagerDataTableByDate($userId, $months, $yearChoice, $status_bo=null, $teleop_id=null, $operator_id=null, $hours=null, $sqlDate="")
    {
        $conn = $this->getEntityManager()->getConnection();

        if($months != "all"){
            $sql_months = " AND MONTH(date) IN($months) AND YEAR(date) = $yearChoice";

        }else{
            $sql_months = " AND YEAR(date) = $yearChoice ";
        }

        if($status_bo!=null){
            $sql_months.=((int)$status_bo>0)?" AND A.status_bo='$status_bo' ":(" AND (A.status_bo=0 OR A.status_bo IS NULL) ");
        }
        if($teleop_id!=null and (int)$teleop_id>0){
            $sql_months.=((int)$teleop_id>0)?" AND A.telepro_id='$teleop_id' ":"";
        }elseif ((int)$teleop_id==-1){
            $sql_months.=" AND (A.telepro_id='' OR A.telepro_id IS NULL) ";
        }

        if($operator_id!=null and (int)$operator_id>0){
            $sql_months.=((int)$operator_id>0)?" AND A.operator_id='$operator_id' ":"";
        }

        if($hours!=null and (int)$status_bo==1){
            switch ((int)$hours){
                case -1:
                    $sql_months.=' AND DATEDIFF(DATE(NOW()), A.date)<1';
                    break;
                case 24:
                    $sql_months.=' AND DATEDIFF(DATE(NOW()), A.date)>=1 AND DATEDIFF(DATE(NOW()), A.date)<2';
                    break;
                case 48:
                    $sql_months.=' AND DATEDIFF(DATE(NOW()), A.date)>=2';
                    break;
            }
        }

        $sql =
            " SELECT count(A.id) as total, A.date 
              
                  FROM `nmd_track` A
                
                  WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` = '$userId'  OR id = '$userId' ) 
                  AND A.etat = 1 
                  $sqlDate  
                 $sql_months 
               GROUP BY DATE(A.date) 
                ORDER BY A.date DESC 
                 ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }



    /*----------- Rémunération Bruillon -----------*/

    public function countventeTotalCompany($cpv,$month,$year, $libelleQuery)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total 
            FROM nmd_cbl F  
            WHERE F.code_point_vente ='$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetch();
    }
    public function countventeTotalDirector($director_id,$month,$year, $libelleQuery)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total 
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetch();
    }
    public function countventeTotalManager($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total 
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetch();
    }
    public function countventeTotalSeller($seller_id,$month,$year, $libelleQuery)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total 
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            WHERE A.user_id=$seller_id 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetch();
    }

    public function countventeTotalRealiseCompany($cpv,$month,$year, $libelleQuery)
    {
        $month_1=($month>1)?($month-1):12;
        $month_2=($month_1>1)?($month_1-1):12;
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(*) AS total,   MONTH(`jour_livraison`) as mois_liv
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.cpv ='$cpv'  
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(F.`$libelleQuery`) = '$year'  
                AND YEAR(`jour_livraison`)  = $year 
                group by MONTH(`jour_livraison`)
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeTotalRealiseDirector($director_id,$month,$year, $libelleQuery)
    {
        $month_1=($month>1)?($month-1):12;
        $month_2=($month_1>1)?($month_1-1):12;
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(*) AS total,   MONTH(`jour_livraison`) as mois_liv
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(F.`$libelleQuery`) = '$year'  
                AND YEAR(`jour_livraison`)  = $year 
                group by MONTH(`jour_livraison`)
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeTotalRealiseManager($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $month_1=($month>1)?($month-1):12;
        $month_2=($month_1>1)?($month_1-1):12;
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(*) AS total,   MONTH(`jour_livraison`) as mois_liv
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.user_id IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.")  
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(F.`$libelleQuery`) = '$year'  
                AND YEAR(`jour_livraison`)  = $year 
                $sqlSeconde 
                group by MONTH(`jour_livraison`)
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeTotalRealiseSeller($seller_id,$month,$year, $libelleQuery)
    {
        $month_1=($month>1)?($month-1):12;
        $month_2=($month_1>1)?($month_1-1):12;
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(*) AS total,   MONTH(`jour_livraison`) as mois_liv
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.user_id = $seller_id 
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(F.`$libelleQuery`) = '$year'  
                AND YEAR(`jour_livraison`)  = $year 
                group by MONTH(`jour_livraison`)
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function countventeTotalRealiseCompanyByCluster($cpv,$month,$year, $libelleQuery,$code_cluster)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT SUM(A.nb_logt) AS total 
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.cpv ='$cpv'   
                AND MONTH(`jour_livraison`)  IN ($month) 
                AND YEAR(`jour_livraison`)  = $year 
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND A.code_cluster='$code_cluster' 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetch();
    }
    public function countventeTotalRealiseDirectorByCluster($director_id,$month,$year, $libelleQuery, $code_cluster)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT SUM(A.nb_logt) AS total 
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                AND MONTH(`jour_livraison`)  IN ($month) 
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(`jour_livraison`)  = $year 
                AND A.code_cluster='$code_cluster' 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetch();
    }

    public function detailventeRealiseCompany($cpv,$month,$mpn,$year, $libelleQuery)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT DISTINCT  F.* 
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A 
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.cpv='$cpv' 
                AND MONTH(`jour_livraison`)  IN ($mpn) 
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(F.`$libelleQuery`) = '$year'  
                AND YEAR(`jour_livraison`)  = $year 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailventeRealiseDirector($director_id,$month,$mpn,$year, $libelleQuery)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT DISTINCT  F.* 
                FROM nmd_cbl F , `nmd_pr_neuves_passage` A 
                WHERE A.`code_hexc` = F.cbl_code_hexc 
                AND A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
                AND MONTH(`jour_livraison`)  IN ($mpn) 
                AND MONTH(F.`$libelleQuery`) = '$month' 
                AND YEAR(F.`$libelleQuery`) = '$year'  
                AND YEAR(`jour_livraison`)  = $year 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Global ventes
    public function countventeCompany($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {

        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente = '$cpv' 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by signup_type_ecom,lbl_offre, statut_global_racc
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirector($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by signup_type_ecom,lbl_offre, statut_global_racc
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManager($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by signup_type_ecom,lbl_offre, statut_global_racc 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSeller($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by signup_type_ecom,lbl_offre, statut_global_racc 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // détail ventes
    public function detailcountventeCompany($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT DISTINCT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, PP.start_at, PP.end_at, PP.remuneration_name, F.dernier_motif_racc       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at) )   
            WHERE F.code_point_vente = '$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY F.id
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailcountventeDirector($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT DISTINCT A.id, num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, PP.start_at, PP.end_at, PP.remuneration_name, F.dernier_motif_racc       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at) )   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY A.id
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailcountventeManager($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT DISTINCT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient , PP.start_at, PP.end_at, PP.remuneration_name, F.dernier_motif_racc       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY A.id
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailcountventeSeller($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT DISTINCT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient , PP.start_at, PP.end_at, PP.remuneration_name, F.dernier_motif_racc        
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN user U ON U.id = $seller_id    
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde
            GROUP BY A.id 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Ventes global by clusters 'objectifs'
    public function countventeCompanyClusters($cpv,$userId,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total,SUM(P.organization_price) as total_price, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.vv as objectives_vv, OM.vr as objectives_vr, P.id as product_id           
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_objectif_clusters OM  ON (OM.cpv='$cpv' and OM.code_cluster=F.code_cluster and mois='$month') 
            WHERE F.code_point_vente = '$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.code_cluster, P.id 
            ORDER BY F.code_cluster DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorClusters($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,SUM(P.organization_price) as total_price, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.objectives_vv, OM.objectives_vr, P.id as product_id        
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_objectives_management OM  ON (OM.user_id=$director_id and OM.code_cluster=F.code_cluster and MONTH(OM.month_at)='$month') 
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.code_cluster, P.id 
            ORDER BY F.code_cluster DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerClusters($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,SUM(P.organization_price) as total_price, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.objectives_vv, OM.objectives_vr, P.id as product_id           
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            
            LEFT JOIN nmd_objectives_management OM  ON (OM.user_id=$manager_id and OM.code_cluster=F.code_cluster and MONTH(OM.month_at)='$month') 
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.code_cluster, P.id  
            ORDER BY F.code_cluster DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSellerClusters($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,SUM(P.organization_price) as total_price, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.objectives_vv, OM.objectives_vr, P.id as product_id           
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_objectives_management OM  ON (OM.user_id=$seller_id and OM.code_cluster=F.code_cluster and MONTH(OM.month_at)='$month') 
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.code_cluster, P.id 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Détail ventes by clusters 'objectifs'
    public function detailcountventeCompanyClusters($cpv,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.vv as objectives_vv, OM.vr as objectives_vr       
            FROM nmd_cbl F  
            LEFT JOIN nmd_objectif_clusters OM  ON (OM.cpv='$cpv' and OM.code_cluster=F.code_cluster and mois='$month')  
            WHERE F.code_point_vente = '$cpv'   
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY F.id
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailcountventeDirectorClusters($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.objectives_vv, OM.objectives_vr       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
                
            LEFT JOIN nmd_objectives_management OM  ON (OM.user_id=$director_id and OM.code_cluster=F.code_cluster and MONTH(OM.month_at)='$month') 
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY A.id
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailcountventeManagerClusters($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.objectives_vv, OM.objectives_vr       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_objectives_management OM  ON (OM.user_id=$manager_id and OM.code_cluster=F.code_cluster and MONTH(OM.month_at)='$month') 
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY A.id
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function detailcountventeSellerClusters($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT num_commande, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, F.code_cluster, F.libelle_cluster, OM.objectives_vv, OM.objectives_vr       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_objectives_management OM  ON (OM.user_id=$seller_id and OM.code_cluster=F.code_cluster and MONTH(OM.month_at)='$month') 
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            GROUP BY A.id 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Group by month
    public function countventeCompanyGroupByMonth($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        if($libelleQuery=='date_vente_valid_b') $sqlGroupBy="date_activ_com_h";
        else {
            $sqlGroupBy = "date_vente_valid_b";
            $sqlSeconde.=" AND F.statut_global_racc='Raccorde' ";
        }
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total,MONTH(date_vente_valid_b) as month_valide,MONTH(date_activ_com_h) as month_raccorde, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente ='$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by MONTH(`$sqlGroupBy`) 
            ORDER BY total DESC 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorGroupByMonth($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        if($libelleQuery=='date_vente_valid_b') $sqlGroupBy="date_activ_com_h";
        else $sqlGroupBy="date_vente_valid_b";
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,MONTH(date_vente_valid_b) as month_valide,MONTH(date_activ_com_h) as month_raccorde, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by MONTH(`$sqlGroupBy`) 
            ORDER BY total DESC 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerGroupByMonth($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        if($libelleQuery=='date_vente_valid_b') $sqlGroupBy="date_activ_com_h";
        else $sqlGroupBy="date_vente_valid_b";
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,MONTH(date_vente_valid_b) as month_valide,MONTH(date_activ_com_h) as month_raccorde, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by MONTH(`$sqlGroupBy`) 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSellerGroupByMonth($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        if($libelleQuery=='date_vente_valid_b') $sqlGroupBy="date_activ_com_h";
        else $sqlGroupBy="date_vente_valid_b";
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,MONTH(date_vente_valid_b) as month_valide,MONTH(date_activ_com_h) as month_raccorde, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by MONTH(`$sqlGroupBy`) 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Group by LIBELL2 R2MUN2RAION
    public function countventeCompanyByLibelle($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total, PP.remuneration_name       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente ='$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by PP.remuneration_name 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorByLibelle($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, PP.remuneration_name       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by PP.remuneration_name 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerByLibelle($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, PP.remuneration_name      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by PP.remuneration_name 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSellerByLibelle($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, PP.remuneration_name       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by PP.remuneration_name 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Détail ventes by vendeurs
    public function countventeCompanyGroupBySellers($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, U.lastname, U.firstname, U.id as user_id      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente = '$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by A.user_id 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorGroupBySellers($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, U.lastname, U.firstname, U.id as user_id      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by A.user_id 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerGroupBySellers($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, U.lastname, U.firstname, U.id as user_id       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by A.user_id 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function countventeDirectorGroupByManager($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, Pa.lastname, Pa.firstname, Pa.id as user_id       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN user Pa ON Pa.id = U.parent   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by U.parent 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeCompanyGroupByManager($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, Pa.lastname, Pa.firstname, Pa.id as user_id       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN user Pa ON Pa.id = U.parent   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente = '$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by U.parent 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeCompanyGroupByDirector($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total, signup_type_ecom,date_cmd_a,date_vente_valid_b,date_activ_com_h, lbl_offre, statut_global_racc, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient, Pd.lastname, Pd.firstname, Pd.id as user_id       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN user U ON U.id = A.user_id   
            LEFT JOIN user Pa ON Pa.id = U.parent   
            LEFT JOIN user Pd ON Pd.id = Pa.parent   
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente = '$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by Pa.parent 
            ORDER BY total DESC

                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Group by month
    public function countventeCompanyGroupByDay($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {
        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total,WEEKDAY(`date_cmd_a`) as day, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente ='$cpv'  
            AND MONTH(`date_cmd_a`) = '$month' 
            AND YEAR(`date_cmd_a`) = '$year' 
            $sqlSeconde 
            group by WEEKDAY(`date_cmd_a`) 
            ORDER BY total DESC 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorGroupByDay($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,WEEKDAY(`date_cmd_a`) as day, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`date_cmd_a`) = '$month' 
            AND YEAR(`date_cmd_a`) = '$year' 
            $sqlSeconde 
            group by WEEKDAY(`date_cmd_a`) 
            ORDER BY total DESC 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerGroupByDay($manager_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,WEEKDAY(`date_cmd_a`) as day, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`date_cmd_a`) = '$month' 
            AND YEAR(`date_cmd_a`) = '$year' 
            $sqlSeconde 
            group by WEEKDAY(`date_cmd_a`) 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSellerGroupByDay($seller_id,$month,$year, $libelleQuery, $sqlSeconde='')
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,WEEKDAY(`date_cmd_a`) as day, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient       
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc 
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.`user_id`=$seller_id  
            AND MONTH(`date_cmd_a`) = '$month' 
            AND YEAR(`date_cmd_a`) = '$year' 
            $sqlSeconde 
            group by WEEKDAY(`date_cmd_a`) 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Group by date
    public function countventeCompanyGroupByDate($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {

        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total,DATE($libelleQuery) as date_cbl, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente ='$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by DATE(`$libelleQuery`) 
            ORDER BY DATE(`$libelleQuery`) DESC 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorGroupByDate($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,DATE($libelleQuery) as date_cbl, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by DATE(`$libelleQuery`) 
            ORDER BY DATE(`$libelleQuery`) DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerGroupByDate($manager_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,DATE($libelleQuery) as date_cbl, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.") 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by DATE(`$libelleQuery`) 
            ORDER BY DATE(`$libelleQuery`) DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSellerGroupByDate($seller_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,DATE($libelleQuery) as date_cbl, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id = $seller_id 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by DATE(`$libelleQuery`) 
            ORDER BY DATE(`$libelleQuery`) DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    // Group by etat de la vente
    public function countventeCompanyGroupByEtatDeLaVente($cpv,$parentId,$month,$year,$libelleQuery, $sqlSeconde='', $operatorId='')
    {

        $sqlRemuOperator="";
        if($operatorId>0) $sqlRemuOperator=" AND S.rem_operator_id=$operatorId ";

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT F.id) AS total, F.etat_de_la_vente, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON (S.rem_status = F.statut_global_racc AND S.rem_user_id=$parentId $sqlRemuOperator)  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id and PP.user_id=$parentId  AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE F.code_point_vente ='$cpv'  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.etat_de_la_vente  
            ORDER BY total DESC 
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeDirectorGroupByEtatDeLaVente($director_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,F.etat_de_la_vente, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN (SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = $director_id) OR parent = $director_id) 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.etat_de_la_vente 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeManagerGroupByEtatDeLaVente($manager_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,F.etat_de_la_vente, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id=".$manager_id.")  
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.etat_de_la_vente 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function countventeSellerGroupByEtatDeLaVente($seller_id,$month,$year,$libelleQuery, $sqlSeconde='')
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "SELECT COUNT(DISTINCT A.id) AS total,F.etat_de_la_vente, S.rem_percent, P.id as product_id, PP.points, PP.cumul_steps, PP.coefficient      
            FROM nmd_cbl F 
            LEFT JOIN nmd_track A ON A.num_cbl = F.num_commande 
            LEFT JOIN nmd_remuneration_status S ON S.rem_status = F.statut_global_racc  
            LEFT JOIN nmd_product P ON (UPPER(F.signup_type_ecom)=P.organization_category AND UPPER(F.lbl_offre)=P.organization_naming)  
            LEFT JOIN nmd_product_points PP ON (PP.product_id=P.id AND DATE(date_vente_valid_b)>=DATE(PP.start_at) AND DATE(date_vente_valid_b)<=DATE(PP.end_at))   
            WHERE A.user_id = $seller_id 
            AND MONTH(`$libelleQuery`) = '$month' 
            AND YEAR(`$libelleQuery`) = '$year' 
            $sqlSeconde 
            group by F.etat_de_la_vente 
            ORDER BY total DESC
                 ";

        $stmt = $conn->prepare($sql);

        

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /*----------- End Rémunération Bruillon -----------*/


}
