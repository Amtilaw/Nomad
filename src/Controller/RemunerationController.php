<?php

namespace App\Controller;

use App\Entity\NmdBonusMalusQuery;
use App\Entity\NmdCbl;
use App\Entity\NmdPaid;
use App\Entity\NmdPartner;
use App\Entity\NmdPointSteps;
use App\Entity\NmdPrNeuvesPassage;
use App\Entity\NmdProduct;
use App\Entity\NmdQualityBonus;
use App\Entity\NmdRemunerationStatus;
use App\Entity\NmdRhManagement;
use App\Entity\NmdTrack;
use App\Entity\NmdValorisationObjectif;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @Route("/remuneration", name="remuneration_")
*/
class RemunerationController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request, UserInterface $userI): Response
    {

        $userId = $request->get('userId');
        $sellerId = $request->get('sellerId');
        $monthChoice = $request->get('monthChoise');
        $yearChoice = $request->get('yearChoice');
        $libelleQuery = $request->get('libelleQuery');
        if($libelleQuery==null or $libelleQuery=='') {$libelleQuery="date_vente_valid_b";}
        if($userId==null or $userId==''){
            $rolesI=$userI->getRoles();
            foreach ($rolesI as $rI) {
                $roleI = $rI;
            }
            if ($roleI=='ROLE_FINANCIAL') {
                $userId=$userI->getParent();
            }
            if ($roleI=='ROLE_COMPANY') {
                $userId=$userI->getId();
            }

        }

        $now=new \DateTime();
        $month=$now->format('m');
        $yearChoise=$now->format('Y');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $yearChoise=$yearChoice;
        }

        if(intval($month>1)){$month_1=$month - 1;$year_1=$yearChoise; }
        else{$month_1=12;$year_1=$yearChoise - 1;}


        $repository_partner=$this->getDoctrine()->getRepository(NmdPartner::class);
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
        $roles=$user->getRoles();
        foreach ($roles as $r) {
            $role = $r;
        }

        $sqlSeconde="";
        if($sellerId!='' and $sellerId!=null){
            $sqlSeconde.=" AND A.user_id='$sellerId' ";
        }


        $listes=[];
        $objectifsList=[];

        $countventeTotalRealiseDirector=[];
        $ventesList=[];
        $regroupementMonthventesListValide=[];
        $regroupementsellersVentes=[];
        $regroupementmanagersVentes=[];
        $regroupementdirectorsVentes=[];
        $regroupementMonthventesListRaccorde=[];
        $regroupementDayVentes=[];
        $regroupementDateVentes=[];
        $regroupementEtatVentes=[];
        $regroupementMonthventesListLibelleRemun=[];
        $rhManagementUser=$this->getDoctrine()->getRepository(NmdRhManagement::class)->findOneBy(array(
            'userId'=>$userId
        ));
        if ($role=='ROLE_COMPANY') {

            $cpv=$user->getCpvCourtierExploitant();
            $parentId=$user->getParent();
            $operatorId=$user->getOperatorId();

            $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompany($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $regroupementsellersVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupBySellers($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $regroupementmanagersVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByManager($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $regroupementdirectorsVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByDirector($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $regroupementDayVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByDay($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $regroupementMonthventesListLibelleRemun=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyByLibelle($cpv,$parentId,$month,$yearChoise, $libelleQuery,"", $operatorId);
            $regroupementMonthventesListValide=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByMonth($cpv,$parentId,$month,$yearChoise, 'date_vente_valid_b',"", $operatorId);
            $regroupementMonthventesListRaccorde=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByMonth($cpv,$parentId,$month,$yearChoise, 'date_activ_com_h',"", $operatorId);
            $regroupementDateVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByDate($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $regroupementEtatVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyGroupByEtatDeLaVente($cpv,$parentId,$month,$yearChoise, $libelleQuery, "", $operatorId);
            $banusMalusTotal=$this->bonusMalusCompany($cpv,$userId,$month,$yearChoise,$user->getOperatorId(), $libelleQuery);

            $totalVente_1=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalCompany($cpv,$month_1,$year_1, $libelleQuery);
            $countventeTotalRealiseDirector=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalRealiseCompany($cpv,$month,$yearChoise, $libelleQuery);
            $listesClusters=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompanyClusters($cpv,$userId,$month,$yearChoise, $libelleQuery);
            foreach ($listesClusters as $itemCluster){
                $listes[]=[
                    'data'=>$itemCluster,
                    'totalPrises'=>$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class)->totalPrisesNeuvesCompany($cpv,$month,$yearChoise, $itemCluster['code_cluster'])['total'],
                ];
            }

        }
        else{
            if($rhManagementUser and $rhManagementUser->getContractType()=='presta'){
                if ($role=='ROLE_DIRECTOR') {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirector($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementsellersVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupBySellers($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementmanagersVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupByManager($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementDayVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupByDay($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementMonthventesListLibelleRemun=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorByLibelle($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementMonthventesListValide=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupByMonth($userId,$month,$yearChoise, 'date_vente_valid_b');
                    $regroupementMonthventesListRaccorde=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupByMonth($userId,$month,$yearChoise, 'date_activ_com_h');
                    $banusMalusTotal=$this->bonusMalusDir($userId,$month,$yearChoise,$user->getOperatorId(), $libelleQuery);

                    $regroupementDateVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupByDate($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementEtatVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorGroupByEtatDeLaVente($userId,$month,$yearChoise, $libelleQuery);

                    $totalVente_1=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalDirector($userId,$month_1,$year_1, $libelleQuery);
                    $countventeTotalRealiseDirector=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalRealiseDirector($userId,$month,$yearChoise, $libelleQuery);
                    $listesClusters=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirectorClusters($userId,$month,$yearChoise, $libelleQuery);
                    foreach ($listesClusters as $itemCluster){
                        $listes[]=[
                            'data'=>$itemCluster,
                            'totalPrises'=>$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class)->totalPrisesNeuvesDirector($userId,$month,$yearChoise, $itemCluster['code_cluster'])['total'],
                        ];
                    }

                }
                if ($role=='ROLE_MANAGER') {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManager($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementsellersVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupBySellers($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementDayVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByDay($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementMonthventesListLibelleRemun=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerByLibelle($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementMonthventesListValide=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByMonth($userId,$month,$yearChoise, 'date_vente_valid_b', $sqlSeconde);
                    $regroupementMonthventesListRaccorde=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByMonth($userId,$month,$yearChoise, 'date_activ_com_h',$sqlSeconde);
                    $banusMalusTotal=$this->bonusMalusManager($userId,$month,$yearChoise,$user->getOperatorId(), $libelleQuery);

                    $totalVente_1=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalManager($userId,$month_1,$year_1, $libelleQuery, $sqlSeconde);
                    $listesClusters=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerClusters($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    foreach ($listesClusters as $itemCluster){
                        $listes[]=[
                            'data'=>$itemCluster,
                            'totalPrises'=>0,
                        ];
                    }

                    $countventeTotalRealiseDirector=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalRealiseManager($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementDateVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByDate($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementEtatVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByEtatDeLaVente($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);

                }
                if ($role=='ROLE_SELLER') {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSeller($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementDayVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByDay($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementMonthventesListLibelleRemun=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerByLibelle($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementMonthventesListValide=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByMonth($userId,$month,$yearChoise, 'date_vente_valid_b');
                    $regroupementMonthventesListRaccorde=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByMonth($userId,$month,$yearChoise, 'date_activ_com_h');
                    $banusMalusTotal=$this->bonusMalusSeller($userId,$month,$yearChoise,$user->getOperatorId(), $libelleQuery);

                    $totalVente_1=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalSeller($userId,$month_1,$year_1, $libelleQuery);
                    $listesClusters=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerClusters($userId,$month,$yearChoise, $libelleQuery);
                    foreach ($listesClusters as $itemCluster){
                        $listes[]=[
                            'data'=>$itemCluster,
                            'totalPrises'=>0,
                        ];
                    }

                    $countventeTotalRealiseDirector=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalRealiseSeller($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementDateVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByDate($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementEtatVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByEtatDeLaVente($userId,$month,$yearChoise, $libelleQuery);

                }
            }
            else{
                if ($role=='ROLE_MANAGER') {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManager($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementsellersVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupBySellers($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementDayVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByDay($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementMonthventesListLibelleRemun=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerByLibelle($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $regroupementMonthventesListValide=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByMonth($userId,$month,$yearChoise, 'date_vente_valid_b', $sqlSeconde);
                    $regroupementMonthventesListRaccorde=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByMonth($userId,$month,$yearChoise, 'date_activ_com_h', $sqlSeconde);
                    $banusMalusTotal=$this->bonusMalusManager($userId,$month,$yearChoise,$user->getOperatorId(), $libelleQuery);

                    $totalVente_1=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalManager($userId,$month_1,$year_1, $libelleQuery, $sqlSeconde);
                    $listesClusters=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerClusters($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    foreach ($listesClusters as $itemCluster){
                        $listes[]=[
                            'data'=>$itemCluster,
                            'totalPrises'=>0,
                        ];
                    }

                    $countventeTotalRealiseDirector=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalRealiseManager($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementDateVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByDate($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementEtatVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManagerGroupByEtatDeLaVente($userId,$month,$yearChoise, $libelleQuery);

                }
                else {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSeller($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementDayVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByDay($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementMonthventesListLibelleRemun=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerByLibelle($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementMonthventesListValide=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByMonth($userId,$month,$yearChoise, 'date_vente_valid_b');
                    $regroupementMonthventesListRaccorde=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByMonth($userId,$month,$yearChoise, 'date_activ_com_h');
                    $banusMalusTotal=$this->bonusMalusSeller($userId,$month,$yearChoise,$user->getOperatorId(), $libelleQuery);

                    $totalVente_1=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalSeller($userId,$month_1,$year_1, $libelleQuery);
                    $listesClusters=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerClusters($userId,$month,$yearChoise, $libelleQuery);
                    foreach ($listesClusters as $itemCluster){
                        $listes[]=[
                            'data'=>$itemCluster,
                            'totalPrises'=>0,
                        ];
                    }

                    $countventeTotalRealiseDirector=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalRealiseSeller($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementDateVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByDate($userId,$month,$yearChoise, $libelleQuery);
                    $regroupementEtatVentes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSellerGroupByEtatDeLaVente($userId,$month,$yearChoise, $libelleQuery);

                }
            }
        }


        $totalPoints=0;
        $pallierName='';
        $taux=[];
        $tauxSignupTypeEcom=[];
        $tauxLblOffre=[];

        $remunerationStatus=$this->getDoctrine()->getRepository(NmdRemunerationStatus::class)->findAll();
        foreach ($remunerationStatus as $remStatus){
            $taux[$remStatus->getRemStatus()]=[
                'status'=>$remStatus->getRemStatus(),
                'total'=>0
            ];
        }

        foreach ($ventes as $itemV){
            $tauxSignupTypeEcom[$itemV['signup_type_ecom']]=[
                'status'=>$itemV['signup_type_ecom'],
                'total'=>0
            ];
            $tauxLblOffre[$itemV['lbl_offre']]=[
                'status'=>$itemV['lbl_offre'],
                'total'=>0
            ];
        }

        foreach ($ventes as $item){
            $totalPoints+=($item['total'])*(($item['coefficient']>0)?($item['coefficient']):0)*(($item['cumul_steps']>0)?($item['cumul_steps']):0);

            if($item['rem_percent']==null or $item['rem_percent']==''){
                $item['rem_percent']=100;
            }
            $ventesList[]=$item;

            if(array_key_exists($item['statut_global_racc'],$taux)) $taux[$item['statut_global_racc']]['total']+=$item['total'];
            if(array_key_exists($item['signup_type_ecom'],$tauxSignupTypeEcom)) $tauxSignupTypeEcom[$item['signup_type_ecom']]['total']+=$item['total'];
            if(array_key_exists($item['lbl_offre'],$tauxLblOffre)) $tauxLblOffre[$item['lbl_offre']]['total']+=$item['total'];
        }

        $result=$this->getPallier($role,$userId,$totalPoints,$user,$month);

        // Objectifs clusters

        foreach ($listes as $item){
            $prct=0;
            if($libelleQuery=='date_vente_valid_b'){
                $prct=($item['data']['objectives_vv']>0)?(($item['data']['total'] *100)/$item['data']['objectives_vv']):($item['data']['total'] *100);
            }elseif($libelleQuery=='date_activ_com_h') {
                $prct=($item['data']['objectives_vr']>0)?(($item['data']['total'] *100)/$item['data']['objectives_vr']):($item['data']['total'] *100);
            }

            if($prct>=100){
                $valorisation=$this->getDoctrine()->getRepository(NmdValorisationObjectif::class)->valorisationSup($userId, $item['data']['product_id']);
            }else{
                $valorisation=$this->getDoctrine()->getRepository(NmdValorisationObjectif::class)->valorisationByVolume($userId, $prct, $item['data']['product_id']);
            }

            $objectifsList[]=[
                'total'=>$item['data']['total'],
                'total_price'=>($item['data']['total_price']>0)?$item['data']['total_price']:0,
                'code_cluster'=>$item['data']['code_cluster'],
                'libelle_cluster'=>$item['data']['libelle_cluster'],
                'objectives_vv'=>$item['data']['objectives_vv'],
                'objectives_vr'=>$item['data']['objectives_vr'],
                'signup_type_ecom'=>$item['data']['signup_type_ecom'],
                'lbl_offre'=>$item['data']['lbl_offre'],
                'prct'=>$prct,
                'valorisation'=>(!empty($valorisation) and $valorisation!=false)?$valorisation:null,
                'totalPrises'=>($item['totalPrises']>0)?$item['totalPrises']:0,
            ];
        }

        $ventesByDays=[
            0=>0,
            1=>0,
            2=>0,
            3=>0,
            4=>0,
            5=>0,
            6=>0
        ];
        foreach ($regroupementDayVentes as $itemDay){
            $ventesByDays[$itemDay['day']]=$itemDay['total'];
        }

        $totalV=0;
        foreach ($ventes as $item){
            $totalV+=intval($item['total']);
        }
        return $this->render('remuneration/index.html.twig', [
            'pageTitle' => 'Rémunération',
            'rootTemplate' => 'remuneration',
            'rootPage' => 'vendeurs',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'm' => $month,
            'yearChoise' => $yearChoise,
            'userId' => $userId,
            'libelleQuery' => $libelleQuery,
            'role' => $role,
            'ventes' => $ventesList,
            'totalPoints' => $totalPoints,
            'regroupementMonthventesListValide' => $regroupementMonthventesListValide,
            'regroupementMonthventesListRaccorde' => $regroupementMonthventesListRaccorde,
            'regroupementMonthventesListLibelleRemun' => $regroupementMonthventesListLibelleRemun,
            'user' => $user,
            'pallierName' => $result['pallierName'],
            'pallier' => $result['pallier'],
            'pallierSup' => $result['pallierSup'],
            'taux' => $taux,
            'tauxSignupTypeEcom' => $tauxSignupTypeEcom,
            'tauxLblOffre' => $tauxLblOffre,
            'banusMalusTotal' => $banusMalusTotal,
            'totalV' => $totalV,

            'objectifs' => $objectifsList,
            'totalVente_1' => $totalVente_1['total'],
            'regroupementsellersVentes' => $regroupementsellersVentes,
            'regroupementmanagersVentes' => $regroupementmanagersVentes,
            'regroupementdirectorsVentes' => $regroupementdirectorsVentes,
            'regroupementDayVentes' => $ventesByDays,
            'countventeTotalRealiseDirector' => $countventeTotalRealiseDirector,

            'regroupementDateVentes' => $regroupementDateVentes,
            'regroupementEtatVentes' => $regroupementEtatVentes,
        ]);
    }

    /**
     * @Route("/detailbrouillon", name="detailremunerationBruillon")
     */
    public function detailRemunerationBruillon(Request $request, UserInterface $userI){


        $userId = $request->get('userId');
        $sellerId = $request->get('sellerId');
        $month = $request->get('monthChoise');
        $yearChoise = $request->get('yearChoice');
        $libelleQuery = $request->get('libelleQuery');
        if($libelleQuery==null or $libelleQuery=='') {$libelleQuery="date_vente_valid_b";}

        $signup_type_ecom = $request->get('signup_type_ecom');
        $lbl_offre = $request->get('lbl_offre');
        $statut_global_racc = $request->get('statut_global_racc');
        $remuneration_name = $request->get('remuneration_name');
        $month_filter = $request->get('month_filter');
        $month_selected = $request->get('month_selected');
        $weekDay = $request->get('weekDay');
        $ventes_prises_neuves = $request->get('ventes_prises_neuves');
        $date_cbl = $request->get('date_cbl');
        $etat_de_la_vente = $request->get('etat_de_la_vente');

        // Filtre au de suus de table
        $filter_detail = $request->get('filter_detail');
        $value_filter_detail = $request->get('value_filter_detail');


        $sqlSeconde='';
        if($signup_type_ecom!='' and $signup_type_ecom!=null){
            $sqlSeconde.=" AND signup_type_ecom='$signup_type_ecom' ";
        }
        if($lbl_offre!='' and $lbl_offre!=null){
            $sqlSeconde.=" AND lbl_offre='$lbl_offre' ";
        }
        if($filter_detail!='' and $filter_detail!=null and $value_filter_detail!='' and $value_filter_detail!=null){
            $value_filter_detailArray=explode(',',$value_filter_detail);
            if(count($value_filter_detailArray)>1){
                $i=0;
                $sqlSeconde.=" AND (";
                foreach ($value_filter_detailArray as $is){
                    if(++$i === count($value_filter_detailArray)) {
                        $sqlSeconde.=" `$filter_detail`='$is'";
                    }else{
                        $sqlSeconde.=" `$filter_detail`='$is' OR ";
                    }
                }
                $sqlSeconde.=")";
            }else{
                $sqlSeconde.=" AND `$filter_detail`='$value_filter_detail' ";
            }
        }
        if($statut_global_racc!='' and $statut_global_racc!=null){
            $sqlSeconde.=" AND statut_global_racc='$statut_global_racc' ";
        }
        if($remuneration_name!=null){
            if($remuneration_name=='NULL'){
                $sqlSeconde.=" AND (remuneration_name IS NULL OR remuneration_name='') ";
            }else{
                $sqlSeconde.=" AND remuneration_name='$remuneration_name' ";
            }
        }
        if($month_filter!='' and $month_filter!=null){
            $sqlSeconde.=" AND MONTH(`$month_filter`)=$month_selected ";
        }
        if($weekDay!='' and $weekDay!=null){
            $sqlSeconde.=" AND WEEKDAY(`$libelleQuery`)=$weekDay ";
        }
        if($date_cbl!='' and $date_cbl!=null){
            $sqlSeconde.=" AND DATE(`$libelleQuery`)=DATE('$date_cbl') ";
        }
        if($etat_de_la_vente!='' and $etat_de_la_vente!=null){
            $sqlSeconde.=" AND etat_de_la_vente='$etat_de_la_vente' ";
        }
        if($sellerId!='' and $sellerId!=null){
            $sqlSeconde.=" AND A.user_id='$sellerId' ";
        }



        $repository_partner=$this->getDoctrine()->getRepository(NmdPartner::class);
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
        $roles=$user->getRoles();
        foreach ($roles as $r) {
            $role = $r;
        }


        $ventesList=[];
        $detailventes=[];
        $rhManagementUser=$this->getDoctrine()->getRepository(NmdRhManagement::class)->findOneBy(array(
            'userId'=>$userId
        ));
        if ($role=='ROLE_COMPANY') {

            $cpv=$user->getCpvCourtierExploitant();
            $parentId=$user->getParent();
            $operatorId=$user->getOperatorId();

            $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeCompany($cpv,$parentId,$month,$yearChoise, $libelleQuery, $sqlSeconde, $operatorId);
            $detailventes=$this->getDoctrine()->getRepository(NmdTrack::class)->detailcountventeCompany($cpv,$parentId,$month,$yearChoise, $libelleQuery, $sqlSeconde, $operatorId);

        }
        else{
            if($rhManagementUser and $rhManagementUser->getContractType()=='presta'){
                if ($role=='ROLE_DIRECTOR') {

                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeDirector($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $detailventes=$this->getDoctrine()->getRepository(NmdTrack::class)->detailcountventeDirector($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);

                }
            }else{
                if ($role=='ROLE_MANAGER') {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeManager($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $detailventes=$this->getDoctrine()->getRepository(NmdTrack::class)->detailcountventeManager($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                }elseif ($role=='ROLE_SELLER') {
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSeller($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $detailventes=$this->getDoctrine()->getRepository(NmdTrack::class)->detailcountventeSeller($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                }else{
                    $ventes=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeSeller($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);
                    $detailventes=$this->getDoctrine()->getRepository(NmdTrack::class)->detailcountventeSeller($userId,$month,$yearChoise, $libelleQuery, $sqlSeconde);

                }

            }
        }


        $totalPoints=0;

        foreach ($detailventes as $item){

            $totalPoints+=(($item['coefficient']>0)?($item['coefficient']):0) * (($item['cumul_steps']>0)?($item['cumul_steps']):0);

            if($item['rem_percent']==null or $item['rem_percent']==''){
                $item['rem_percent']=100;
            }
            $ventesList[]=$item;

        }

        $result=$this->getPallier($role,$userId,$totalPoints,$user,$month);

        $details=[];
        foreach ($detailventes as $itemV){
            $paidVR=0;
            $paidVV=0;
            $paids=$this->getDoctrine()->getRepository(NmdPaid::class)->findBy(array(
                'commandNumber'=>$itemV['num_commande']
            ));
            foreach ($paids as $itemP){
                if(strtoupper($itemP->getPaymentStatus())=='A_VERSER' and (strtoupper($itemP->getPaymentType())=='OFFRE' OR strtoupper($itemP->getPaymentType())=='MIG OFFRE (RAC)')) $paidVR=1;
                if(strtoupper($itemP->getPaymentStatus())=='A_VERSER' and strtoupper($itemP->getPaymentType())=='OFFRE (VV)') $paidVV=1;
            }

            $itemV['paidVR']=$paidVR;
            $itemV['paidVV']=$paidVV;

            $details[]=$itemV;
        }

        return $this->render('remuneration/details.html.twig',[
            'pageTitle' => 'Rémunération',
            'rootTemplate' => 'remuneration',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'm' => $month,
            'yearChoise' => $yearChoise,
            'ventes' => $ventesList,
            'detailventes' => $details,
            'totalPoints' => $totalPoints,
            'user' => $user,
            'pallierName' => $result['pallierName'],
            'pallier' => $result['pallier'],
            'pallierSup' => $result['pallierSup'],

        ]);
    }

    private function getPallier($role,$userId,$totalPoints,$user,$month){
        $result=[];
        if ($role=='ROLE_COMPANY') {
            $operatorId=$user->getOperatorId();

            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolumeCompany($user->getParent(),$operatorId,$totalPoints,$month);
            $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSupCompany($user->getParent(),$operatorId,$month);
            if($pallier==false or empty($pallier)){
                if(($pallierSup==false or empty($pallierSup))){
                    $pallierName='----';
                    $pallier=[
                        'coefficient'=>0
                    ];
                    $pallierSup=[
                        'step_start_value'=>0
                    ];
                }else{
                    $pallierName=$pallierSup['step_name'];
                    $pallier=$pallierSup;
                }
            }else{
                $pallierName=$pallier['step_name'];
            }
        }
        if ($role=='ROLE_DIRECTOR') {
            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($userId,$totalPoints,$month);
            $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($userId,$month);
            if($pallier==false or empty($pallier)){
                if(($pallierSup==false or empty($pallierSup))){
                    $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($user->getParent(),$totalPoints,$month);
                    $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($user->getParent(),$month);
                    if($pallier==false or empty($pallier)){
                        if(($pallierSup==false or empty($pallierSup))){
                            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolumeMax($user->getParent(),$totalPoints,$month);
                            $pallierName=$pallier['step_name'];
                        }else{
                            $pallierName=$pallierSup['step_name'];
                            $pallier=$pallierSup;
                        }
                    }else{
                        $pallierName=$pallier['step_name'];
                    }
                }else{
                    $pallierName=$pallierSup['step_name'];
                    $pallier=$pallierSup;
                }
            }else{
                $pallierName=$pallier['step_name'];
            }
        }
        if ($role=='ROLE_MANAGER') {
            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($userId,$totalPoints,$month);
            $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($userId,$month);
            if(($pallier==false or empty($pallier))){
                if(($pallierSup==false or empty($pallierSup))){
                    $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($user->getParent(),$totalPoints,$month);
                    $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($user->getParent(),$month);
                    if($pallier==false or empty($pallier)){
                        if(($pallierSup==false or empty($pallierSup))){
                            $director=$this->getDoctrine()->getRepository(User::class)->find($user->getParent());
                            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($director->getParent(),$totalPoints,$month);
                            $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($director->getParent(),$month);
                            if($pallier==false or empty($pallier)){
                                if(($pallierSup==false or empty($pallierSup))){
                                    $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolumeMax($director->getParent(),$totalPoints,$month);
                                    $pallierName=$pallier['step_name'];
                                }else{
                                    $pallierName=$pallierSup['step_name'];
                                    $pallier=$pallierSup;
                                }
                            }else{
                                $pallierName=$pallier['step_name'];
                            }
                        }else{
                            $pallierName=$pallierSup['step_name'];
                            $pallier=$pallierSup;
                        }
                    }else{
                        $pallierName=$pallier['step_name'];
                    }
                }else{
                    $pallierName=$pallierSup['step_name'];
                    $pallier=$pallierSup;
                }
            }else{
                $pallierName=$pallier['step_name'];
            }
        }
        if ($role=='ROLE_SELLER') {
            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($userId,$totalPoints,$month);
            $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($userId,$month);
            if($pallier==false or empty($pallier)){
                $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($user->getParent(),$totalPoints,$month);
                $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($user->getParent(),$month);
                if($pallier==false or empty($pallier)){
                    $manager=$this->getDoctrine()->getRepository(User::class)->find($user->getParent());
                    $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($manager->getParent(),$totalPoints,$month);
                    $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($manager->getParent(),$month);
                    if($pallier==false or empty($pallier)){
                        $director=$this->getDoctrine()->getRepository(User::class)->find($manager->getParent());
                        $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolume($director->getParent(),$totalPoints,$month);
                        $pallierSup=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepSup($director->getParent(),$month);
                        if($pallier==false or empty($pallier)){
                            $pallier=$this->getDoctrine()->getRepository(NmdPointSteps::class)->stepsByVolumeMax($director->getParent(),$totalPoints,$month);
                            $pallierName=$pallier['step_name'];
                        }else{
                            $pallierName=$pallier['step_name'];
                        }
                    }else{
                        $pallierName=$pallier['step_name'];
                    }
                }else{
                    $pallierName=$pallier['step_name'];
                }
            }else{
                $pallierName=$pallier['step_name'];
            }
        }

        return [
            'pallierName' => $pallierName,
            'pallier' => $pallier,
            'pallierSup' => $pallierSup
        ];
    }
    private function bonusMalusCompany($cpv,$user_id,$month,$year,$operator_id, $libelleQuery){

        $total_bonus_malus=0;
        $qualitees=[];
        $r_quality_bonus=$this->getDoctrine()->getRepository(NmdQualityBonus::class);
        $r_bonus_malus=$this->getDoctrine()->getRepository(NmdBonusMalusQuery::class);
        $list_bonus_malus=$r_bonus_malus->findAll();

        $total_vente_valide_current_month=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalCompany($cpv,$month,$year, $libelleQuery)['total'];
        foreach ($list_bonus_malus as $_item_bonus_malus){

            $data_status=$r_bonus_malus->executeRequestBonusMalus($_item_bonus_malus->getSqlQuery(),$user_id,$month,$year,$operator_id);
            foreach ($data_status as $item_status){

                $total_bonus_malus_quality=0;
                $percentage=($total_vente_valide_current_month>0)?(($item_status['total']*100)/$total_vente_valide_current_month):($item_status['total']);
                $quality=$r_quality_bonus->qualityByCourtierrrrStausOperator($item_status['status'],$cpv,$percentage,$_item_bonus_malus->getId());
                if($quality!=false and !empty($quality)){

                    $total_bonus_malus += ($item_status['total'])*floatval($quality['coefficient']);
                    $total_bonus_malus_quality = ($item_status['total'])*floatval($quality['coefficient']);
                    $qualitees[]=[
                        'title_bonus_malus'=>$_item_bonus_malus->getTitle(),
                        'total'=>$item_status['total'],
                        'status'=>$quality['title'],
                        'coefficient'=>floatval($quality['coefficient']),
                        'total_bonus_malus_quality'=>$total_bonus_malus_quality,
                    ];

                }
            }
        }

        $result=[
            'qualitees'=>$qualitees,
            'total_bonus_malus'=>$total_bonus_malus,
        ];

        return $result;
    }
    private function bonusMalusDir($user_id,$month,$year,$operator_id, $libelleQuery){


        $seller=$this->getDoctrine()->getRepository(User::class)->find($user_id);
        $userCpv=$seller->getCpvCourtierExploitant();
        $total_bonus_malus=0;
        $qualitees=[];
        $r_quality_bonus=$this->getDoctrine()->getRepository(NmdQualityBonus::class);
        $r_bonus_malus=$this->getDoctrine()->getRepository(NmdBonusMalusQuery::class);
        $list_bonus_malus=$r_bonus_malus->findAll();

        $total_vente_valide_current_month=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalDirector($user_id,$month,$year, $libelleQuery)['total'];
        foreach ($list_bonus_malus as $_item_bonus_malus){

            $data_status=$r_bonus_malus->executeRequestBonusMalus($_item_bonus_malus->getSqlQuery(),$user_id,$month,$year,$operator_id);
            foreach ($data_status as $item_status){

                $total_bonus_malus_quality=0;
                $percentage=($total_vente_valide_current_month>0)?(($item_status['total']*100)/$total_vente_valide_current_month):($item_status['total']);
                $quality=$r_quality_bonus->qualityByCourtierrrrStausOperator($item_status['status'],$userCpv,$percentage,$_item_bonus_malus->getId());
                if($quality!=false and !empty($quality)){

                    $total_bonus_malus += ($item_status['total'])*floatval($quality['coefficient']);
                    $total_bonus_malus_quality = ($item_status['total'])*floatval($quality['coefficient']);
                    $qualitees[]=[
                        'title_bonus_malus'=>$_item_bonus_malus->getTitle(),
                        'total'=>$item_status['total'],
                        'status'=>$quality['title'],
                        'coefficient'=>floatval($quality['coefficient']),
                        'total_bonus_malus_quality'=>$total_bonus_malus_quality,
                    ];

                }
            }
        }

        $result=[
            'qualitees'=>$qualitees,
            'total_bonus_malus'=>$total_bonus_malus,
        ];

        return $result;
    }
    private function bonusMalusManager($user_id,$month,$year,$operator_id, $libelleQuery){


        $seller=$this->getDoctrine()->getRepository(User::class)->find($user_id);
        $userCpv=$seller->getCpvCourtierExploitant();
        $total_bonus_malus=0;
        $qualitees=[];
        $r_quality_bonus=$this->getDoctrine()->getRepository(NmdQualityBonus::class);
        $r_bonus_malus=$this->getDoctrine()->getRepository(NmdBonusMalusQuery::class);
        $list_bonus_malus=$r_bonus_malus->findAll();

        $total_vente_valide_current_month=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalManager($user_id,$month,$year, $libelleQuery)['total'];
        foreach ($list_bonus_malus as $_item_bonus_malus){

            $data_status=$r_bonus_malus->executeRequestBonusMalus($_item_bonus_malus->getSqlQuery(),$user_id,$month,$year,$operator_id);
            foreach ($data_status as $item_status){

                $total_bonus_malus_quality=0;
                $percentage=($total_vente_valide_current_month>0)?(($item_status['total']*100)/$total_vente_valide_current_month):($item_status['total']);
                $quality=$r_quality_bonus->qualityByCourtierrrrStausOperator($item_status['status'],$userCpv,$percentage,$_item_bonus_malus->getId());
                if($quality!=false and !empty($quality)){

                    $total_bonus_malus += ($item_status['total'])*floatval($quality['coefficient']);
                    $total_bonus_malus_quality = ($item_status['total'])*floatval($quality['coefficient']);
                    $qualitees[]=[
                        'title_bonus_malus'=>$_item_bonus_malus->getTitle(),
                        'total'=>$item_status['total'],
                        'status'=>$quality['title'],
                        'coefficient'=>floatval($quality['coefficient']),
                        'total_bonus_malus_quality'=>$total_bonus_malus_quality,
                    ];

                }
            }
        }

        $result=[
            'qualitees'=>$qualitees,
            'total_bonus_malus'=>$total_bonus_malus,
        ];

        return $result;
    }
    private function bonusMalusSeller($user_id,$month,$year,$operator_id, $libelleQuery){


        $seller=$this->getDoctrine()->getRepository(User::class)->find($user_id);
        $userCpv=$seller->getCpvCourtierExploitant();
        $total_bonus_malus=0;
        $qualitees=[];
        $r_quality_bonus=$this->getDoctrine()->getRepository(NmdQualityBonus::class);
        $r_bonus_malus=$this->getDoctrine()->getRepository(NmdBonusMalusQuery::class);
        $list_bonus_malus=$r_bonus_malus->findAll();

        $total_vente_valide_current_month=$this->getDoctrine()->getRepository(NmdTrack::class)->countventeTotalSeller($user_id,$month,$year, $libelleQuery)['total'];
        foreach ($list_bonus_malus as $_item_bonus_malus){

            $data_status=$r_bonus_malus->executeRequestBonusMalus($_item_bonus_malus->getSqlQuery(),$user_id,$month,$year,$operator_id);
            foreach ($data_status as $item_status){

                $total_bonus_malus_quality=0;
                $percentage=($total_vente_valide_current_month>0)?(($item_status['total']*100)/$total_vente_valide_current_month):($item_status['total']);
                $quality=$r_quality_bonus->qualityByCourtierrrrStausOperator($item_status['status'],$userCpv,$percentage,$_item_bonus_malus->getId());
                if($quality!=false and !empty($quality)){

                    $total_bonus_malus += ($item_status['total'])*floatval($quality['coefficient']);
                    $total_bonus_malus_quality = ($item_status['total'])*floatval($quality['coefficient']);
                    $qualitees[]=[
                        'title_bonus_malus'=>$_item_bonus_malus->getTitle(),
                        'total'=>$item_status['total'],
                        'status'=>$quality['title'],
                        'coefficient'=>floatval($quality['coefficient']),
                        'total_bonus_malus_quality'=>$total_bonus_malus_quality,
                    ];

                }
            }
        }

        $result=[
            'qualitees'=>$qualitees,
            'total_bonus_malus'=>$total_bonus_malus,
        ];

        return $result;
    }

    private function getLastXMonths($x){
        $mois=['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
        $months=[];
        for ($i = $x; $i >=0; $i--)
        {
            $month_number=date("m", strtotime( date( 'Y-m-01' )." -$i months"));
            $months[] = [
                'mois'=>$month_number,
                'month_text'=>$mois[(($month_number<10)?substr($month_number,-1):$month_number)-1],
                'annee'=>date("Y", strtotime( date( 'Y-m-01' )." -$i months"))
            ];
        }
        return $months;
    }

    private function sort_array_of_array(&$array, $subfield)
    {
        $sortarray = array();
        foreach ($array as $key => $row)
        {
            $sortarray[$key] = $row[$subfield];
        }

        array_multisort($sortarray, SORT_DESC, $array);
    }


}
