<?php

namespace App\Controller;

use App\Entity\NmdCbl;
use App\Entity\NmdPartner;
use App\Entity\NmdProduct;
use App\Entity\NmdTrack;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @Route("/production", name="production_")
*/
class ProductionController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request,UserInterface $user): Response
    {

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);
        foreach ($user->getRoles() as $r) {
            $role = $r;
        }
        $userCpv = $user->getCpvCourtierExploitant();

        $repository=$this->getDoctrine()->getRepository(NmdCbl::class);
        $repository_partenaire=$this->getDoctrine()->getRepository(NmdPartner::class);

        $list_partenaires=[];
        $list_cluster=[];
        $list_directeurs=[];
        $list_managers=[];
        $num_commande = "";
        $sql_cluster = "";
        $join_left="";
        $monthSelected="";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $monthChoice=$request->get('monthChoise');
        $jourProduction=$request->get('jourProduction');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }

        $num_commande .= ' MONTH(F.`date_vente_valid_b`) = ' . $month .' AND YEAR(F.`date_vente_valid_b`) = '.$year;
        if($jourProduction!=null and $jourProduction!=''){
            $num_commande .= " DATE(F.`date_vente_valid_b`) = '$jourProduction' " ;
        }

        if($role=='ROLE_SFR'){

            $list_partenaires=$repository_partenaire->selectAllPartnerInParner($user->getId());

            $ventes = $repository->TotalSellsDetailsGroupByDate($num_commande);

        }
        elseif ($role=='ROLE_COMPANY'){

            $list_directeurs=$repository_partenaire->DirecteursForPartnerById($user->getId());
            $_org_partner=$repository_partenaire->findOneBy(array('myaccountId'=>$user->getId()));
            if($_org_partner) $partner=$_org_partner->getPartner();
            else $partner=$user->getFirstname();
            $sql_cluster = ' AND `lib_pdv` = "' . addslashes($partner) . '"';

            $ventes = $repository->loadVentesPartnerGroupByDate($sql_cluster,$num_commande);

        }
        elseif ($role=='ROLE_DIRECTOR'){

            $director_id=$user->getId();
            $list_managers=$repository_partenaire->managersForDirecteur($director_id);
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` 
                           IN(SELECT id FROM `user` WHERE `parent` 
                           IN(SELECT id FROM `user` WHERE `parent` = ".$director_id." OR id = ".$director_id.")) 
                           AND F.code_point_vente = ".$userCpv."
                           ";

            $ventes = $repository->loadVentesByDate($sql_cluster,$num_commande);

        }
        elseif ($role=='ROLE_MANAGER'){

            $manager_id=$user->getId();
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` 
            IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id = ".$manager_id.") 
            AND F.code_point_vente = ".$userCpv."
            
            ";

            $ventes = $repository->loadVentesByDate($sql_cluster,$num_commande);

        }
        elseif ($role=='ROLE_SELLER'){
            $seller_id=$user->getId();

            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` = ".$seller_id;

            $ventes = $repository->loadVentesByDate($sql_cluster,$num_commande);

        }

        if($request->get('partner')){
            $partner=$request->get('partner');
            $sql_cluster .= ' AND `lib_pdv` = "' . addslashes($partner) . '"';

            $ventes = $repository->loadVentesPartnerGroupByDate($sql_cluster,$num_commande);
        }
        if($request->get('director')){
            $director_id=$request->get('director');
            $sql_cluster.=" AND A.num_cbl = F.num_commande AND A.`user_id` IN(SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = ".$director_id." OR id = ".$director_id.")) ";
            if($request->get('cluster')){

                $cluster=$request->get('cluster');
                $sql_cluster .= ' AND F.`code_cluster`= "' . $cluster.'"';

            }
            $ventes = $repository->loadVentesByDate($sql_cluster,$num_commande);

        }
        if($request->get('manager')){
            $manager_id=$request->get('manager');
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id = ".$manager_id.") ";
            if($request->get('cluster')){

                $cluster=$request->get('cluster');
                $sql_cluster .= ' AND F.`code_cluster`= "' . $cluster.'"';

            }
            $ventes = $repository->loadVentesByDate($sql_cluster,$num_commande);

        }

        return $this->render('production/index.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'production',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'role'=>$role,
            'ventes' => $ventes,
            'm' => $month,
            'yearChoice' => $year,
            'monthSelected' => $monthSelected,
            'list_partenaires'=>$list_partenaires,
            'list_cluster'=>$list_cluster,
            'list_directeurs'=>$list_directeurs,
            'list_managers'=>$list_managers,
        ]);
    }

    /**
     * @Route("/details", name="details")
     */
    public function details(Request $request,UserInterface $user)
    {

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);
        foreach ($user->getRoles() as $r) {
            $role = $r;
        }
        $userCpv = $user->getCpvCourtierExploitant();

        $repository=$this->getDoctrine()->getRepository(NmdCbl::class);
        $repository_partenaire=$this->getDoctrine()->getRepository(NmdPartner::class);

        $list_partenaires=[];
        $list_cluster=[];
        $list_directeurs=[];
        $list_managers=[];
        $num_commande = "";
        $sql_cluster = "";
        $join_left="";
        $monthSelected="";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $monthChoice=$request->get('monthChoise');
        $jourProduction=$request->get('jourProduction');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }

        $num_commande .= ' MONTH(F.`date_vente_valid_b`) = ' . $month .' AND YEAR(F.`date_vente_valid_b`) = '.$year;
        if($jourProduction!=null and $jourProduction!=''){
            $num_commande .= " AND DATE(F.`date_vente_valid_b`) = '$jourProduction' " ;
        }

        if($role=='ROLE_SFR'){

            $list_partenaires=$repository_partenaire->selectAllPartnerInParner($user->getId());

            // $ventes = $repository->loadVentesPartner($sql_cluster,$num_commande);
            $ventes = $repository->TotalSellsDetails($num_commande);

        }
        elseif ($role=='ROLE_COMPANY'){

            $list_directeurs=$repository_partenaire->DirecteursForPartnerById($user->getId());
            $_org_partner=$repository_partenaire->findOneBy(array('myaccountId'=>$user->getId()));
            if($_org_partner) $partner=$_org_partner->getPartner();
            else $partner=$user->getFirstname();
            $sql_cluster = ' AND `lib_pdv` = "' . addslashes($partner) . '"';

            $ventes = $repository->loadVentesPartner($sql_cluster,$num_commande);

        }
        elseif ($role=='ROLE_DIRECTOR'){

            $director_id=$user->getId();
            $list_managers=$repository_partenaire->managersForDirecteur($director_id);
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` 
                           IN(SELECT id FROM `user` WHERE `parent` 
                           IN(SELECT id FROM `user` WHERE `parent` = ".$director_id." OR id = ".$director_id.")) 
                           AND F.code_point_vente = ".$userCpv."
                           ";

            $ventes = $repository->loadVentes($sql_cluster,$num_commande);

        }
        elseif ($role=='ROLE_MANAGER'){

            $manager_id=$user->getId();
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` 
            IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id = ".$manager_id.") 
            AND F.code_point_vente = ".$userCpv."
            
            ";

            $ventes = $repository->loadVentes($sql_cluster,$num_commande);

        }
        elseif ($role=='ROLE_SELLER'){
            $seller_id=$user->getId();

            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` = ".$seller_id;

            $ventes = $repository->loadVentes($sql_cluster,$num_commande);

        }

        if($request->get('partner')){
            $partner=$request->get('partner');
            $sql_cluster .= ' AND `lib_pdv` = "' . addslashes($partner) . '"';

            $ventes = $repository->loadVentesPartner($sql_cluster,$num_commande);
        }
        if($request->get('director')){
            $director_id=$request->get('director');
            $sql_cluster.=" AND A.num_cbl = F.num_commande AND A.`user_id` IN(SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = ".$director_id." OR id = ".$director_id.")) ";
            if($request->get('cluster')){

                $cluster=$request->get('cluster');
                $sql_cluster .= ' AND F.`code_cluster`= "' . $cluster.'"';

            }
            $ventes = $repository->loadVentes($sql_cluster,$num_commande);

        }
        if($request->get('manager')){
            $manager_id=$request->get('manager');
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id = ".$manager_id.") ";
            if($request->get('cluster')){

                $cluster=$request->get('cluster');
                $sql_cluster .= ' AND F.`code_cluster`= "' . $cluster.'"';

            }
            $ventes = $repository->loadVentes($sql_cluster,$num_commande);

        }


        return $this->render('production/details.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'production',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'role'=>$role,
            'ventes' => $ventes,
            'm' => $month,
            'yearChoice' => $year,
            'monthSelected' => $monthSelected,
            'list_partenaires'=>$list_partenaires,
            'list_cluster'=>$list_cluster,
            'list_directeurs'=>$list_directeurs,
            'list_managers'=>$list_managers,
        ]);
    }

    /**
     * @Route("/declaratif", name="declaratif")
     */
    public function production_declaratif(Request $request, UserInterface $user){

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $cpv = $user->getCpvCourtierExploitant();
        $userId = $user->getId();

        $repository = $this->getDoctrine()->getRepository(NmdTrack::class);
        $repository_partenaire=$this->getDoctrine()->getRepository(NmdPartner::class);
        $repository_products=$this->getDoctrine()->getRepository(NmdProduct::class);

        $list_partenaires = [];
        $list_directeurs = [];
        $list_managers = [];
        $allSalesListArray = [];

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $monthChoice=$request->get('monthChoise');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }

        // Get All Sales
        $allSalesList = $repository->AllSales($cpv);

        foreach ($allSalesList as $key => $value) {
            array_push($allSalesListArray, $value['num_commande']);
        }

        $directorId = $request->get('director');
        $managerId = $request->get('manager');

        if($directorId != null){
            $directorId = $request->get('director');
        }else{
            $directorId = "all";
        }

        if($managerId != null){
            $managerId = $request->get('manager');
        }else{
            $managerId = "all";
        }

        if ($this->security->isGranted('ROLE_COMPANY')) {

            $list_directeurs = $repository_partenaire->DirecteursForPartner($cpv);
            $declaratif = $repository->LoadTrackCompanyDataTableOldByDate($cpv, $directorId , $month, $year);
        }


        if ($this->security->isGranted('ROLE_DIRECTOR')) {

            $list_managers = $repository_partenaire->managersForDirecteur($userId);

            $declaratif = $repository->LoadTrackDirectorDataTableByDate($userId, $managerId, $month, $year);
        }

        if ($this->security->isGranted('ROLE_MANAGER')) {

            $declaratif = $repository->LoadTrackManagerDataTableByDate($userId, $month, $year);

        }

        // For all uers responsable
        $usersInfosForSellsView_complete = $this->getDoctrine()->getRepository(User::class)->UsersInfosForSellsView_complete($cpv);

        return $this->render('production/tracksByDate.html.twig', [
            'pageTitle' => 'Déclaratif',
            'rootTemplate' => 'production',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'm' => $month,
            'yearChoice' => $year,
            'declaratif' => $declaratif,
            'list_directeurs' => $list_directeurs,
            'list_managers' => $list_managers,
            'directorId' => $directorId,
            'managerId' => $managerId,
            'allSalesListArray' => $allSalesListArray,
            'products'=>$repository_products->allProducts(),

            'usersInfosForSellsView_complete' => $usersInfosForSellsView_complete
        ]);

    }

    /**
     * @Route("/declaratif/details", name="declaratif_details")
     */
    public function production_declaratif_details(Request $request, UserInterface $user){

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $cpv = $user->getCpvCourtierExploitant();
        $userId = $user->getId();

        $repository = $this->getDoctrine()->getRepository(NmdTrack::class);
        $repository_partenaire=$this->getDoctrine()->getRepository(NmdPartner::class);
        $repository_products=$this->getDoctrine()->getRepository(NmdProduct::class);

        $list_partenaires = [];
        $list_directeurs = [];
        $list_managers = [];
        $allSalesListArray = [];

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $monthChoice=$request->get('monthChoise');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }

        // Get All Sales
        $allSalesList = $repository->AllSales($cpv);

        foreach ($allSalesList as $key => $value) {
            array_push($allSalesListArray, $value['num_commande']);
        }

        $directorId = $request->get('director');
        $managerId = $request->get('manager');
        $dateFilter = $request->get('dateFilter');

        if($directorId != null){
            $directorId = $request->get('director');
        }else{
            $directorId = "all";
        }

        if($managerId != null){
            $managerId = $request->get('manager');
        }else{
            $managerId = "all";
        }

        $sqlDate="";
        if($dateFilter!=null and $dateFilter!='' and $dateFilter!='all'){
            $sqlDate.=" AND DATE(A.date)='$dateFilter' ";
        }
        if ($this->security->isGranted('ROLE_COMPANY')) {

            $list_directeurs = $repository_partenaire->DirecteursForPartner($cpv);
            $declaratif = $repository->LoadTrackCompanyDataTableOld($cpv, $directorId , $month, $year,$sqlDate);
        }


        if ($this->security->isGranted('ROLE_DIRECTOR')) {

            $list_managers = $repository_partenaire->managersForDirecteur($userId);

            $declaratif = $repository->LoadTrackDirectorDataTable($userId, $managerId, $month, $year, null, null, null, null,$sqlDate);
        }

        if ($this->security->isGranted('ROLE_MANAGER')) {

            $declaratif = $repository->LoadTrackManagerDataTable($userId, $month, $year, null, null, null, null,$sqlDate);

        }

        // For all uers responsable
        $usersInfosForSellsView_complete = $this->getDoctrine()->getRepository(User::class)->UsersInfosForSellsView_complete($cpv);

        return $this->render('production/tracks.html.twig', [
            'pageTitle' => 'Déclaratif',
            'rootTemplate' => 'production',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'm' => $month,
            'yearChoice' => $year,
            'declaratif' => $declaratif,
            'list_directeurs' => $list_directeurs,
            'list_managers' => $list_managers,
            'directorId' => $directorId,
            'managerId' => $managerId,
            'allSalesListArray' => $allSalesListArray,
            'products'=>$repository_products->allProducts(),

            'usersInfosForSellsView_complete' => $usersInfosForSellsView_complete
        ]);

    }

    /**
     * @Route("/histogramme", name="histogramme")
     */
    public function venteshistogrammeV2(Request $request,UserInterface $user)
    {

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);
        foreach ($user->getRoles() as $r) {
            $role = $r;
        }
        $userCpv = $user->getCpvCourtierExploitant();

        $repository=$this->getDoctrine()->getRepository(NmdCbl::class);
        $repository_partenaire=$this->getDoctrine()->getRepository(NmdPartner::class);

        $list_partenaires=[];
        $list_cluster=[];
        $list_directeurs=[];
        $list_managers=[];

        $ventesSignupTypeEcom=[];
        $ventesLblOffre=[];
        $ventesStatusGlobalRacc=[];
        $ventesTypesKO=[];

        $num_commande = "";
        $sql_cluster = "";
        $join_left="";
        $monthSelected="";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $monthChoice=$request->get('monthChoise');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }

        $num_commande .= ' YEAR(F.`date_vente_valid_b`) = '.$year;


        if($role=='ROLE_SFR'){

            $list_partenaires=$repository_partenaire->selectAllPartnerInParner($user->getId());


            $ventes = $repository->TotalSellsDetailsGroupByMonth($num_commande);
            $ventesSignupTypeEcom = $repository->TotalSellsDetailsGroupBySignupTypeEcom($num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->TotalSellsDetailsGroupByLblOffre($num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->TotalSellsDetailsGroupByStatusGlobalRacc($num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->TotalSellsDetailsGroupByEtatDeLaVente($num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");

        }
        elseif ($role=='ROLE_COMPANY'){

            $list_directeurs=$repository_partenaire->DirecteursForPartnerById($user->getId());
            $_org_partner=$repository_partenaire->findOneBy(array('myaccountId'=>$user->getId()));
            if($_org_partner) $partner=$_org_partner->getPartner();
            else $partner=$user->getFirstname();
            $sql_cluster = ' AND `lib_pdv` = "' . addslashes($partner) . '"';

            $ventes = $repository->loadVentesPartnerGroupByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesPartnerGroupBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesPartnerGroupByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesPartnerGroupByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesPartnerGroupByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");


        }
        elseif ($role=='ROLE_DIRECTOR'){

            $director_id=$user->getId();
            $list_managers=$repository_partenaire->managersForDirecteur($director_id);
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` 
                           IN(SELECT id FROM `user` WHERE `parent` 
                           IN(SELECT id FROM `user` WHERE `parent` = ".$director_id." OR id = ".$director_id.")) 
                           AND F.code_point_vente = ".$userCpv."
                           ";

            $ventes = $repository->loadVentesByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");


        }
        elseif ($role=='ROLE_MANAGER'){

            $manager_id=$user->getId();
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` 
            IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id = ".$manager_id.") 
            AND F.code_point_vente = ".$userCpv."
            
            ";

            $ventes = $repository->loadVentesByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");


        }
        elseif ($role=='ROLE_SELLER'){
            $seller_id=$user->getId();

            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` = ".$seller_id;

            $ventes = $repository->loadVentesByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");


        }

        if($request->get('partner')){
            $partner=$request->get('partner');
            $sql_cluster .= ' AND `lib_pdv` = "' . addslashes($partner) . '"';

            $ventes = $repository->loadVentesPartnerGroupByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesPartnerGroupBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesPartnerGroupByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesPartnerGroupByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesPartnerGroupByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");

        }
        if($request->get('director')){
            $director_id=$request->get('director');
            $sql_cluster.=" AND A.num_cbl = F.num_commande AND A.`user_id` IN(SELECT id FROM `user` WHERE `parent` IN(SELECT id FROM `user` WHERE `parent` = ".$director_id." OR id = ".$director_id.")) ";
            if($request->get('cluster')){

                $cluster=$request->get('cluster');
                $sql_cluster .= ' AND F.`code_cluster`= "' . $cluster.'"';

            }
            $ventes = $repository->loadVentesByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");


        }
        if($request->get('manager')){
            $manager_id=$request->get('manager');
            $sql_cluster=" AND A.num_cbl = F.num_commande AND A.`user_id` IN(SELECT id FROM `user` WHERE `parent` = ".$manager_id." OR id = ".$manager_id.") ";
            if($request->get('cluster')){

                $cluster=$request->get('cluster');
                $sql_cluster .= ' AND F.`code_cluster`= "' . $cluster.'"';

            }
            $ventes = $repository->loadVentesByMonth($sql_cluster,$num_commande);
            $ventesSignupTypeEcom = $repository->loadVentesBySignupTypeEcom($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesLblOffre = $repository->loadVentesByLblOffre($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesStatusGlobalRacc = $repository->loadVentesByStatusGlobalRacc($sql_cluster,$num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month'");
            $ventesTypesKO = $repository->loadVentesByEtatDeLaVente($sql_cluster, $num_commande." AND MONTH(F.`date_vente_valid_b`) = '$month' AND statut_global_racc='Racco KO'");


        }


        return $this->render('production/histogramme.html.twig', [

            'pageTitle' => 'Production',
            'rootTemplate' => 'production',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'role'=>$role,
            'ventes' => $ventes,
            'm' => $month,
            'yearChoice' => $year,
            'monthSelected' => $monthSelected,
            'list_partenaires'=>$list_partenaires,
            'list_cluster'=>$list_cluster,
            'list_directeurs'=>$list_directeurs,
            'list_managers'=>$list_managers,

            'ventesSignupTypeEcom'=>$ventesSignupTypeEcom,
            'ventesLblOffre'=>$ventesLblOffre,
            'ventesStatusGlobalRacc'=>$ventesStatusGlobalRacc,
            'ventesTypesKO'=>$ventesTypesKO,
        ]);
    }


}
