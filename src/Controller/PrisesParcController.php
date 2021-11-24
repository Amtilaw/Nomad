<?php

namespace App\Controller;

use App\Entity\NmdPartner;
use App\Entity\NmdPrParcPassage;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @Route("/prises/parc", name="prisesParc_")
*/
class PrisesParcController extends AbstractController
{

    /**
     * @Route("/index", name="index")
     */
    public function datesPrisesParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $userId = $userI->getId();
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $maxDate='';
        $maxDateParc='';
        $sqlFilter="";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $listsParc=[];

        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlFilter.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlFilter.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlFilter.=" AND user_id is not null ";


        $monthChoiceFrom=$request->get('monthChoiseFrom');
        $yearChoiceFrom=$request->get('yearChoiceFrom');
        $monthChoiceTo=$request->get('monthChoiseTo');
        $yearChoiceTo=$request->get('yearChoiceTo');

        if($monthChoiceFrom!=null and $yearChoiceFrom!=null and $monthChoiceTo!=null and $yearChoiceTo!=null){
            $sqlFilter.=" AND ( DATE(dat_prem_comm_adrs) between '$yearChoiceFrom-$monthChoiceFrom-01' AND '$yearChoiceTo-$monthChoiceTo-01' ) ";
        }

        if($role=='ROLE_SFR'){

            //Parc
            $lists=$repository->totalNbLogtDatesByWeek(null, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeek(null, $sqlFilter)['max_date'];

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item){
                $cpvItem=$item['cpv_courtier_exploitant'];
                $list_directeurs = $repository_partner->DirecteursForPartner($cpvItem);
                $list_directeursAff=$list_directeurs;
                foreach ($list_directeursAff as $item_director){
                    $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                    foreach ($list_manager_director as $it){
                        $list_managersAff[]=$it;
                    }
                }
                foreach ($list_managersAff as $item_manager){
                    $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                    foreach ($list_vendeurs_manager as $it){
                        $list_sellersAff[]=$it;
                    }
                }
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();

            //Parc
            $lists=$repository->totalNbLogtDatesByWeek($cpv, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeek($cpv, $sqlFilter)['max_date'];

            $list_directeurs = $repository_partner->DirecteursForPartner($cpv);
            $list_directeursAff=$list_directeurs;
            foreach ($list_directeursAff as $item_director){
                $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                foreach ($list_manager_director as $it){
                    $list_managersAff[]=$it;
                }
            }
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_DIRECTOR'){


            //Parc
            $lists=$repository->totalNbLogtDatesByWeekDirector($userId, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeekDirector($userId, $sqlFilter)['max_date'];

            $list_managers=$repository_partner->managersForDirecteur($userId);
            $list_managersAff=$list_managers;
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_MANAGER'){


            //Parc
            $lists=$repository->totalNbLogtDatesByWeekManager($userId, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeekManager($userId, $sqlFilter)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesParc/index.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesParc',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'listsParc'=>$listsParc,
            'm' => $month,
            'yearChoise' => $year,
            'monthChoiceTo' => $monthChoiceTo,
            'yearChoiceTo' => $yearChoiceTo,
            'monthChoiceFrom' => $monthChoiceFrom,
            'yearChoiceFrom' => $yearChoiceFrom,
            'weekChoice'=>$weekChoice,
            'yearWeek'=>$yearWeek,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'maxDateParc' => $maxDateParc,
            'value' => $value,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/clusters", name="clustersPrises")
     */
    public function clustersPrisesParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $maxDate='';
        $sqlJourLivraison='';
        //if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        if($month_dat_prem_comm_adrs!=null and $month_dat_prem_comm_adrs!='' and $year_dat_prem_comm_adrs!=null and $year_dat_prem_comm_adrs!='') $sqlJourLivraison.=" AND MONTH(dat_prem_comm_adrs) ='$month_dat_prem_comm_adrs' AND YEAR(dat_prem_comm_adrs) ='$year_dat_prem_comm_adrs' ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $userId = $userI->getId();
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";

        $monthChoiceFrom=$request->get('monthChoiseFrom');
        $yearChoiceFrom=$request->get('yearChoiceFrom');
        $monthChoiceTo=$request->get('monthChoiseTo');
        $yearChoiceTo=$request->get('yearChoiceTo');

        if($monthChoiceFrom!=null and $yearChoiceFrom!=null and $monthChoiceTo!=null and $yearChoiceTo!=null){
            $sqlJourLivraison.=" AND ( DATE(dat_prem_comm_adrs) between '$yearChoiceFrom-$monthChoiceFrom-01' AND '$yearChoiceTo-$monthChoiceTo-01' ) ";
        }

        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtClustersByWeek(null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeek(null,$sqlJourLivraison)['max_date'];

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item){
                $cpvItem=$item['cpv_courtier_exploitant'];
                $list_directeurs = $repository_partner->DirecteursForPartner($cpvItem);
                $list_directeursAff=$list_directeurs;
                foreach ($list_directeursAff as $item_director){
                    $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                    foreach ($list_manager_director as $it){
                        $list_managersAff[]=$it;
                    }
                }
                foreach ($list_managersAff as $item_manager){
                    $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                    foreach ($list_vendeurs_manager as $it){
                        $list_sellersAff[]=$it;
                    }
                }
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            $lists=$repository->totalNbLogtClustersByWeek($cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeek($cpv,$sqlJourLivraison)['max_date'];

            $list_directeurs = $repository_partner->DirecteursForPartner($cpv);
            $list_directeursAff=$list_directeurs;
            foreach ($list_directeursAff as $item_director){
                $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                foreach ($list_manager_director as $it){
                    $list_managersAff[]=$it;
                }
            }
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_DIRECTOR'){

            $lists=$repository->totalNbLogtClustersByWeekDirector($userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeekDirector($userId,$sqlJourLivraison)['max_date'];

            $list_managers=$repository_partner->managersForDirecteur($userId);
            $list_managersAff=$list_managers;
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_MANAGER'){

            $lists=$repository->totalNbLogtClustersByWeekManager( $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeekManager($userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesParc/clusters.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesParc',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'jourLivraison' => $jourLivraison,
            'month_dat_prem_comm_adrs' => $month_dat_prem_comm_adrs,
            'year_dat_prem_comm_adrs' => $year_dat_prem_comm_adrs,
            'monthChoiceTo' => $monthChoiceTo,
            'yearChoiceTo' => $yearChoiceTo,
            'monthChoiceFrom' => $monthChoiceFrom,
            'yearChoiceFrom' => $yearChoiceFrom,
            'weekChoice'=>$weekChoice,
            'yearWeek'=>$yearWeek,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/ville", name="villePrises")
     */
    public function villesPrisesParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $maxDate='';
        $sqlJourLivraison='';
        //if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        if($month_dat_prem_comm_adrs!=null and $month_dat_prem_comm_adrs!='' and $year_dat_prem_comm_adrs!=null and $year_dat_prem_comm_adrs!='') $sqlJourLivraison.=" AND MONTH(dat_prem_comm_adrs) ='$month_dat_prem_comm_adrs' AND YEAR(dat_prem_comm_adrs) ='$year_dat_prem_comm_adrs' ";

        $userId = $userI->getId();
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $codeCluster=$request->get('codeCluster');
        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $monthChoiceFrom=$request->get('monthChoiseFrom');
        $yearChoiceFrom=$request->get('yearChoiceFrom');
        $monthChoiceTo=$request->get('monthChoiseTo');
        $yearChoiceTo=$request->get('yearChoiceTo');

        if($monthChoiceFrom!=null and $yearChoiceFrom!=null and $monthChoiceTo!=null and $yearChoiceTo!=null){
            $sqlJourLivraison.=" AND ( DATE(dat_prem_comm_adrs) between '$yearChoiceFrom-$monthChoiceFrom-01' AND '$yearChoiceTo-$monthChoiceTo-01' ) ";
        }
        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtVillesByClusterWeek($codeCluster,null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeek($codeCluster,null,$sqlJourLivraison)['max_date'];

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item){
                $cpvItem=$item['cpv_courtier_exploitant'];
                $list_directeurs = $repository_partner->DirecteursForPartner($cpvItem);
                $list_directeursAff=$list_directeurs;
                foreach ($list_directeursAff as $item_director){
                    $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                    foreach ($list_manager_director as $it){
                        $list_managersAff[]=$it;
                    }
                }
                foreach ($list_managersAff as $item_manager){
                    $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                    foreach ($list_vendeurs_manager as $it){
                        $list_sellersAff[]=$it;
                    }
                }
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            $lists=$repository->totalNbLogtVillesByClusterWeek($codeCluster, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeek($codeCluster,$cpv,$sqlJourLivraison)['max_date'];

            $list_directeurs = $repository_partner->DirecteursForPartner($cpv);
            $list_directeursAff=$list_directeurs;
            foreach ($list_directeursAff as $item_director){
                $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                foreach ($list_manager_director as $it){
                    $list_managersAff[]=$it;
                }
            }
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_DIRECTOR'){

            $lists=$repository->totalNbLogtVillesByClusterWeekDirector($codeCluster, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeekDirector($codeCluster,$userId,$sqlJourLivraison)['max_date'];

            $list_managers=$repository_partner->managersForDirecteur($userId);
            $list_managersAff=$list_managers;
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_MANAGER'){

            $lists=$repository->totalNbLogtVillesByClusterWeekManager($codeCluster, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeekManager($codeCluster,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesParc/ville.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesParc',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'jourLivraison' => $jourLivraison,
            'month_dat_prem_comm_adrs' => $month_dat_prem_comm_adrs,
            'year_dat_prem_comm_adrs' => $year_dat_prem_comm_adrs,
            'monthChoiceTo' => $monthChoiceTo,
            'yearChoiceTo' => $yearChoiceTo,
            'monthChoiceFrom' => $monthChoiceFrom,
            'yearChoiceFrom' => $yearChoiceFrom,
            'codeCluster'=>$codeCluster,
            'yearWeek'=>$yearWeek,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/rues", name="ruesPrises")
     */
    public function ruesPrisesParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $maxDate='';
        $userId = $userI->getId();
        $sqlJourLivraison='';
        //if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        if($month_dat_prem_comm_adrs!=null and $month_dat_prem_comm_adrs!='' and $year_dat_prem_comm_adrs!=null and $year_dat_prem_comm_adrs!='') $sqlJourLivraison.=" AND MONTH(dat_prem_comm_adrs) ='$month_dat_prem_comm_adrs' AND YEAR(dat_prem_comm_adrs) ='$year_dat_prem_comm_adrs' ";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];

        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $codeCluster=$request->get('codeCluster');
        $ville=$request->get('ville');
        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%' OR nom_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $monthChoiceFrom=$request->get('monthChoiseFrom');
        $yearChoiceFrom=$request->get('yearChoiceFrom');
        $monthChoiceTo=$request->get('monthChoiseTo');
        $yearChoiceTo=$request->get('yearChoiceTo');

        if($monthChoiceFrom!=null and $yearChoiceFrom!=null and $monthChoiceTo!=null and $yearChoiceTo!=null){
            $sqlJourLivraison.=" AND ( DATE(dat_prem_comm_adrs) between '$yearChoiceFrom-$monthChoiceFrom-01' AND '$yearChoiceTo-$monthChoiceTo-01' ) ";
        }

        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtRuesByVilleClusterWeek($codeCluster,$ville,null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeek($codeCluster,$ville,null,$sqlJourLivraison)['max_date'];

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item){
                $cpvItem=$item['cpv_courtier_exploitant'];
                $list_directeurs = $repository_partner->DirecteursForPartner($cpvItem);
                $list_directeursAff=$list_directeurs;
                foreach ($list_directeursAff as $item_director){
                    $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                    foreach ($list_manager_director as $it){
                        $list_managersAff[]=$it;
                    }
                }
                foreach ($list_managersAff as $item_manager){
                    $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                    foreach ($list_vendeurs_manager as $it){
                        $list_sellersAff[]=$it;
                    }
                }
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            $lists=$repository->totalNbLogtRuesByVilleClusterWeek($codeCluster,$ville, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeek($codeCluster,$ville,$cpv,$sqlJourLivraison)['max_date'];

            $list_directeurs = $repository_partner->DirecteursForPartner($cpv);
            $list_directeursAff=$list_directeurs;
            foreach ($list_directeursAff as $item_director){
                $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                foreach ($list_manager_director as $it){
                    $list_managersAff[]=$it;
                }
            }
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_DIRECTOR'){

            $lists=$repository->totalNbLogtRuesByVilleClusterWeekDirector($codeCluster,$ville, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeekDirector($codeCluster,$ville,$userId,$sqlJourLivraison)['max_date'];

            $list_managers=$repository_partner->managersForDirecteur($userId);
            $list_managersAff=$list_managers;
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_MANAGER'){

            $lists=$repository->totalNbLogtRuesByVilleClusterWeekManager($codeCluster,$ville, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeekManager($codeCluster,$ville,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesParc/rues.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesParc',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'jourLivraison' => $jourLivraison,
            'month_dat_prem_comm_adrs' => $month_dat_prem_comm_adrs,
            'year_dat_prem_comm_adrs' => $year_dat_prem_comm_adrs,
            'monthChoiceTo' => $monthChoiceTo,
            'yearChoiceTo' => $yearChoiceTo,
            'monthChoiceFrom' => $monthChoiceFrom,
            'yearChoiceFrom' => $yearChoiceFrom,
            'yearWeek'=>$yearWeek,
            'codeCluster'=>$codeCluster,
            'ville'=>$ville,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/numrues", name="numruesPrises")
     */
    public function numruesPrisesParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $maxDate='';
        $sqlJourLivraison='';
        //if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        if($month_dat_prem_comm_adrs!=null and $month_dat_prem_comm_adrs!='' and $year_dat_prem_comm_adrs!=null and $year_dat_prem_comm_adrs!='') $sqlJourLivraison.=" AND MONTH(dat_prem_comm_adrs) ='$month_dat_prem_comm_adrs' AND YEAR(dat_prem_comm_adrs) ='$year_dat_prem_comm_adrs' ";

        $userId = $userI->getId();
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];

        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $codeCluster=$request->get('codeCluster');
        $ville=$request->get('ville');
        $nomVoie=$request->get('nomVoie');
        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%' OR nom_voie LIKE '$value%' OR numr_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $monthChoiceFrom=$request->get('monthChoiseFrom');
        $yearChoiceFrom=$request->get('yearChoiceFrom');
        $monthChoiceTo=$request->get('monthChoiseTo');
        $yearChoiceTo=$request->get('yearChoiceTo');

        if($monthChoiceFrom!=null and $yearChoiceFrom!=null and $monthChoiceTo!=null and $yearChoiceTo!=null){
            $sqlJourLivraison.=" AND ( DATE(dat_prem_comm_adrs) between '$yearChoiceFrom-$monthChoiceFrom-01' AND '$yearChoiceTo-$monthChoiceTo-01' ) ";
        }
        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeek($codeCluster,$ville,$nomVoie,null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeek($codeCluster,$ville,$nomVoie,null,$sqlJourLivraison)['max_date'];

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item){
                $cpvItem=$item['cpv_courtier_exploitant'];
                $list_directeurs = $repository_partner->DirecteursForPartner($cpvItem);
                $list_directeursAff=$list_directeurs;
                foreach ($list_directeursAff as $item_director){
                    $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                    foreach ($list_manager_director as $it){
                        $list_managersAff[]=$it;
                    }
                }
                foreach ($list_managersAff as $item_manager){
                    $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                    foreach ($list_vendeurs_manager as $it){
                        $list_sellersAff[]=$it;
                    }
                }
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeek($codeCluster,$ville,$nomVoie, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeek($codeCluster,$ville,$nomVoie,$cpv,$sqlJourLivraison)['max_date'];

            $list_directeurs = $repository_partner->DirecteursForPartner($cpv);
            $list_directeursAff=$list_directeurs;
            foreach ($list_directeursAff as $item_director){
                $list_manager_director=$repository_partner->managersForDirecteur($item_director['id']);
                foreach ($list_manager_director as $it){
                    $list_managersAff[]=$it;
                }
            }
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_DIRECTOR'){

            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeekDirector($codeCluster,$ville,$nomVoie, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeekDirector($codeCluster,$ville,$nomVoie,$userId,$sqlJourLivraison)['max_date'];

            $list_managers=$repository_partner->managersForDirecteur($userId);
            $list_managersAff=$list_managers;
            foreach ($list_managersAff as $item_manager){
                $list_vendeurs_manager=$repository_partner->vendeursForManager($item_manager['id']);
                foreach ($list_vendeurs_manager as $it){
                    $list_sellersAff[]=$it;
                }
            }
        }
        if($role=='ROLE_MANAGER'){

            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeekManager($codeCluster,$ville,$nomVoie, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeekManager($codeCluster,$ville,$nomVoie,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesParc/numrues.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesParc',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'yearWeek'=>$yearWeek,
            'jourLivraison' => $jourLivraison,
            'month_dat_prem_comm_adrs' => $month_dat_prem_comm_adrs,
            'year_dat_prem_comm_adrs' => $year_dat_prem_comm_adrs,
            'monthChoiceTo' => $monthChoiceTo,
            'yearChoiceTo' => $yearChoiceTo,
            'monthChoiceFrom' => $monthChoiceFrom,
            'yearChoiceFrom' => $yearChoiceFrom,
            'codeCluster'=>$codeCluster,
            'ville'=>$ville,
            'nomVoie'=>$nomVoie,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }

    /**
     * @Route("/getAllUsersAffectedAjax", name="getAllUsersAffectedAjax")
     */
    public function getAllUsersAffectedAjaxParc(Request $request, UserInterface $userI){


        $userId=$request->get('userId');
        $weekChoice=$request->get('weekChoise');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $codeCluster=$request->get('codeCluster');
        $ville=$request->get('ville');
        $nomVoie=$request->get('nomVoie');
        $value=$request->get('value');
        $numRue=$request->get('numRue');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $userId = $userI->getId();

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $sqlSecond='';

        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all'){
            $sqlSecond.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        }
        if($codeCluster!=null and $codeCluster!=''){
            $sqlSecond.=" AND code_cluster ='$codeCluster' ";
        }
        if($ville!=null and $ville!=''){
            $sqlSecond.=" AND vill ='$ville' ";
        }
        if($nomVoie!=null and $nomVoie!=''){
            $sqlSecond.=" AND nom_voie ='$nomVoie' ";
        }
        if($numRue!=null and $numRue!=''){
            $sqlSecond.=" AND numr_voie ='$numRue' ";
        }
        if($value!=null and $value!=''){
            $sqlSecond.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%' OR nom_voie LIKE '$value%' OR numr_voie LIKE '$value%') ";
        }

        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);

        if($role=='ROLE_SFR'){
            $lists=$repository->listOfUsersAffected(null,$sqlSecond );
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            $lists=$repository->listOfUsersAffected($cpv, $sqlSecond);

        }
        if($role=='ROLE_DIRECTOR'){

            $lists=$repository->listOfUsersAffectedDirector($userId, $sqlSecond);

        }
        if($role=='ROLE_MANAGER'){

            $lists=$repository->listOfUsersAffectedManager($userId, $sqlSecond);

        }

        return new JsonResponse([
            'lists'=>$lists
        ], 200);
    }

    /**
     * @Route("/affectAllPrisesDates", name="affectAllPrisesDatesAjaxParc")
     */
    public function affectAllPrisesDatesAjaxParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('jourLivraisonArray');
        $weekChoice=$request->get('weekChoise');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        $data = json_decode($data,true);
        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }

        $userId = $userI->getId();
        $now=new \DateTime();
        $lists=[];

        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);

        $seller_id=null;
        $manager_id=null;
        $director_id=null;
        $affected_by=$userId;
        $beneficiary_roles='';
        $userS=$this->getDoctrine()->getRepository(User::class)->find($userSelected);
        if($userS){
            foreach ($userS->getRoles() as $rs) {
                $roleS = $rs;
            }
            $beneficiary_roles=$roleS;
            if($roleS=='ROLE_DIRECTOR'){
                $director_id=$userSelected;
            }
            if($roleS=='ROLE_MANAGER'){
                $manager_id=$userSelected;
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $director_id=$directorS->getId();

            }
            if($roleS=='ROLE_SELLER'){
                $seller_id=$userSelected;
                $managerS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $manager_id=$managerS->getId();
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($managerS->getParent());
                $director_id=$directorS->getId();
            }
        }
        if($role=='ROLE_SFR'){
            foreach($data as $jourLivraison){
                $sqlJourLivraison='';
                if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";

                if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";
                if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
                if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

                $affect=$repository->affectPrisesSfrOrCompany(null,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $jourLivraison){
                $sqlJourLivraison='';
                if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";

                if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";
                if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
                if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

                $affect = $repository->affectPrisesSfrOrCompany($cpv,  $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }

    /**
     * @Route("/affectAllPrisesClusters", name="affectAllPrisesClustersAjaxParc")
     */
    public function affectAllPrisesClustersAjaxParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('clustersArray');
        $weekChoice=$request->get('weekChoise');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        $data = json_decode($data,true);
        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }

        $userId = $userI->getId();
        $now=new \DateTime();
        $lists=[];
        $sqlJourLivraison='';
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $seller_id=null;
        $manager_id=null;
        $director_id=null;
        $affected_by=$userId;
        $beneficiary_roles='';
        $userS=$this->getDoctrine()->getRepository(User::class)->find($userSelected);
        if($userS){
            foreach ($userS->getRoles() as $rs) {
                $roleS = $rs;
            }
            $beneficiary_roles=$roleS;
            if($roleS=='ROLE_DIRECTOR'){
                $director_id=$userSelected;
            }
            if($roleS=='ROLE_MANAGER'){
                $manager_id=$userSelected;
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $director_id=$directorS->getId();

            }
            if($roleS=='ROLE_SELLER'){
                $seller_id=$userSelected;
                $managerS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $manager_id=$managerS->getId();
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($managerS->getParent());
                $director_id=$directorS->getId();
            }
        }
        if($role=='ROLE_SFR'){
            foreach($data as $codeCluster){
                $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";
                $affect=$repository->affectPrisesSfrOrCompany(null,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $codeCluster){

                $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv,  $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
    /**
     * @Route("/affectAllPrisesVilles", name="affectAllPrisesVillesAjaxParc")
     */
    public function affectAllPrisesVillesAjaxParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('villesArray');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $weekChoice=$request->get('weekChoise');
        $codeCluster=$request->get('codeCluster');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        $data = json_decode($data,true);
        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }

        $userId = $userI->getId();
        $now=new \DateTime();
        $lists=[];

        $sqlJourLivraison='';
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $seller_id=null;
        $manager_id=null;
        $director_id=null;
        $affected_by=$userId;
        $beneficiary_roles='';
        $userS=$this->getDoctrine()->getRepository(User::class)->find($userSelected);
        if($userS){
            foreach ($userS->getRoles() as $rs) {
                $roleS = $rs;
            }
            $beneficiary_roles=$roleS;
            if($roleS=='ROLE_DIRECTOR'){
                $director_id=$userSelected;
            }
            if($roleS=='ROLE_MANAGER'){
                $manager_id=$userSelected;
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $director_id=$directorS->getId();

            }
            if($roleS=='ROLE_SELLER'){
                $seller_id=$userSelected;
                $managerS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $manager_id=$managerS->getId();
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($managerS->getParent());
                $director_id=$directorS->getId();
            }
        }
        if($role=='ROLE_SFR'){
            foreach($data as $ville){
                $sqlJourLivraison.=" AND vill='$ville' ";
                $affect=$repository->affectPrisesSfrOrCompany(null,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $ville){
                $sqlJourLivraison.=" AND vill='$ville' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv,  $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
    /**
     * @Route("/affectAllPrisesRues", name="affectAllPrisesRuesAjaxParc")
     */
    public function affectAllPrisesRuesAjaxParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('ruesArray');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $weekChoice=$request->get('weekChoise');
        $codeCluster=$request->get('codeCluster');
        $ville=$request->get('ville');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        $data = json_decode($data,true);
        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }

        $userId = $userI->getId();
        $now=new \DateTime();
        $lists=[];
        $sqlJourLivraison='';
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";
        $sqlJourLivraison.=" AND vill='$ville' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%' OR nom_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $seller_id=null;
        $manager_id=null;
        $director_id=null;
        $affected_by=$userId;
        $beneficiary_roles='';
        $userS=$this->getDoctrine()->getRepository(User::class)->find($userSelected);
        if($userS){
            foreach ($userS->getRoles() as $rs) {
                $roleS = $rs;
            }
            $beneficiary_roles=$roleS;
            if($roleS=='ROLE_DIRECTOR'){
                $director_id=$userSelected;
            }
            if($roleS=='ROLE_MANAGER'){
                $manager_id=$userSelected;
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $director_id=$directorS->getId();

            }
            if($roleS=='ROLE_SELLER'){
                $seller_id=$userSelected;
                $managerS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $manager_id=$managerS->getId();
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($managerS->getParent());
                $director_id=$directorS->getId();
            }
        }
        if($role=='ROLE_SFR'){
            foreach($data as $rue){
                $sqlJourLivraison.=" AND nom_voie='$rue' ";
                $affect=$repository->affectPrisesSfrOrCompany(null,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $rue){
                $sqlJourLivraison.=" AND nom_voie='$rue' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv,  $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
    /**
     * @Route("/affectAllPrisesNumRues", name="affectAllPrisesNumRuesAjaxParc")
     */
    public function affectAllPrisesNumRuesAjaxParc(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('numruesArray');
        $weekChoice=$request->get('weekChoise');
        $jourLivraison=$request->get('jourLivraison');
        $month_dat_prem_comm_adrs=$request->get('month_dat_prem_comm_adrs');
        $year_dat_prem_comm_adrs=$request->get('year_dat_prem_comm_adrs');
        $codeCluster=$request->get('codeCluster');
        $ville=$request->get('ville');
        $nomVoie=$request->get('nomVoie');
        $value=$request->get('value');
        $prisesNoAffected=$request->get('prisesNoAffected');

        $data = json_decode($data,true);
        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }

        $userId = $userI->getId();
        $now=new \DateTime();
        $lists=[];
        $sqlJourLivraison='';
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(dat_prem_comm_adrs) ='$jourLivraison' ";
        $sqlJourLivraison.=" AND nom_voie='$nomVoie' ";
        $sqlJourLivraison.=" AND vill='$ville' ";
        $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrParcPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR cp LIKE '$value%' OR nom_voie LIKE '$value%' OR numr_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $seller_id=null;
        $manager_id=null;
        $director_id=null;
        $affected_by=$userId;
        $beneficiary_roles='';
        $userS=$this->getDoctrine()->getRepository(User::class)->find($userSelected);
        if($userS){
            foreach ($userS->getRoles() as $rs) {
                $roleS = $rs;
            }
            $beneficiary_roles=$roleS;
            if($roleS=='ROLE_DIRECTOR'){
                $director_id=$userSelected;
            }
            if($roleS=='ROLE_MANAGER'){
                $manager_id=$userSelected;
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $director_id=$directorS->getId();

            }
            if($roleS=='ROLE_SELLER'){
                $seller_id=$userSelected;
                $managerS=$this->getDoctrine()->getRepository(User::class)->find($userS->getParent());
                $manager_id=$managerS->getId();
                $directorS=$this->getDoctrine()->getRepository(User::class)->find($managerS->getParent());
                $director_id=$directorS->getId();
            }
        }
        if($role=='ROLE_SFR'){
            foreach($data as $numrue){
                $sqlJourLivraison.=" AND numr_voie='$numrue' ";
                $affect=$repository->affectPrisesSfrOrCompany(null,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $numrue){
                $sqlJourLivraison.=" AND numr_voie='$numrue' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv,  $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles, $sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
}
