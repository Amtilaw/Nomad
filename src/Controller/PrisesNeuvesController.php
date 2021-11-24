<?php

namespace App\Controller;

use App\Entity\NmdPartner;
use App\Entity\NmdPrNeuvesPassage;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @Route("/prises/neuves", name="prisesNeuves_")
*/
class PrisesNeuvesController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request, UserInterface $userI): Response
    {

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $userId=$request->get('userId');
        $value=$request->get('value');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $sqlFilter="";

        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $lists=[];
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $userId = $userI->getId();
        $maxDate='';

        if($value!=null and $value!='') $sqlFilter.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlFilter.=" AND is_open_close=1 ";
        }else{
            $sqlFilter.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

        $monthChoice=$request->get('monthChoise');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }

        if($role=='ROLE_SFR'){

            $maxDate = $repository->maxDateNbLogtLast8Weeks(null,$sqlFilter)['max_date'];

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item){
                $cpvItem=$item['cpv_courtier_exploitant'];

                $pvr = $repository->totalNbLogtLForPartner($cpvItem,$sqlFilter);
                $lists[] = [
                    'libelle' => $item['lastname'].' '.$item['firstname'],
                    'userId' => $item['id'],
                    'total' => ($pvr['total']>0)?($pvr['total']):0,
                    'totalPrisesNoaffected' => $pvr['totalPrisesNoaffected'],
                    'totalPrisesaffected' => $pvr['totalPrisesaffected']
                ];

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
            $item=$repository_partner->partnerByCpv($cpv);
            $pvr = $repository->totalNbLogtLForPartner($cpv,$sqlFilter);
            $lists[] = [
                'libelle' => $item['lastname'].' '.$item['firstname'],
                'userId' => $item['id'],
                'total' => ($pvr['total']>0)?($pvr['total']):0,
                'totalPrisesNoaffected' => $pvr['totalPrisesNoaffected'],
                'totalPrisesaffected' => $pvr['totalPrisesaffected']
            ];
            $maxDate = $repository->maxDateNbLogtLast8Weeks($cpv,$sqlFilter)['max_date'];

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

            $cpv = $userI->getCpvCourtierExploitant();
            $item=$repository_partner->partnerByCpv($cpv);
            $pvr = $repository->totalNbLogtLForPartner($cpv,$sqlFilter);
            $lists[] = [
                'libelle' => $item['lastname'].' '.$item['firstname'],
                'userId' => $item['id'],
                'total' => ($pvr['total']>0)?($pvr['total']):0,
                'totalPrisesNoaffected' => $pvr['totalPrisesNoaffected'],
                'totalPrisesaffected' => $pvr['totalPrisesaffected']
            ];
            $maxDate = $repository->maxDateNbLogtLast8WeeksDirector($userId,$sqlFilter)['max_date'];

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

            $cpv = $userI->getCpvCourtierExploitant();
            $item=$repository_partner->partnerByCpv($cpv);
            $pvr = $repository->totalNbLogtLForPartner($cpv,$sqlFilter);
            $lists[] = [
                'libelle' => $item['lastname'].' '.$item['firstname'],
                'userId' => $item['id'],
                'total' => ($pvr['total']>0)?($pvr['total']):0,
                'totalPrisesNoaffected' => $pvr['totalPrisesNoaffected'],
                'totalPrisesaffected' => $pvr['totalPrisesaffected']
            ];
            $maxDate = $repository->maxDateNbLogtLast8WeeksManager($userId,$sqlFilter)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesNeuves/index.html.twig', [
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/byWeek", name="prisesByWeek")
     */
    public function prisesByWeek(Request $request, UserInterface $userI): Response
    {
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $userId=$request->get('userId');
        $value=$request->get('value');

        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        $list_directeursAff = [];
        $list_managersAff = [];
        $list_sellersAff = [];
        $sqlFilter="";

        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $lists=[];
        $listsParc=[];
        //$weeks=$this->getLastXWeeks(8);
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $userId = $userI->getId();
        $maxDate='';

        if($value!=null and $value!='') $sqlFilter.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlFilter.=" AND is_open_close=1 ";
        }else{
            $sqlFilter.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

        if($role=='ROLE_SFR'){
            $pvrLists = $repository->totalNbLogtLast8Weeks(null,$sqlFilter);
            foreach ($pvrLists as $pvr) {
                $lists[] = [
                    'week' => $pvr['week'],
                    'annee' => $pvr['annee'],
                    'libelle' => 'Semaine ' . $pvr['week'],
                    'total' => ($pvr['total']>0)?($pvr['total']):0,
                    'semaine_livraison' => $pvr['sem_livraison'],
                    'totalPrisesNoaffected' => $pvr['totalPrisesNoaffected'],
                    'totalPrisesaffected' => $pvr['totalPrisesaffected']
                ];
            }
            $maxDate = $repository->maxDateNbLogtLast8Weeks(null,$sqlFilter)['max_date'];

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
            $pvrLists = $repository->totalNbLogtLast8Weeks($cpv,$sqlFilter);
            foreach ($pvrLists as $pvr) {
                $lists[] = [
                    'week' => $pvr['week'],
                    'annee' => $pvr['annee'],
                    'libelle' => 'Semaine ' . $pvr['week'],
                    'total' => ($pvr['total']>0)?($pvr['total']):0,
                    'semaine_livraison' => $pvr['sem_livraison'],
                    'totalPrisesNoaffected' => $pvr['totalPrisesNoaffected'],
                    'totalPrisesaffected' => $pvr['totalPrisesaffected']
                ];
            }
            $maxDate = $repository->maxDateNbLogtLast8Weeks($cpv,$sqlFilter)['max_date'];

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

            $pvrLists = $repository->totalNbLogtLast8WeeksDirector($userId,$sqlFilter);
            foreach ($pvrLists as $pvr) {
                $lists[] = [
                    'week' => $pvr['week'],
                    'annee' => $pvr['annee'],
                    'libelle' => 'Semaine ' . $pvr['week'],
                    'total' => ($pvr['total']>0)?($pvr['total']):0,
                    'semaine_livraison' => $pvr['sem_livraison'],
                    'totalPrisesNoaffected' => 0,
                    'totalPrisesaffected' => $pvr['totalPrisesaffected']
                ];
            }
            $maxDate = $repository->maxDateNbLogtLast8WeeksDirector($userId,$sqlFilter)['max_date'];

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
            $pvrLists = $repository->totalNbLogtLast8WeeksManager($userId,$sqlFilter);
            foreach ($pvrLists as $pvr) {
                $lists[] = [
                    'week' => $pvr['week'],
                    'annee' => $pvr['annee'],
                    'libelle' => 'Semaine ' . $pvr['week'],
                    'total' => ($pvr['total']>0)?($pvr['total']):0,
                    'semaine_livraison' => $pvr['sem_livraison'],
                    'totalPrisesNoaffected' => 0,
                    'totalPrisesaffected' => $pvr['totalPrisesaffected']
                ];
            }
            $maxDate = $repository->maxDateNbLogtLast8WeeksManager($userId,$sqlFilter)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }


        return $this->render('prisesNeuves/prisesByWeek.html.twig', [
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'listsParc'=>$listsParc,
            'm' => $month,
            'yearChoise' => $year,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }

    /**
     * @Route("/dates", name="datesPrises")
     */
    public function datesPrises(Request $request, UserInterface $userI){

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
        $sqlFilter="";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];

        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlFilter.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlFilter.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlFilter.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlFilter.=" AND is_open_close=1 ";
        }else{
            $sqlFilter.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }
        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtDatesByWeek($weekChoice, null, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeek($weekChoice, null, $sqlFilter)['max_date'];

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
            $lists=$repository->totalNbLogtDatesByWeek($weekChoice, $cpv, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeek($weekChoice, $cpv, $sqlFilter)['max_date'];

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

            $lists=$repository->totalNbLogtDatesByWeekDirector($weekChoice, $userId, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeekDirector($weekChoice, $userId, $sqlFilter)['max_date'];

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

            $lists=$repository->totalNbLogtDatesByWeekManager($weekChoice, $userId, $sqlFilter);
            $maxDate = $repository->maxDateNbLogtDatesByWeekManager($weekChoice,$userId, $sqlFilter)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesNeuves/dates.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'yearWeek'=>$yearWeek,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/clusters", name="clustersPrises")
     */
    public function clustersPrises(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $userId = $userI->getId();
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }
        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtClustersByWeek($weekChoice, null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeek($weekChoice,null,$sqlJourLivraison)['max_date'];

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
            $lists=$repository->totalNbLogtClustersByWeek($weekChoice, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeek($weekChoice,$cpv,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtClustersByWeekDirector($weekChoice, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeekDirector($weekChoice,$userId,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtClustersByWeekManager($weekChoice, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtClustersByWeekManager($weekChoice,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesNeuves/clusters.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'jourLivraison' => $jourLivraison,
            'weekChoice'=>$weekChoice,
            'yearWeek'=>$yearWeek,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }

    /**
     * @Route("/synthese_livraison", name="clustersPrisessyntheseLivraison")
     */
    public function clustersPrisessyntheseLivraison(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $value=$request->get('value');

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

        $userId = $userI->getId();
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";

        $monthChoice=$request->get('monthChoise');
        $yearChoice=$request->get('yearChoice');
        if($monthChoice!=null and $monthChoice!=''){
            $month=$monthChoice;
        }
        if($yearChoice!=null and $yearChoice!=''){
            $year=$yearChoice;
        }


        if($role=='ROLE_SFR'){

            $partenaires = $repository_partner->partnersForSFR();
            foreach ($partenaires as $item) {
                $cpvItem = $item['cpv_courtier_exploitant'];
                $view = 'view_pvr_pr_neuves_passage_'.$cpvItem;
                $listsClusters=$repository->totalPrisesClustersByWeekView($view,($sqlJourLivraison." AND MONTH(jour_livraison) ='$month' "." AND YEAR(jour_livraison) ='$year' "));
                foreach ($listsClusters as $itemCluster){
                    $totalFerme=$repository->totalPrisesFermesClustersByWeekView($view,$itemCluster['code_cluster'],($sqlJourLivraison." AND MONTH(jour_livraison) ='$month' "." AND YEAR(jour_livraison) ='$year' "))['total'];
                    $totalParc=$repository->totalPrisesParcClustersByWeekView($itemCluster['code_cluster'],($sqlJourLivraison))['total'];
                    $lists[]=[
                        'code_cluster'=>$itemCluster['code_cluster'],
                        'libelle_cluster'=>$itemCluster['libelle_cluster'],
                        'totalNeuves'=>($itemCluster['total'])?$itemCluster['total']:0,
                        'totalParc'=>($totalParc>0)?$totalParc:0,
                        'totalNeuvesFerme'=>($totalFerme>0)?$totalFerme:0
                    ];
                }

            }

        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            $view = 'view_pvr_pr_neuves_passage_'.$cpv;
            $listsClusters=$repository->totalPrisesClustersByWeekView($view,($sqlJourLivraison." AND MONTH(jour_livraison) ='$month' "." AND YEAR(jour_livraison) ='$year' "));
            foreach ($listsClusters as $itemCluster){
                $totalFerme=$repository->totalPrisesFermesClustersByWeekView($view,$itemCluster['code_cluster'],($sqlJourLivraison." AND MONTH(jour_livraison) ='$month' "." AND YEAR(jour_livraison) ='$year' "))['total'];
                $totalParc=$repository->totalPrisesParcClustersByWeekView($itemCluster['code_cluster'],($sqlJourLivraison))['total'];
                $lists[]=[
                    'code_cluster'=>$itemCluster['code_cluster'],
                    'libelle_cluster'=>$itemCluster['libelle_cluster'],
                    'totalNeuves'=>($itemCluster['total'])?$itemCluster['total']:0,
                    'totalParc'=>($totalParc>0)?$totalParc:0,
                    'totalNeuvesFerme'=>($totalFerme>0)?$totalFerme:0
                ];
            }

        }
        if($role=='ROLE_DIRECTOR'){



        }
        if($role=='ROLE_MANAGER'){



        }

        return $this->render('prises/V2/clustersV2SyntheseLivraison.html.twig',[
            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
        ]);
    }

    /**
     * @Route("/ville", name="villePrises")
     */
    public function villesPrises(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";

        $userId = $userI->getId();
        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];
        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $codeCluster=$request->get('codeCluster');
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtVillesByClusterWeek($weekChoice,$codeCluster,null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeek($weekChoice,$codeCluster,null,$sqlJourLivraison)['max_date'];

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
            $lists=$repository->totalNbLogtVillesByClusterWeek($weekChoice,$codeCluster, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeek($weekChoice,$codeCluster,$cpv,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtVillesByClusterWeekDirector($weekChoice,$codeCluster, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeekDirector($weekChoice,$codeCluster,$userId,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtVillesByClusterWeekManager($weekChoice,$codeCluster, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtVillesByClusterWeekManager($weekChoice,$codeCluster,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesNeuves/ville.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'jourLivraison' => $jourLivraison,
            'codeCluster'=>$codeCluster,
            'yearWeek'=>$yearWeek,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/rues", name="ruesPrises")
     */
    public function ruesPrises(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";

        $now=new \DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');
        $lists=[];

        $weekChoice=$request->get('weekChoise');
        $yearWeek=$request->get('yearWeek');
        $codeCluster=$request->get('codeCluster');
        $ville=$request->get('ville');
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%' OR nom_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtRuesByVilleClusterWeek($weekChoice,$codeCluster,$ville,null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeek($weekChoice,$codeCluster,$ville,null,$sqlJourLivraison)['max_date'];

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
            $lists=$repository->totalNbLogtRuesByVilleClusterWeek($weekChoice,$codeCluster,$ville, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeek($weekChoice,$codeCluster,$ville,$cpv,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtRuesByVilleClusterWeekDirector($weekChoice,$codeCluster,$ville, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeekDirector($weekChoice,$codeCluster,$ville,$userId,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtRuesByVilleClusterWeekManager($weekChoice,$codeCluster,$ville, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtRuesByVilleClusterWeekManager($weekChoice,$codeCluster,$ville,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesNeuves/rues.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'jourLivraison' => $jourLivraison,
            'yearWeek'=>$yearWeek,
            'codeCluster'=>$codeCluster,
            'ville'=>$ville,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }
    /**
     * @Route("/numrues", name="numruesPrises")
     */
    public function numruesPrises(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";

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
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);
        $repository_partner = $this->getDoctrine()->getRepository(NmdPartner::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%' OR nom_voie LIKE '$value%' OR numr_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

        if($role=='ROLE_SFR'){
            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeek($weekChoice,$codeCluster,$ville,$nomVoie,null,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeek($weekChoice,$codeCluster,$ville,$nomVoie,null,$sqlJourLivraison)['max_date'];

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
            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeek($weekChoice,$codeCluster,$ville,$nomVoie, $cpv,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeek($weekChoice,$codeCluster,$ville,$nomVoie,$cpv,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeekDirector($weekChoice,$codeCluster,$ville,$nomVoie, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeekDirector($weekChoice,$codeCluster,$ville,$nomVoie,$userId,$sqlJourLivraison)['max_date'];

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

            $lists=$repository->totalNbLogtNumRuesByRueVilleClusterWeekManager($weekChoice,$codeCluster,$ville,$nomVoie, $userId,$sqlJourLivraison);
            $maxDate = $repository->maxDateNbLogtNumRuesByRueVilleClusterWeekManager($weekChoice,$codeCluster,$ville,$nomVoie,$userId,$sqlJourLivraison)['max_date'];

            $list_sellers=$repository_partner->vendeursForManager($userId);
            $list_sellersAff=$list_sellers;
        }

        return $this->render('prisesNeuves/numrues.html.twig',[
            'pageTitle' => 'Prises',
            'rootTemplate' => 'prisesNeuves',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            'lists'=>$lists,
            'm' => $month,
            'yearChoise' => $year,
            'weekChoice'=>$weekChoice,
            'yearWeek'=>$yearWeek,
            'jourLivraison' => $jourLivraison,
            'codeCluster'=>$codeCluster,
            'ville'=>$ville,
            'nomVoie'=>$nomVoie,
            'userId' => $userId,
            'maxDate' => $maxDate,
            'value' => $value,
            'is_open_close' => $is_open_close,
            'prisesNoAffected' => $prisesNoAffected,

            'list_directeursAff'=>$list_directeursAff,
            'list_managersAff'=>$list_managersAff,
            'list_sellersAff'=>$list_sellersAff,
        ]);
    }

    /**
     * @Route("/getAllUsersAffectedAjax", name="getAllUsersAffectedAjax")
     */
    public function getAllUsersAffectedAjax(Request $request, UserInterface $userI){


        $userId=$request->get('userId');
        $weekChoice=$request->get('weekChoise');
        $jourLivraison=$request->get('jourLivraison');
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

        if($weekChoice!=null and $weekChoice!=''){
            $sqlSecond.=" AND sem_livraison= '$weekChoice' ";
        }
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all'){
            $sqlSecond.=" AND DATE(jour_livraison) ='$jourLivraison' ";
        }
        if($codeCluster!=null and $codeCluster!=''){
            $sqlSecond.=" AND code_cluster ='$codeCluster' ";
        }
        if($ville!=null and $ville!=''){
            $sqlSecond.=" AND ville ='$ville' ";
        }
        if($nomVoie!=null and $nomVoie!=''){
            $sqlSecond.=" AND nom_voie ='$nomVoie' ";
        }
        if($numRue!=null and $numRue!=''){
            $sqlSecond.=" AND numr_voie ='$numRue' ";
        }
        if($value!=null and $value!=''){
            $sqlSecond.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%' OR nom_voie LIKE '$value%' OR numr_voie LIKE '$value%') ";
        }

        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlSecond.=" AND is_open_close=1 ";
        }else{
            $sqlSecond.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

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
     * @Route("/affectAllPrisesWeeks", name="affectAllPrisesWeeksAjax")
     */
    public function affectAllPrisesWeeksAjax(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $jourLivraison=$request->get('jourLivraison');
        $data=$request->get('weekChoise');
        $value=$request->get('value');
        $data = json_decode($data,true);
        if($userId!=null and $userId!='' and $userId>0){
            $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
            $userI=$user;
        }
        foreach ($userI->getRoles() as $r) {
            $role = $r;
        }
        $sqlJourLivraison='';
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";

        $userId = $userI->getId();
        $now=new \DateTime();
        $lists=[];
        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

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
            $manager_id=null;
            foreach($data as $weekChoice){
                $affect=$repository->affectPrisesSfrOrCompany(null,$weekChoice,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $weekChoice) {
                $affect = $repository->affectPrisesSfrOrCompany($cpv, $weekChoice, $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }

    /**
     * @Route("/affectAllPrisesDates", name="affectAllPrisesDatesAjax")
     */
    public function affectAllPrisesDatesAjax(Request $request, UserInterface $userI){

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

        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

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
                if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";

                if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
                if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
                if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

                $is_open_close=$request->get('is_open_close');
                if($is_open_close!=null and $is_open_close=='yes'){
                    $sqlJourLivraison.=" AND is_open_close=1 ";
                }else{
                    $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
                }

                $affect=$repository->affectPrisesSfrOrCompany(null,$weekChoice,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $jourLivraison){
                $sqlJourLivraison='';
                if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison=" AND DATE(jour_livraison) ='$jourLivraison' ";

                if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
                if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
                if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

                $is_open_close=$request->get('is_open_close');
                if($is_open_close!=null and $is_open_close=='yes'){
                    $sqlJourLivraison.=" AND is_open_close=1 ";
                }else{
                    $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
                }

                $affect = $repository->affectPrisesSfrOrCompany($cpv, $weekChoice, $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }

    /**
     * @Route("/affectAllPrisesClusters", name="affectAllPrisesClustersAjax")
     */
    public function affectAllPrisesClustersAjax(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('clustersArray');
        $weekChoice=$request->get('weekChoise');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

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
                $affect=$repository->affectPrisesSfrOrCompany(null,$weekChoice,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $codeCluster){

                $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv, $weekChoice, $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
    /**
     * @Route("/affectAllPrisesVilles", name="affectAllPrisesVillesAjax")
     */
    public function affectAllPrisesVillesAjax(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('villesArray');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";
        $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

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
                $sqlJourLivraison.=" AND ville='$ville' ";
                $affect=$repository->affectPrisesSfrOrCompany(null,$weekChoice,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $ville){
                $sqlJourLivraison.=" AND ville='$ville' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv, $weekChoice, $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
    /**
     * @Route("/affectAllPrisesRues", name="affectAllPrisesRuesAjax")
     */
    public function affectAllPrisesRuesAjax(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('ruesArray');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";
        $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";
        $sqlJourLivraison.=" AND ville='$ville' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%' OR nom_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

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
                $affect=$repository->affectPrisesSfrOrCompany(null,$weekChoice,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $rue){
                $sqlJourLivraison.=" AND nom_voie='$rue' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv, $weekChoice, $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
    /**
     * @Route("/affectAllPrisesNumRues", name="affectAllPrisesNumRuesAjax")
     */
    public function affectAllPrisesNumRuesAjax(Request $request, UserInterface $userI){

        $userId=$request->get('userId');
        $userSelected=$request->get('userSelected');
        $data=$request->get('numruesArray');
        $weekChoice=$request->get('weekChoise');
        $jourLivraison=$request->get('jourLivraison');
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
        if($jourLivraison!=null and $jourLivraison!='' and $jourLivraison!='all') $sqlJourLivraison.=" AND DATE(jour_livraison) ='$jourLivraison' ";
        $sqlJourLivraison.=" AND nom_voie='$nomVoie' ";
        $sqlJourLivraison.=" AND ville='$ville' ";
        $sqlJourLivraison.=" AND code_cluster='$codeCluster' ";

        $repository=$this->getDoctrine()->getRepository(NmdPrNeuvesPassage::class);

        if($value!=null and $value!='') $sqlJourLivraison.=" AND (code_cluster LIKE '$value%' OR code_postal LIKE '$value%' OR nom_voie LIKE '$value%' OR numr_voie LIKE '$value%') ";
        if($prisesNoAffected!=null and $prisesNoAffected=='yes') $sqlJourLivraison.=" AND user_id is null ";
        if($prisesNoAffected!=null and $prisesNoAffected=='no') $sqlJourLivraison.=" AND user_id is not null ";

        $is_open_close=$request->get('is_open_close');
        if($is_open_close!=null and $is_open_close=='yes'){
            $sqlJourLivraison.=" AND is_open_close=1 ";
        }else{
            $sqlJourLivraison.=" AND (is_open_close=0 OR is_open_close IS NULL) ";
        }

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
                $affect=$repository->affectPrisesSfrOrCompany(null,$weekChoice,$seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }
        if($role=='ROLE_COMPANY'){
            $cpv = $userI->getCpvCourtierExploitant();
            foreach($data as $numrue){
                $sqlJourLivraison.=" AND numr_voie='$numrue' ";
                $affect = $repository->affectPrisesSfrOrCompany($cpv, $weekChoice, $seller_id,$affected_by,$manager_id,$director_id,$beneficiary_roles,$sqlJourLivraison);
            }
        }

        return new JsonResponse(1,200);
    }
}
