<?php

namespace App\Controller;

use App\Entity\NmdPartner;
use App\Entity\NmdUserConfiguration;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;

/**
* @Route("/effectif", name="effectif_")
*/
class EffectifController extends AbstractController
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

        $isEnabled=$request->get('isEnabled');
        if($isEnabled==null or $isEnabled==''){
            $isEnabled='1';
        }
        $sqlIsEnabled="";
        $repository_user=$this->getDoctrine()->getRepository(User::class);
        $userId = $request->get('userId');
        if($userId==null or $userId==''){
            $rolesI=$userI->getRoles();
            foreach ($rolesI as $rI) {
                $roleI = $rI;
            }
            if ($roleI=='ROLE_FINANCIAL') {
                $userId=$userI->getParent();
            }
            if ($roleI=='ROLE_COMPANY' or $roleI=='ROLE_USER') {
                $userId=$userI->getId();
            }
        }
        $user=$this->getDoctrine()->getRepository(User::class)->find($userId);
        $cpv=$user->getCpvCourtierExploitant();

        $now=new DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');

        $roles=$user->getRoles();
        foreach ($roles as $r) {
            $role = $r;
        }

        $isAll=$request->get('isAll');
        if($isEnabled=='1'){
            $sqlIsEnabled=" AND is_enabled='1' ";
        }else{
            $sqlIsEnabled=" AND (is_enabled='0' OR is_enabled IS NULL) ";
        }
        if ($role=='ROLE_COMPANY' or $role=='ROLE_USER') {
            $roleP='COMPANY';
            if($isAll!=null and $isAll=='yes'){
                $users=[];
                $allDirectors=$repository_user->DirecteursForPartner($cpv, $sqlIsEnabled);
                foreach ($allDirectors as $itemDirector){
                    $managers=[];
                    $director_id=$itemDirector['id'];
                    $allManagers=$repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
                    foreach ($allManagers as $itemManager){
                        $manager_id=$itemManager['id'];
                        $sellers=$repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
                        $managers[]=[
                            'id'=>$itemManager['id'],
                            'is_enabled'=>$itemManager['is_enabled'],
                            'lastname'=>$itemManager['lastname'],
                            'firstname'=>$itemManager['firstname'],
                            'email'=>$itemManager['email'],
                            'phone'=>$itemManager['phone'],
                            'role'=>'ROLE_MANAGER',
                            'sellers'=>$sellers
                        ];
                    }

                    $users[]=[
                        'id'=>$itemDirector['id'],
                        'is_enabled'=>$itemDirector['is_enabled'],
                        'lastname'=>$itemDirector['lastname'],
                        'firstname'=>$itemDirector['firstname'],
                        'email'=>$itemDirector['email'],
                        'phone'=>$itemDirector['phone'],
                        'role'=>'ROLE_DIRECTOR',
                        'managers'=>$managers
                    ];

                }
            }else{
                $users=[];
                $usersLists=$repository_user->DirecteursForPartner($cpv, $sqlIsEnabled);
                foreach ($usersLists as $itemList){
                    $users[]=[
                        'id'=>$itemList['id'],
                        'is_enabled'=>$itemList['is_enabled'],
                        'lastname'=>$itemList['lastname'],
                        'firstname'=>$itemList['firstname'],
                        'email'=>$itemList['email'],
                        'phone'=>$itemList['phone'],
                        'role'=>'ROLE_DIRECTOR'
                    ];
                }
            }

            if($request->get('director_id') and $request->get('director_id')!=''){
                $roleP='DIRECTOR';
                $director_id=$request->get('director_id');
                $user=$repository_user->find($director_id);
                $users=[];

                if($isAll!=null and $isAll=='yes'){
                    $allManagers=$repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
                    foreach ($allManagers as $itemManager){
                        $manager_id=$itemManager['id'];
                        $sellers=$repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
                        $users[]=[
                            'id'=>$itemManager['id'],
                            'is_enabled'=>$itemManager['is_enabled'],
                            'lastname'=>$itemManager['lastname'],
                            'firstname'=>$itemManager['firstname'],
                            'email'=>$itemManager['email'],
                            'phone'=>$itemManager['phone'],
                            'role'=>'ROLE_MANAGER',
                            'sellers'=>$sellers
                        ];
                    }
                }else{
                    $users=[];
                    $usersLists=$repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
                    foreach ($usersLists as $itemList){
                        $users[]=[
                            'id'=>$itemList['id'],
                            'is_enabled'=>$itemList['is_enabled'],
                            'lastname'=>$itemList['lastname'],
                            'firstname'=>$itemList['firstname'],
                            'email'=>$itemList['email'],
                            'phone'=>$itemList['phone'],
                            'role'=>'ROLE_MANAGER'
                        ];
                    }

                }
            }
            if($request->get('manager_id') and $request->get('manager_id')!=''){
                $users=[];
                $roleP='MANAGER';
                $manager_id=$request->get('manager_id');
                $user=$repository_user->find($manager_id);
                $usersLists=$repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
                foreach ($usersLists as $itemList){
                    $users[]=[
                        'id'=>$itemList['id'],
                        'is_enabled'=>$itemList['is_enabled'],
                        'lastname'=>$itemList['lastname'],
                        'firstname'=>$itemList['firstname'],
                        'email'=>$itemList['email'],
                        'phone'=>$itemList['phone'],
                        'role'=>'ROLE_SELLER'
                    ];
                }
            }
        }
        if ($role=='ROLE_DIRECTOR') {
            $roleP='DIRECTOR';
            $director_id=$userId;

            if($isAll!=null and $isAll=='yes'){
                $allManagers=$repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
                foreach ($allManagers as $itemManager){
                    $manager_id=$itemManager['id'];
                    $sellers=$repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
                    $users[]=[
                        'id'=>$itemManager['id'],
                        'is_enabled'=>$itemManager['is_enabled'],
                        'lastname'=>$itemManager['lastname'],
                        'firstname'=>$itemManager['firstname'],
                        'email'=>$itemManager['email'],
                        'phone'=>$itemManager['phone'],
                        'role'=>'ROLE_MANAGER',
                        'sellers'=>$sellers
                    ];
                }
            }else{
                $usersLists=$repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
                foreach ($usersLists as $itemList){
                    $users[]=[
                        'id'=>$itemList['id'],
                        'is_enabled'=>$itemList['is_enabled'],
                        'lastname'=>$itemList['lastname'],
                        'firstname'=>$itemList['firstname'],
                        'email'=>$itemList['email'],
                        'phone'=>$itemList['phone'],
                        'role'=>'ROLE_MANAGER'
                    ];
                }
            }

            if($request->get('manager_id') and $request->get('manager_id')!=''){
                $roleP='MANAGER';
                $manager_id=$request->get('manager_id');
                $user=$repository_user->find($manager_id);
                $usersLists=$repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
                foreach ($usersLists as $itemList){
                    $users[]=[
                        'id'=>$itemList['id'],
                        'is_enabled'=>$itemList['is_enabled'],
                        'lastname'=>$itemList['lastname'],
                        'firstname'=>$itemList['firstname'],
                        'email'=>$itemList['email'],
                        'phone'=>$itemList['phone'],
                        'role'=>'ROLE_SELLER'
                    ];
                }
            }
        }
        if ($role=='ROLE_MANAGER') {
            $roleP='MANAGER';
            $manager_id=$userId;
            $usersLists=$repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
            foreach ($usersLists as $itemList){
                $users[]=[
                    'id'=>$itemList['id'],
                    'is_enabled'=>$itemList['is_enabled'],
                    'lastname'=>$itemList['lastname'],
                    'firstname'=>$itemList['firstname'],
                    'email'=>$itemList['email'],
                    'phone'=>$itemList['phone'],
                    'role'=>'ROLE_SELLER'
                ];
            }
        }

        return $this->render('effectif/index.html.twig', [
            'pageTitle' => 'Effectifs',
            'rootTemplate' => 'effectif',
            'pageIcon' => 'group',
            'rootPage' => 'lists',
            'pageColor' => 'md-bg-grey-100',

            //data
            'users'=>$users,
            'm' => $month,
            'yearChoise' => $year,
            'userId' => $userId,
            'isAll' => $isAll,
            'user' => $user,
            'role'=>$roleP,
            'isEnabled'=>$isEnabled
        ]);
    }

    /**
     * @Route("/edit/{userId}", name="edit")
     */
    public function edit(Request $request, UserInterface $user, $userId): Response
    {

        $cpv = $user->getCpvCourtierExploitant();
        $userIdConnect = $user->getId();
        $role = $user->getRoles();
        $userParentId = $user->getParent();

        $displayOperatorId = false;

        $operatorDetailsArray = [];
        $operatorDetailsForParentArray = [];

        $getSectors_userConnected = [];
        $getSectors_userToEdit = [];

        $getCities_userToEdit = [];
        $getCities_userConnected = [];

        $operatorDetailsArray_forBoIsRequired = [];
        $operatorDetailsForParentArray_forBoIsRequired = [];

        $operatorDetailsArray_forBtoCIsRequired = [];
        $operatorDetailsForParentArray_forBtoCIsRequired = [];

        $operatorDetailsArray_forBtoBIsRequired = [];
        $operatorDetailsForParentArray_forBtoBIsRequired = [];

        $getParentAutoModification = null;
        $getChildrenOfUserConnectedViewDisplay = false;

        $repository = $this->getDoctrine()->getRepository(User::class);

        // Get infos of user to Edit
        $userInfos = $repository->find($userId);
        $responsableInfos = $repository->find($userInfos->getParent());

        // GEt operators Id for selection of product : FOR THIS PARENT
        $parentInfos = $repository->find($userInfos->getParent());
        $operatorIdListForParentArray = explode(",", $parentInfos->getOperatorId());

        foreach ($operatorIdListForParentArray as $key => $operatorId) {

            // details de l'operrator du parent
            $operatorIdDetails = $this->getDoctrine()->getRepository(NmdPartner::class)->OperatorIdDetails($operatorId);
            array_push($operatorDetailsForParentArray, $operatorIdDetails);
        }

        // Display or not the select menu for operators Id
        if (count($operatorIdListForParentArray) > 1){
            $displayOperatorId = true;
        }else {
            $displayOperatorId = false;
        }

        // Get List of responsable for defaultDisplay
        $userToEditRoleArray = $userInfos->getRoles();

        if (in_array("ROLE_DIRECTOR", $userToEditRoleArray)) {
            $roleChoice = "director";

        }elseif(in_array("ROLE_TELEOP", $userToEditRoleArray)){
            $roleChoice = "teleOperator";

        }elseif(in_array("ROLE_MANAGER", $userToEditRoleArray)){
            $roleChoice = "manager";

        }elseif(in_array("ROLE_ENCADRANT", $userToEditRoleArray)){
            $roleChoice = "encadrant";

        }else{
            $roleChoice = "seller";
        }

        // For Parent choice
        if ($this->security->isGranted('ROLE_SFR')) {

            $userCpvToEdit = $userInfos->getCpvCourtierExploitant();
            $userCpvArray = explode(",",$userCpvToEdit);

            $countUserArray = count($userCpvArray);

            if($countUserArray > 1){
                $getParent = $repository->GetParentNewUser_fromSFRView();

            }else{
                $userCpv = $userCpvToEdit;
                $getParent = $repository->GetParentNewUser_fromCompany($roleChoice, $userCpv);
            }
        }

        if ($this->security->isGranted('ROLE_COMPANY')) {
            $userCpv = $cpv;
            $getParent = $repository->GetParentNewUser_fromCompany($roleChoice, $userCpv);
        }

        if ($this->security->isGranted('ROLE_DIRECTOR') or $this->security->isGranted('ROLE_MANAGER') or $this->security->isGranted('ROLE_SELLER') ) {
            $userCpv = $cpv;
            $getParent = $repository->GetParentNewUser_fromDirectorManager($roleChoice, $userCpv , $userIdConnect);
        }

        if ($this->security->isGranted('ROLE_SELLER')) {
            $userCpv = $cpv;
            $getParent = $repository->GetParentNewUser_fromSeller($userParentId , $userCpv);
        }

        if ($this->security->isGranted('ROLE_TELEOP')) {
            $userCpv = $cpv;
            $getParent = $repository->GetParentNewUser_fromSeller($userParentId , $userCpv);
        }

        // CASE OF AUTO MODIFICATION
        if ($userId == $userIdConnect ){
            $getParentAutoModification = $repository->GetParentNewUser_ForAutoModification($userParentId);
        }

        //
        // ─────────────────────────────────────────────────── ACCOUNT ─────
        //

        // Account of current userid to edit
        $getListOfcpv = $userInfos->getCpvCourtierExploitant();
        $cpvArray = explode(",", $getListOfcpv);
        $countGetChildrenOfUserConnected = count($cpvArray);

        // to get all pv que peut avoir le user connecté
        // Si c'est SFR , il y aura tous
        $getcpvPossibleOfUserConnected = $repository->GetCompanyChildrenOfUserConnected($userIdConnect);
        $countCpvPossibleOfUserConnected = count($getcpvPossibleOfUserConnected);

        $accountArrayForuserConnected = [];
        foreach ($cpvArray as $key => $cpv) {
            $getChildrenOfUserConnected = $repository->GetCompanyOfUserConnectedByCpv($cpv);
            array_push($accountArrayForuserConnected, $getChildrenOfUserConnected);
        }

        if ($this->security->isGranted('ROLE_SFR')) {
            if($countCpvPossibleOfUserConnected > 1){
                $getChildrenOfUserConnectedViewDisplay = true;
            }
        }else{
            if($getcpvPossibleOfUserConnected > 1 ){
                $getChildrenOfUserConnectedViewDisplay = true;
            }
        }

        // GEt operators Id for selection of BO is required : FOR THIS USER----------------------
        $operatorIdListArray_forBoIsRequired = explode(",", $userInfos->getIsValidationBoRequiredByoperators());

        if($operatorIdListArray_forBoIsRequired != null && !empty($operatorIdListArray_forBoIsRequired) ){

            foreach ($operatorIdListArray_forBoIsRequired as $key => $operatorId) {

                $operatorIdDetails = $this->getDoctrine()->getRepository(NmdPartner::class)->OperatorIdDetails($operatorId);
                array_push($operatorDetailsArray_forBoIsRequired, $operatorIdDetails);
            }

        }

        // GEt operators Id for selection of BTOC is required : FOR THIS USER----------------------
        $operatorDetailsArray_forBtoCIsRequired = explode(",", $userInfos->getIsBtobByoperators());

        if($operatorDetailsArray_forBtoCIsRequired != null && !empty($operatorDetailsArray_forBtoCIsRequired) ){

            foreach ($operatorDetailsArray_forBtoCIsRequired as $key => $operatorId) {

                $operatorIdDetails = $this->getDoctrine()->getRepository(NmdPartner::class)->OperatorIdDetails($operatorId);
                array_push($operatorDetailsForParentArray_forBtoCIsRequired, $operatorIdDetails);
            }

        }

        // GEt operators Id for selection of BTOB is required : FOR THIS USER----------------------
        $operatorDetailsArray_forBtoBIsRequired = explode(",", $userInfos->getIsBtocByoperators());

        if($operatorDetailsArray_forBtoBIsRequired != null && !empty($operatorDetailsArray_forBtoBIsRequired) ){

            foreach ($operatorDetailsArray_forBtoBIsRequired as $key => $operatorId) {

                $operatorIdDetails = $this->getDoctrine()->getRepository(NmdPartner::class)->OperatorIdDetails($operatorId);
                array_push($operatorDetailsForParentArray_forBtoBIsRequired, $operatorIdDetails);
            }

        }

        return $this->render('effectif/edit.html.twig', [
            'pageTitle' => 'Effectifs',
            'rootTemplate' => 'effectif',
            'pageIcon' => 'group',
            'rootPage' => 'edit',
            'pageColor' => 'md-bg-grey-100',

            'user'=>$user,
            'userCpv' => $cpv,
            'userId'=>$userIdConnect,
            'userRole'=>$role,
            'userInfos'=>$userInfos,

            'operatorDetailsArray'=>$operatorDetailsArray,
            'operatorDetailsForParentArray' => $operatorDetailsForParentArray,
            'displayOperatorId' => $displayOperatorId,

            'responsableInfos' => $responsableInfos,
            'userToEditRoleArray' => $userToEditRoleArray,
            'getParent' => $getParent,
            'userIdToEdit' => $userId,
            'getParentAutoModification' => $getParentAutoModification,
            'roleChoice' => $roleChoice,

            'getSectors_userToEdit' => $getSectors_userToEdit,
            'getSectors_userConnected' => $getSectors_userConnected,

            'getCities_userToEdit' => $getCities_userToEdit,
            'getCities_userConnected' => $getCities_userConnected,

            'accountArrayForuserConnected' => $accountArrayForuserConnected,
            'getcpvPossibleOfUserConnected' => $getcpvPossibleOfUserConnected,

            'countGetChildrenOfUserConnected' => $countGetChildrenOfUserConnected,
            'countCpvPossibleOfUserConnected' => $countCpvPossibleOfUserConnected,

            'getChildrenOfUserConnectedViewDisplay' => $getChildrenOfUserConnectedViewDisplay,

            'operatorDetailsArray_forBoIsRequired'=>$operatorDetailsArray_forBoIsRequired,

            'operatorDetailsArray_forBtoCIsRequired' => $operatorDetailsArray_forBtoCIsRequired,

            'operatorDetailsArray_forBtoBIsRequired' => $operatorDetailsArray_forBtoBIsRequired,

        ]);
    }
}
