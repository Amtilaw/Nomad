<?php

namespace App\Controller;

use App\Repository\QuestionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\NmdPartner;
use App\Entity\NmdUserConfiguration;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;
use App\Entity\NmdCategorieProduct;


use App\Form\FormationType;
use App\Form\ModuleType;
use App\Form\LevelType;
use App\Entity\Formation;
use App\Entity\Module;
use App\Entity\Level;
use App\Entity\Question;
use App\Entity\Pallier;
use App\Entity\Type;
use App\Entity\Proposition;
use App\Entity\Video;
use App\Repository\ModuleRepository;
use App\Repository\NmdProductRepository;
use App\Repository\PallierRepository;
use App\Repository\PropositionRepository;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Validator\Constraints\Json;


/**
 * @Route("/module", name="module_")
 */
class ModuleQuizzController extends AbstractController

{
  private $nmdNproductRepository;

  public function __construct(
    NmdProductRepository $nmdNproductRepository
  ) {
    $this->nmdNproductRepository = $nmdNproductRepository;
  }
  /**
   * @Route("/module_quizz", name="module_quizz")
   */
  public function index(Request $request): Response
  {
    $formation = new Formation();

    $form = $this->createForm(FormationType::class, $formation);


    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $formation = $form->getData();
      $entityManager->persist($formation);

      $entityManager->flush();
      $message = sprintf('Création de formation reussi!');
      $this->addFlash('notice', $message);

      return $this->redirectToRoute('module_formations');
    }

    return $this->renderForm('module_quizz/index.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'form' => $form,
      'pageTitle' => 'Création de Module',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
    ]);
  }

  /**
   * @Route("/module/{id_formation}", name="module")
   */
  public function module(int $id_formation = 1, Request $request): Response
  {

    if ($request->get('submit') == 'annuler') {
      $message = sprintf('Création de module abandonnée');
      $this->addFlash('', $message);
      return $this->redirectToRoute('module_formations');
    }

    $repository = $this->getDoctrine()->getRepository(Formation::class);
    $formations = $repository->findAll();

    $repositoryLvl = $this->getDoctrine()->getRepository(Level::class);
    $levels = $repositoryLvl->findAll();

    $module = new Module();

    if (isset($_POST['moduleName']) && isset($_POST['formation'])) {
      $entityManager = $this->getDoctrine()->getManager();

      $dateTime = new \DateTime();
      $dateTime->format('Y-m-d H:i:s');

      $module->setNom($_POST['moduleName']);
      $module->setCreatedAt($dateTime);

      $module->setIdFormation($repository->find($_POST['formation']));

      $module->setIdLvl($repositoryLvl->find($_POST['lvl']));

      $entityManager->persist($module);

      $entityManager->flush();

      $message = sprintf('Module créé');
      $this->addFlash('notice', $message);
      return $this->redirectToRoute('module_formations');
    }



    return $this->render('module_quizz/creationModule.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'id_formation' => $id_formation,
      'formations' => $formations,
      'levels' => $levels,
      'pageTitle' => 'Création de Module',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
    ]);
  }

  /**
   * @Route("/createQuestion{idQuestion}", name="createQuestion")
   */
  public function createQuestion(Request $request, $idQuestion = null): Response
  {

    $selected = "";


    $repository = $this->getDoctrine()->getRepository(Type::class);
    $types = $repository->findAll();
    $repositoryFormation =  $this->getDoctrine()->getRepository(Formation::class);
    $repositoryModule =  $this->getDoctrine()->getRepository(Module::class);

    $formation = $repositoryFormation->findAll();
    $modules = $repositoryModule->findAll();
    $repositoryLvl = $this->getDoctrine()->getRepository(Level::class);
    $levels = $repositoryLvl->findAll();
    $repositoryType = $this->getDoctrine()->getRepository(Type::class);
    $repositoryVideo = $this->getDoctrine()->getRepository(Video::class);
    $repositoryPalier = $this->getDoctrine()->getRepository(Pallier::class);
    $videos = $repositoryVideo->findAll();
    $repositoryProposition = $this->getDoctrine()->getRepository(Proposition::class);
    $repositoryQuestion = $this->getDoctrine()->getRepository(Question::class);



    $question = new Question();

    //La selection par default si une question est ajoute dans un module

    if (isset($idQuestion)) {
      $idFormation = $repositoryQuestion->findByIdFormation($idQuestion);
      $formatio = $repositoryFormation->find($idFormation[0]["id_formation_id"]);
      $formatio->selected = "selected";

      $question = $repositoryQuestion->find($idQuestion)->getIdModule();
      $repositoryModule->find($question)->selected = "selected";

      $repositoryLvl->find($question->getIdLvl())->selected = "selected";
    }



    // ce que j'enredistre dans la bdd 

    // enregistrement de la question 

    if (isset($_POST['questionLibelle']) && isset($_POST['lvl'])) {

      if (isset($_POST['submit'])) {
        $message = sprintf('Création de question abandonnée');
        $this->addFlash('', $message);
        return $this->redirectToRoute('module_formations');
      }
      $entityManager = $this->getDoctrine()->getManager();
      $idModule = $repositoryModule->find($_POST['Module']);
      $idModule = str_replace('', '', $_POST["Module"]);

      $question->setLibelle($_POST['questionLibelle']);
      $question->setIdModule($repositoryModule->find($_POST['Module']));
      $question->setCreatedAt(new \DateTime());
      $question->setModifyAt(new \DateTime());
      $question->setIdLvl($repositoryLvl->find($_POST['lvl']));
      $question->setIdType($repositoryType->find($_POST['type']));
      $question->setIdVideo($repositoryVideo->find($_POST['video']));
      $question->getId();
      // $pallier_id = 1;

      // enregistrement du palier

      $palierTime = $repositoryPalier->pallierByVideo($question->getIdVideo());
      $pallier_id = 0;
      //conversion du timecode user en datetime
      $UserTimecode = explode(".", $_POST["pallier"]);
      $UserTimecode = $UserTimecode[0] * 60 + $UserTimecode[1];
      foreach ($palierTime as $palier) {
        if ($palier["timecode"] == $UserTimecode) {
          $pallier_id = $palier["id_pallier"];
          $ordering = $repositoryQuestion->getOrderingByPallier($pallier_id);
          $ordering = $ordering[0]["ordering"];
          break;
        }
      }
      if ($pallier_id == 0) {
        $palliers = new Pallier();
        $palliers->setTimecode($UserTimecode);
        $palliers->setTitreGroupeQuestion($_POST['titrePallier']);
        $entityManager->persist($palliers);
        $entityManager->flush();
        $question->setIdPallier($palliers);
        $question->setOrdering(1);
      } else {
        $question->setIdPallier($repositoryPalier->find($pallier_id));
        $question->setOrdering($ordering);
      }
      $entityManager->persist($question);

      $entityManager->flush();
      return $this->redirectToRoute('module_createProposition', ['questionId' => $question->getId()]);
    }





    // return new Response('Saved new product with id ' . $question->getId());



    return $this->render('module_quizz/creationQuestion.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'types' => $types,
      'formation' => $formation,
      'levels' => $levels,
      'videos' => $videos,
      'modules' => $modules,
      'pageTitle' => 'Creation Question',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
    ]);
  }

  /**
   * @Route("/createProposition/{questionId}", name="createProposition")
   */
  public function createProposition(Request $request, $questionId): Response
  {

    // enregistrement des proposition
    if (isset($_POST['libelleProps1'])) {
      if (isset($_POST['submit'])) {
        $message = sprintf('Creation proposition abandonnée');
        $this->addFlash('', $message);
        return $this->redirectToRoute("module_formations");
      }
      for ($i = 1; $i < 30; $i++) {

        if (isset($_POST['libelleProps' . $i])) {
          $proposition = new Proposition();
          $repositoryQuestion = $this->getDoctrine()->getRepository(Question::class);

          $id_question = $questionId;
          $idModule = $repositoryQuestion->findIdModule($id_question);
          $idModule = $idModule[0]["id_module_id"];
          $proposition->setLibelle($_POST['libelleProps' . $i]);
          $proposition->setIdQuestion($repositoryQuestion->find($id_question));
          if (isset($_POST['correct' . $i]))
            $proposition->setIsCorrect(0);
          else
            $proposition->setIsCorrect(1);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($proposition);
        $entityManager->flush();
      }
      return $this->redirectToRoute('module_listequestion', ['moduleId' => $idModule]);
    }

    return $this->render('module_quizz/creationProposition.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'pageTitle' => 'Creation Question',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
      'questionId' => $questionId,
    ]);
  }




  /**
   * @Route("/listequestion/{moduleId}", name="listequestion")
   */
  public function listequestion(Request $request, UserInterface $userI, QuestionRepository $questionRepository, PropositionRepository $propositionRepository, $moduleId, ModuleRepository $moduleRepository): Response
  {

    $all = $questionRepository->isall();
    $module = $moduleRepository->find($moduleId);

    $isEnabled = $request->get('isEnabled');
    if ($isEnabled == null or $isEnabled == '') {
      $isEnabled = '1';
    }
    $sqlIsEnabled = "";
    $repository_user = $this->getDoctrine()->getRepository(User::class);
    $userId = $request->get('userId');
    $categoryID = $request->get('categoryId');
    if ($userId == null or $userId == '') {
      $rolesI = $userI->getRoles();
      foreach ($rolesI as $rI) {
        $roleI = $rI;
      }
      if ($roleI == 'ROLE_FINANCIAL') {
        $userId = $userI->getParent();
      }
      if ($roleI == 'ROLE_COMPANY' or $roleI == 'ROLE_USER') {
        $userId = $userI->getId();
      }
    }
    $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    $cpv = $user->getCpvCourtierExploitant();

    $now = new DateTime();
    $month = $now->format('m');
    $year = $now->format('Y');

    $roles = $user->getRoles();
    foreach ($roles as $r) {
      $role = $r;
    }

    $isAll = $request->get('isAll');
    if ($isEnabled == '1') {
      $sqlIsEnabled = " AND is_enabled='1' ";
    } else {
      $sqlIsEnabled = " AND (is_enabled='0' OR is_enabled IS NULL) ";
    }
    if ($role == 'ROLE_COMPANY' or $role == 'ROLE_USER') {
      $roleP = 'COMPANY';
      if ($isAll != null and $isAll == 'yes') {
        $users = [];
        $allDirectors = $repository_user->DirecteursForPartner($cpv, $sqlIsEnabled);
        foreach ($allDirectors as $itemDirector) {
          $managers = [];
          $director_id = $itemDirector['id'];
          $allManagers = $repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
          foreach ($allManagers as $itemManager) {
            $manager_id = $itemManager['id'];
            $sellers = $repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
            $managers[] = [
              'id' => $itemManager['id'],
              'is_enabled' => $itemManager['is_enabled'],
              'lastname' => $itemManager['lastname'],
              'firstname' => $itemManager['firstname'],
              'email' => $itemManager['email'],
              'phone' => $itemManager['phone'],
              'role' => 'ROLE_MANAGER',
              'sellers' => $sellers
            ];
          }

          $users[] = [
            'id' => $itemDirector['id'],
            'is_enabled' => $itemDirector['is_enabled'],
            'lastname' => $itemDirector['lastname'],
            'firstname' => $itemDirector['firstname'],
            'email' => $itemDirector['email'],
            'phone' => $itemDirector['phone'],
            'role' => 'ROLE_DIRECTOR',
            'managers' => $managers
          ];
        }
      } else {
        $users = [];
        $usersLists = $repository_user->DirecteursForPartner($cpv, $sqlIsEnabled);
        foreach ($usersLists as $itemList) {
          $users[] = [
            'id' => $itemList['id'],
            'is_enabled' => $itemList['is_enabled'],
            'lastname' => $itemList['lastname'],
            'firstname' => $itemList['firstname'],
            'email' => $itemList['email'],
            'phone' => $itemList['phone'],
            'role' => 'ROLE_DIRECTOR'
          ];
        }
      }

      if ($request->get('director_id') and $request->get('director_id') != '') {
        $roleP = 'DIRECTOR';
        $director_id = $request->get('director_id');
        $user = $repository_user->find($director_id);
        $users = [];

        if ($isAll != null and $isAll == 'yes') {
          $allManagers = $repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
          foreach ($allManagers as $itemManager) {
            $manager_id = $itemManager['id'];
            $sellers = $repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
            $users[] = [
              'id' => $itemManager['id'],
              'is_enabled' => $itemManager['is_enabled'],
              'lastname' => $itemManager['lastname'],
              'firstname' => $itemManager['firstname'],
              'email' => $itemManager['email'],
              'phone' => $itemManager['phone'],
              'role' => 'ROLE_MANAGER',
              'sellers' => $sellers
            ];
          }
        } else {
          $users = [];
          $usersLists = $repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
          foreach ($usersLists as $itemList) {
            $users[] = [
              'id' => $itemList['id'],
              'is_enabled' => $itemList['is_enabled'],
              'lastname' => $itemList['lastname'],
              'firstname' => $itemList['firstname'],
              'email' => $itemList['email'],
              'phone' => $itemList['phone'],
              'role' => 'ROLE_MANAGER'
            ];
          }
        }
      }
      if ($request->get('manager_id') and $request->get('manager_id') != '') {
        $users = [];
        $roleP = 'MANAGER';
        $manager_id = $request->get('manager_id');
        $user = $repository_user->find($manager_id);
        $usersLists = $repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
        foreach ($usersLists as $itemList) {
          $users[] = [
            'id' => $itemList['id'],
            'is_enabled' => $itemList['is_enabled'],
            'lastname' => $itemList['lastname'],
            'firstname' => $itemList['firstname'],
            'email' => $itemList['email'],
            'phone' => $itemList['phone'],
            'role' => 'ROLE_SELLER'
          ];
        }
      }
    }
    if ($role == 'ROLE_DIRECTOR') {
      $roleP = 'DIRECTOR';
      $director_id = $userId;

      if ($isAll != null and $isAll == 'yes') {
        $allManagers = $repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
        foreach ($allManagers as $itemManager) {
          $manager_id = $itemManager['id'];
          $sellers = $repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
          $users[] = [
            'id' => $itemManager['id'],
            'is_enabled' => $itemManager['is_enabled'],
            'lastname' => $itemManager['lastname'],
            'firstname' => $itemManager['firstname'],
            'email' => $itemManager['email'],
            'phone' => $itemManager['phone'],
            'role' => 'ROLE_MANAGER',
            'sellers' => $sellers
          ];
        }
      } else {
        $usersLists = $repository_user->allManagersForDirecteur($director_id, $sqlIsEnabled);
        foreach ($usersLists as $itemList) {
          $users[] = [
            'id' => $itemList['id'],
            'is_enabled' => $itemList['is_enabled'],
            'lastname' => $itemList['lastname'],
            'firstname' => $itemList['firstname'],
            'email' => $itemList['email'],
            'phone' => $itemList['phone'],
            'role' => 'ROLE_MANAGER'
          ];
        }
      }

      if ($request->get('manager_id') and $request->get('manager_id') != '') {
        $roleP = 'MANAGER';
        $manager_id = $request->get('manager_id');
        $user = $repository_user->find($manager_id);
        $usersLists = $repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
        foreach ($usersLists as $itemList) {
          $users[] = [
            'id' => $itemList['id'],
            'is_enabled' => $itemList['is_enabled'],
            'lastname' => $itemList['lastname'],
            'firstname' => $itemList['firstname'],
            'email' => $itemList['email'],
            'phone' => $itemList['phone'],
            'role' => 'ROLE_SELLER'
          ];
        }
      }
    }
    if ($role == 'ROLE_MANAGER') {
      $roleP = 'MANAGER';
      $manager_id = $userId;
      $usersLists = $repository_user->allVendeursForManager($manager_id, $sqlIsEnabled);
      foreach ($usersLists as $itemList) {
        $users[] = [
          'id' => $itemList['id'],
          'is_enabled' => $itemList['is_enabled'],
          'lastname' => $itemList['lastname'],
          'firstname' => $itemList['firstname'],
          'email' => $itemList['email'],
          'phone' => $itemList['phone'],
          'role' => 'ROLE_SELLER'
        ];
      }
    }

    $proposition = $propositionRepository->findAll();





    return $this->render('module_quizz/listequestion.html.twig', [
      'pageTitle' => 'categorys',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      //data
      'users' => $users,
      'm' => $month,
      'yearChoise' => $year,
      'userId' => $userId,
      'isAll' => $isAll,
      'user' => $user,
      'role' => $roleP,
      'isEnabled' => $isEnabled,
      'all' => $all,
      'proposition' => $proposition,
      'moduleId' => $moduleId,
      'module' => $module,
      'categoryId' => $categoryID

    ]);
  }

  /**
   * @Route("/edit/{categoryId}", name="edit")
   */
  public function edit(Request $request, userinterface $user, $categoryId, PropositionRepository $propositionRepository, PallierRepository $RepositoryPallier, QuestionRepository $RepositoryQuestion): Response
  {

    //dd($request->request->get('libelle'));  // POST PUT UPDATE
    //dd($request->queryy->get('libelle')); // GET DELETE 

    if (isset($_POST['annuler'])) {
      $message = sprintf('modification question abandonnée');
      $this->addFlash('', $message);
      return $this->redirectToRoute("module_formations");
    }

    $categoryInfos = $RepositoryQuestion->find($categoryId);
    $laQuestion = $categoryInfos;
    $Id_palier_question = $laQuestion->getIdPallier();
    if (isset($Id_palier_question)) {
      $timecode = $Id_palier_question->getTimecode();
      $Id_video = $laQuestion->getIdVideo();
      $urlVideo = $Id_video->getUrl();
      $IdlVideo = $Id_video->getId();
      $titrePallier = $Id_palier_question->getTitreGroupeQuestion();
    } else {
      $timecode = null;
      $urlVideo = null;
      $IdlVideo = null;
      $titrePallier = null;
    }

    if (isset($Id_palier_question)) {
      $pallieraafficher = $RepositoryPallier->find($Id_palier_question);
    } else {
      $pallieraafficher = null;
    }

    if ($request->isXmlHttpRequest()) {
      $repositoryPallier = $this->getDoctrine()->getRepository(Pallier::class);
      $palliers = $repositoryPallier->pallierByVideo($request->request->get("idVideo"));
      return new JsonResponse(json_encode($palliers));
    }

    $repository = $this->getDoctrine()->getRepository(Type::class);
    $types = $repository->findAll();
    $repositoryFormation =  $this->getDoctrine()->getRepository(Formation::class);
    $repositoryModule =  $this->getDoctrine()->getRepository(Module::class);


    $formation = $repositoryFormation->findAll();
    $modules = $repositoryModule->findAll();
    $repositoryLvl = $this->getDoctrine()->getRepository(Level::class);
    $levels = $repositoryLvl->findAll();
    $repositoryType = $this->getDoctrine()->getRepository(Type::class);
    $repositoryVideo = $this->getDoctrine()->getRepository(Video::class);
    $repositoryPalier = $this->getDoctrine()->getRepository(Pallier::class);
    $videos = $repositoryVideo->findAll();
    $palierTime = $repositoryPalier->allPallier();
    $repositoryProposition = $this->getDoctrine()->getRepository(Proposition::class);
    $repositoryQuestion = $this->getDoctrine()->getRepository(Question::class);

    if (isset($_POST['id'])) {
      $categoryId = $_POST['id'];
    }
    $palliers = null;
    $entityManager = $this->getDoctrine()->getManager();
    $PropositionAModifier = $propositionRepository->propositionParQuestion($categoryId);
    $repository_quizz = $this->getDoctrine()->getRepository(Question::class);
    if (isset($_POST['id'])) {

      $question = $entityManager->getRepository(Question::class)->find($_POST['id']);
    }



    $entityManager = $this->getDoctrine()->getManager();


    if (isset($_POST['Bouton'])) { // Autre contrôle pour vérifier si la variable $_POST['Bouton'] est bien définie
      for ($i = 0; $i < 30; $i++) {
        if (isset($_POST['libelleProps' . $i]) && isset($_POST['iscorrect' . $i])) {
          if (isset($_POST['id-Prop' . $i])) {

            $proposition = $entityManager->getRepository(Proposition::class)->find($_POST['id-Prop' . $i]);
            $proposition->setLibelle($_POST['libelleProps' . $i]);

            $proposition->setIsCorrect($_POST['iscorrect' . $i]);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposition);
            $entityManager->flush();
          } else {
            // enregistrment des proposition

            if (isset($_POST['libelleProps' . $i])) {
              $proposition = new Proposition();

              $id_question = $question->getId();


              $proposition->setLibelle($_POST['libelleProps' . $i]);
              $proposition->setIdQuestion($repositoryQuestion->find($id_question));
              $proposition->setIsCorrect($_POST['iscorrect' . $i]);

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($proposition);
              $entityManager->flush();
            }
          }
        }
      }

      // creation modification pallier
      if (isset($Id_palier_question)) {
        $paliers = $entityManager->getRepository(Pallier::class)->find($Id_palier_question);
        $paliers->setTimecode($_POST['pallierTimecode']);
        $paliers->setTitreGroupeQuestion($_POST['titrePallier']);
        $entityManager->persist($paliers);

        $entityManager->flush();
      } else {
        $paliers = new Pallier;
        $paliers->setTimecode($_POST['pallierTimecode']);

        $entityManager->persist($paliers);

        $entityManager->flush();
      }
    }

    // creation question 
    if (isset($_POST['libelle'])) {
      $question = $entityManager->getRepository(Question::class)->find($categoryId);
      $question->setLibelle($_POST['libelle']);
      $question->setModifyAt(new \DateTime());
      $pallier_id = 0;
      $pallier_id = $paliers->getId();





      $question->setIdVideo($repositoryVideo->find($_POST['video']));
      $question->setIdPallier($repositoryPalier->find($pallier_id));
      $entityManager->persist($question);
      $entityManager->flush();

      return $this->redirectToRoute('module_listequestion', ['moduleId' => $question->getIdModule()->getId()]);
    }







    return $this->render('module_quizz/edit.html.twig', [
      'pageTitle' => 'categorie',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'edit',
      'pageColor' => 'md-bg-grey-100',

      'user' => $user,
      'categoryId' => $categoryId,
      'categoryInfos' => $categoryInfos,
      'PropositionAModifier' => $PropositionAModifier,
      'types' => $types,
      'formation' => $formation,
      'levels' => $levels,
      'videos' => $videos,
      'palliers' => $palliers,
      'timecode' => $timecode,
      'pallieraafficher' =>  $pallieraafficher,
      'urlVideo' => $urlVideo,
      'modules' => $modules,
      'IdlVideo' => $IdlVideo,
      'titrePallier' => $titrePallier,


    ]);
  }

  /**
   * @Route("/formations", name="formations")
   */
  public function displayFormations(Request $request): Response
  {
    $repository = $this->getDoctrine()->getRepository(Formation::class);
    $allFormations = $repository->findBy(array(), array('id' => 'ASC'));

    $formations = [];
    foreach ($allFormations as $formation) {
      $allModules = $formation->getModules();
      $modules = [];
      foreach ($allModules as $module) {
        $level = $this->getDoctrine()->getRepository(Level::class)->find($module->getIdLvl());
        $allVideo = $this->getDoctrine()->getRepository(Module::class)->allVideos($module->getId());
        $videos = [];
        foreach ($allVideo as $video) {
          $videos[] = [
            'id' => $video["id_video_id"],
            'url' => $video["url"],
          ];
        }
        $modules[] = [
          'id' => $module->getId(),
          'nom' => $module->getNom(),
          'level' => $level->getNom(),
          'videos' => $videos,
        ];
      }
      $formations[] = [
        'id' => $formation->getId(),
        'nom' => $formation->getNom(),
        'modules' => $modules
      ];
    }

    return $this->render('formations/index.html.twig', [
      'pageTitle' => 'Formations évaluation',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      'formations' => $formations
    ]);
  }

  /**
   * @Route("/delete/{categoryId}", name="delete")
   */
  public function delete(Request $request, userinterface $user, $categoryId): Response
  {

    $repository_category = $this->getDoctrine()->getRepository(Question::class);

    $categoryInfos = $repository_category->find($categoryId);
    $moduleId = $repository_category->findIdModule($categoryId);
    $moduleId = $moduleId[0]["id_module_id"];
    // Connexion à MySQL

    $connection = mysqli_connect("127.0.0.1", "root", "", "nomad");

    if (!$connection) { // Contrôler la connexion
      $MessageConnexion = die("connection impossible");
    } else {


      // Requête d'insertion
      $ModifCategory = "DELETE FROM proposition
            where id_question_id = '$categoryId';";

      // Exécution de la reqête
      mysqli_query($connection, $ModifCategory) or die('Erreur SQL !' . $ModifCategory . '<br>' . mysqli_error($connection));
      $ModifCategory = "DELETE FROM question
            where id = '$categoryId';";

      mysqli_query($connection, $ModifCategory) or die('Erreur SQL !' . $ModifCategory . '<br>' . mysqli_error($connection));
      return $this->redirectToRoute('module_listequestion', ['moduleId' => $moduleId]);
    }
  }

  /**
   * @Route("/listeProposition/{questionId}", name="Proposition")
   */
  public function listeProposition(Request $request, UserInterface $userI, QuestionRepository $questionRepository, PropositionRepository $propositionRepository, $questionId, ModuleRepository $moduleRepository): Response
  {

    $questionId = $questionId;
    $question = $questionRepository->find($questionId);
    $proposition = $propositionRepository->AllProposition();

    return $this->render('module_quizz/listeProposition.html.twig', [
      'pageTitle' => 'liste proposition',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      //data

      'proposition' => $proposition,
      'questionId' => $questionId,
      'question' => $question,

    ]);
  }

  /**
   * @Route("/editProposition/{PropositionId}", name="editProposition")
   */
  public function editProposition(Request $request, userinterface $user, $PropositionId, PropositionRepository $propositionRepository, PallierRepository $RepositoryPallier, QuestionRepository $RepositoryQuestion): Response
  {

<<<<<<< HEAD


    $categoryInfos = $propositionRepository->find($PropositionId);


    $QuestionId = $propositionRepository->findIdQuestion($PropositionId);
    $QuestionId = $QuestionId[0]['id_question_id'];
=======
   

    $categoryInfos = $propositionRepository->find($PropositionId);
    $QuestionId = $propositionRepository->findIdQuestion($PropositionId);
    $QuestionId = $QuestionId[0]['id_question_id'];
    dump($QuestionId);
      
>>>>>>> 6711b6cc0bdce95fdf3c73765fffe2e0413ea3d3

    $repository = $this->getDoctrine()->getRepository(Type::class);
    $types = $repository->findAll();

    if (isset($_POST['id'])) {
      $PropositionId = $_POST['id'];
    }

    $palliers = null;
    $entityManager = $this->getDoctrine()->getManager();



    if (isset($_POST['Bouton'])) { // Autre contrôle pour vérifier si la variable $_POST['Bouton'] est bien définie

      if (isset($_POST['libelleProps']) && isset($_POST['iscorrect'])) {
        $proposition = $entityManager->getRepository(Proposition::class)->find($_POST['id-Prop']);

<<<<<<< HEAD
        $proposition->setLibelle($_POST['libelleProps']);

        $proposition->setIsCorrect($_POST['iscorrect']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($proposition);
        $entityManager->flush();

        return $this->redirectToRoute('module_Proposition', ['questionId' => $QuestionId]);
      }
    }
=======
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposition);
            $entityManager->flush();
        }

      return $this->redirectToRoute('module_Proposition', ['questionId'=>$QuestionId]);

      }
>>>>>>> 6711b6cc0bdce95fdf3c73765fffe2e0413ea3d3



    if (isset($_POST['annuler'])) {
      return $this->redirectToRoute('module_Proposition', ['questionId' => $QuestionId]);
    }


    return $this->render('module_quizz/editProposition.html.twig', [
      'pageTitle' => 'categorie',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'edit',
      'pageColor' => 'md-bg-grey-100',

      'user' => $user,
      'categoryId' => $PropositionId,
      'PropositionInfos' => $categoryInfos,
      'types' => $types,
      'palliers' => $palliers,
    ]);
  }
  /**
   * @Route("/deleteProposition/{PropositionId}", name="deleteProposition")
   */
<<<<<<< HEAD
  public function deleteProposition(Request $request, PropositionRepository $propositionRepository, userinterface $user, $PropositionId): Response
=======
  public function deleteProposition(Request$request, PropositionRepository $propositionRepository, userinterface $user, $PropositionId): Response
>>>>>>> 6711b6cc0bdce95fdf3c73765fffe2e0413ea3d3
  {
    $entityManager = $this->getDoctrine()->getManager();
    $repository_category = $this->getDoctrine()->getRepository(NmdCategorieProduct::class);
    $QuestionId = $propositionRepository->findIdQuestion($PropositionId);
    $QuestionId = $QuestionId[0]['id_question_id'];
    $categoryInfos = $repository_category->find($PropositionId);
    // suppresion de la proposition 
<<<<<<< HEAD
    $proposition = $propositionRepository->find($PropositionId);
=======
    $proposition = $propositionRepository ->find($PropositionId);
>>>>>>> 6711b6cc0bdce95fdf3c73765fffe2e0413ea3d3
    $entityManager->remove($proposition);
    $entityManager->flush();
    $message = sprintf('Proposition supprime');
    $this->addFlash('', $message);

    
    return $this->redirectToRoute('module_Proposition', ['questionId' => $QuestionId]);

<<<<<<< HEAD
    return $this->redirectToRoute('module_Proposition', ['questionId' => $QuestionId]);
=======
>>>>>>> 6711b6cc0bdce95fdf3c73765fffe2e0413ea3d3
  }

  /**
   * @Route("/deleteFormation/{formationId}", name="deleteFormation")
   */
  public function deleteFormation(Request $request, userinterface $user, $formationId): Response
  {

    $repository_formation = $this->getDoctrine()->getRepository(formation::class);
    $formation = $repository_formation->find($formationId);

    $manager = $this->getDoctrine()->getManager();
    $manager->remove($formation);
    $manager->flush();
    $message = sprintf('formation supprime');
    $this->addFlash('', $message);

    return $this->redirectToRoute("module_formations");
  }
}
