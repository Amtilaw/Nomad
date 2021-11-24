<?php

namespace App\Repository;

use App\Entity\NmdCbl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NmdCbl|null find($id, $lockMode = null, $lockVersion = null)
 * @method NmdCbl|null findOneBy(array $criteria, array $orderBy = null)
 * @method NmdCbl[]    findAll()
 * @method NmdCbl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NmdCblRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NmdCbl::class);
    }

    public function TotalSellsDetailsGroupByDate($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT count(F.id) as total, DATE(date_vente_valid_b) as jourProduction

           FROM `nmd_cbl` F WHERE  $num_commande 
            GROUP BY DATE(date_vente_valid_b) 
        ";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function loadVentesPartnerGroupByDate($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT COUNT(DISTINCT F.id) as total,DATE(F.date_vente_valid_b) as jourProduction 
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE  $num_commande $sql_cluster 
                  GROUP BY DATE(F.date_vente_valid_b)";

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function loadVentesByDate($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT COUNT(DISTINCT F.id) as total,DATE(F.date_vente_valid_b) as jourProduction 
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster.' GROUP BY DATE(F.date_vente_valid_b)';
        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function TotalSellsDetails($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT num_commande, lbl_offre, signup_type_ecom, box_v8, statut_ko_vv_sbl, rac_planifie, 
            statut_global_racc, libelle_comm, code_post_titu, date_vente_valid_b,
            nom_user, prenom_user, date_cmd_a, etat_de_la_vente, F.id AS v_id,
           date_racco_nc_i,date_1er_rdv_deb,commentaire_cri,libelle_cluster,
           code_panier,code_point_vente,date_rem,date_prochain_rdv_nc, date_annu,date_maj_motif

           FROM `nmd_cbl` F WHERE  $num_commande
        ";

        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function loadVentesPartner($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT *,U.firstname,U.lastname, U.prestataire_id as code_vendeur, U.id as vendeurId, U.roles as vendeurRole,
                          F.id AS v_id,
                          F.date_racco_nc_i,F.date_1er_rdv_deb,F.commentaire_cri,F.libelle_cluster,
                          F.code_panier,F.code_point_vente,F.date_rem,F.date_prochain_rdv_nc, F.date_annu, F.date_maj_motif
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE ". $num_commande . ' ' . $sql_cluster;

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function loadVentes($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT * ,U.firstname, U.lastname, U.prestataire_id as code_vendeur, U.id as vendeurId, U.roles as vendeurRole,
                          F.id AS v_id,
                           F.date_racco_nc_i,F.date_1er_rdv_deb,F.commentaire_cri,
                           F.libelle_cluster,F.code_panier,F.code_point_vente,F.date_rem,
                           F.date_prochain_rdv_nc, F.date_annu, F.date_maj_motif
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster;
        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }


    public function TotalSellsDetailsGroupByMonth($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT count(F.id) as total, MONTH(date_vente_valid_b) as month_date_vente_valid_b

           FROM `nmd_cbl` F WHERE  $num_commande 
            GROUP BY MONTH(date_vente_valid_b) 
        ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function TotalSellsDetailsGroupBySignupTypeEcom($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT count(F.id) as total, signup_type_ecom

           FROM `nmd_cbl` F WHERE  $num_commande 
            GROUP BY signup_type_ecom 
        ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function TotalSellsDetailsGroupByLblOffre($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT count(F.id) as total, lbl_offre

           FROM `nmd_cbl` F WHERE  $num_commande 
            GROUP BY lbl_offre 
        ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function TotalSellsDetailsGroupByStatusGlobalRacc($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT count(F.id) as total, statut_global_racc

           FROM `nmd_cbl` F WHERE  $num_commande 
            GROUP BY statut_global_racc 
        ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function TotalSellsDetailsGroupByEtatDeLaVente($num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =
            "  SELECT count(F.id) as total, etat_de_la_vente

           FROM `nmd_cbl` F WHERE  $num_commande 
            GROUP BY etat_de_la_vente 
        ";

        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery()->fetchAllAssociative();

    }


    public function loadVentesPartnerGroupByMonth($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT COUNT(DISTINCT F.id) as total,MONTH(F.date_vente_valid_b) as month_date_vente_valid_b 
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE  $num_commande $sql_cluster 
                  GROUP BY MONTH(F.date_vente_valid_b)";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function loadVentesPartnerGroupBySignupTypeEcom($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT COUNT(DISTINCT F.id) as total, signup_type_ecom 
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE  $num_commande $sql_cluster 
                  GROUP BY signup_type_ecom";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function loadVentesPartnerGroupByLblOffre($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT COUNT(DISTINCT F.id) as total, lbl_offre 
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE  $num_commande $sql_cluster 
                  GROUP BY lbl_offre";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function loadVentesPartnerGroupByStatusGlobalRacc($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT COUNT(DISTINCT F.id) as total, statut_global_racc 
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE  $num_commande $sql_cluster 
                  GROUP BY statut_global_racc";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetchAllAssociative();

    }
    public function loadVentesPartnerGroupByEtatDeLaVente($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "  SELECT COUNT(DISTINCT F.id) as total, etat_de_la_vente 
                  FROM `nmd_cbl` F 
                  LEFT JOIN nmd_track T ON F.num_commande=T.num_cbl
                  LEFT JOIN `user` U  ON T.user_id=U.id
                  WHERE  $num_commande $sql_cluster 
                  GROUP BY etat_de_la_vente";

        $stmt = $conn->prepare($sql);

        
        return $stmt->executeQuery()->fetchAllAssociative();

    }

    public function loadVentesByMonth($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT COUNT(DISTINCT F.id) as total,MONTH(F.date_vente_valid_b) as month_date_vente_valid_b 
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster.' GROUP BY MONTH(F.date_vente_valid_b)';
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function loadVentesBySignupTypeEcom($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT COUNT(DISTINCT F.id) as total, signup_type_ecom 
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster.' GROUP BY signup_type_ecom';
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function loadVentesByLblOffre($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT COUNT(DISTINCT F.id) as total, lbl_offre 
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster.' GROUP BY lbl_offre';
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function loadVentesByStatusGlobalRacc($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT COUNT(DISTINCT F.id) as total, statut_global_racc 
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster.' GROUP BY statut_global_racc';
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function loadVentesByEtatDeLaVente($sql_cluster,$num_commande)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT COUNT(DISTINCT F.id) as total, etat_de_la_vente 
                FROM `nmd_cbl` F, `nmd_track` A  
                LEFT JOIN `user` U  ON A.user_id=U.id
                WHERE ' . $num_commande . ' ' . $sql_cluster.' GROUP BY etat_de_la_vente';
        $stmt = $conn->prepare($sql);
        

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->executeQuery()->fetchAllAssociative();
    }

}
