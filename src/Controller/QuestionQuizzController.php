<?php

namespace App\Controller;

use App\Repository\QuestionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Question;
use App\Entity\Pallier;
use App\Entity\Proposition;
use App\Repository\FormationRepository;
use App\Repository\LevelRepository;
use App\Repository\ModuleRepository;
use App\Repository\PallierRepository;
use App\Repository\PropositionRepository;
use App\Repository\TypeRepository;
use App\Repository\VideoRepository;
/**
 * @Route("/Question", name="Question_")
 */
class QuestionQuizzController extends AbstractController

{
  

  /**
   * @Route("/createQuestion/{idModule}", name="createQuestion")
   */
  public function createQuestion(Request $request, $idModule,PallierRepository $pallierRepository,QuestionRepository $questionRepository ,
  TypeRepository $typeRepository,VideoRepository $videoRepository, FormationRepository $formationRepository,ModuleRepository $moduleRepository,
  LevelRepository $levelRepository): Response
  {
    $selected = "";

    //recuperation des palier
    $listepalier = $pallierRepository->allPallier();

    //recuperation des type de question 
    $types = $typeRepository->findAll();

    //recuperation des formation
    $formation = $formationRepository->findAll();

    //recuperation des module
    $modules = $moduleRepository->findAll();

    //recuperation des level
    $levels = $levelRepository->findAll();

    //recuperation des question
    $questions = $questionRepository->findAll();

    //recuperation des video
    $videos = $videoRepository->findAll();
   
    if (isset($_GET["moduleId"])) {
      $palliers = $pallierRepository->allPallier();
      if (isset($_POST['annuler'])) {
        return $this->redirectToRoute('Question_listequestion', ['moduleId' => $idModule]);
      }
    }

    //on soccupe de la question    
    $question = new Question();

    // enregistrement de la question 
  
    // on verifie que le formulaire n'est pas vide
    if (isset($_POST['questionLibelle']) && isset($_POST['lvl'])) {

      if (isset($_POST['submit'])) {
        $message = sprintf('Création de question abandonnée');
        $this->addFlash('', $message);
        return $this->redirectToRoute('Formation_formations');
      }

      // set entitymanager pour pouvoir modifier les entity 
      $entityManager = $this->getDoctrine()->getManager();
      
      //on set les info de la nouvelle question 
      $question->setLibelle($_POST['questionLibelle']);
      $id_module = $idModule;
      $question->setIdModule($moduleRepository->find($id_module));
      $question->setCreatedAt(new \DateTime());
      $question->setModifyAt(new \DateTime());
      $question->setIdLvl($levelRepository->find($_POST['lvl']));
      $question->setIdType($typeRepository->find($_POST['type']));
      $question->setIdVideo($videoRepository->find($_POST['video']));

      //creation des order pour la question
      $order = 0;
      $id_N_question = $question->getId();
      foreach ($questions as $laquestion) {

        $id_question = $laquestion->getIdPallier();

        if ($id_question == $id_N_question) {
          $order = $order + 1;
        }
      }
      $question->setOrdering($order);
      

      //on soccupe du palier 
      // $pallier_id = 1;

      // enregistrement du palier
      
      $palierTime = $pallierRepository->pallierByVideo($question->getIdVideo());
      $pallier_id = 0;

      //conversion du timecode user en datetime

      // verifie si l'utilisateur a rentrer un palier dans le menue deroulent
      if (isset($_POST["palliers"]) && $_POST["palliers"] != 0) {
        
        $question->setIdPallier($pallierRepository->find($_POST["palliers"]));
        
      }elseif (isset($_POST["pallier"])) {
        
        //nouveau pallier 
        $palier = new Pallier;
        $palier->setTimecode($_POST['pallier']);
        $palier->setTitreGroupeQuestion($_POST['titrePallier']);

        //enregistrement du nouveau pallier
        $entityManager->persist($palier);
        $entityManager->flush();

        //set l'id question pallier avec l'id du nouveau pallier 
        $id_pallier = $palier->getId();
        $question->setIdPallier($pallierRepository->find($id_pallier));

       
      }

      //enregistement nouvelle question
      $entityManager->persist($question);
      $entityManager->flush();
      
      //si le type de question est choix multiple on redirige ver new prop
      if (($_POST['type']) == 1 || ($_POST['type']) == 3 ) {
        return $this->redirectToRoute('Proposition_createProposition', ['questionId' => $question->getId()]); 
      }else {
        return $this->redirectToRoute('Question_listequestion', ['moduleId' => $id_module]);
      }
        
    }

    return $this->render('module_quizz/creationQuestion.html.twig', [
      'controller_name' => 'QuestionQuizzController',
      'types' => $types,
      'formation' => $formation,
      'levels' => $levels,
      'videos' => $videos,
      'modules' => $modules,
      'pageTitle' => 'Creation de question',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
      'listePallier' => $listepalier,
      'idModule' => $idModule,
    ]);
  
}



  /**
   * @Route("/listequestion/{moduleId}", name="listequestion")
   */
  public function listequestion(Request $request, UserInterface $userI, QuestionRepository $questionRepository, PropositionRepository $propositionRepository, $moduleId, ModuleRepository $moduleRepository): Response
  {

     $all = $questionRepository->isall();
    $module = $moduleRepository->find($moduleId);
    $proposition = $propositionRepository->findAll();

    return $this->render('module_quizz/listequestion.html.twig', [
      'pageTitle' => 'Liste des questions',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      //data
     
   
      'all' => $all,
      'proposition' => $proposition,
      'moduleId' => $moduleId,
      'module' => $module,
      

    ]);
  }

  /**
   * @Route("/edit/{categoryId}", name="edit")
   */
  public function edit(Request $request, userinterface $user, $categoryId, PropositionRepository $propositionRepository,
   PallierRepository $pallierRepository, QuestionRepository $RepositoryQuestion,
   LevelRepository $levelRepository,TypeRepository $typeRepository,FormationRepository $formationRepository
   ,ModuleRepository $moduleRepository, QuestionRepository $questionRepository,VideoRepository $videoRepository): Response
  {
    $listepalier = $pallierRepository->findAll();
  
       //dd($request->request->get('libelle'));  // POST PUT UPDATE
    //dd($request->queryy->get('libelle')); // GET DELETE 
    $la_question = $RepositoryQuestion->find($categoryId);
    $id_du_module = $la_question->getIdModule();
    $id_du_palier_question = $la_question->getIdPallier()->getId();
    $id_du_level_question = $la_question->getIdLvl();
    $id_du_type_question = $la_question->getIdType();
    $id_de_la_video = $la_question->getIdVideo();
    $video_actuel = $videoRepository->find($id_de_la_video);
    $palier_actuel = $pallierRepository->find($id_du_palier_question);
    $id = $palier_actuel->getId();
    $level_actuel = $levelRepository->find($id_du_level_question);
    
    $type_actuel = $typeRepository->find($id_du_type_question);
  

     foreach ($listepalier as $lpalier) {
       $idlpalier = $lpalier->getId();
        if($idlpalier == $id){
         $palier_actuel = $lpalier;
         $titre_actuel = $palier_actuel->getTitreGroupeQuestion();
        //  dd($palier_actuel);
     }
     }
    
    if (isset($_POST['annuler'])) {
      $message = sprintf('modification question abandonnée');
      $this->addFlash('', $message);
      return $this->redirectToRoute("Formation_formations");
    }

    $categoryInfos = $RepositoryQuestion->find($categoryId);
    //recuperation des palier
    $listepalier = $pallierRepository->allPallier();

    //recuperation des type de question 
    $types = $typeRepository->findAll();

    //recuperation des formation
    $formation = $formationRepository->findAll();

    //recuperation des module
    $modules = $moduleRepository->findAll();

    //recuperation des level
    $levels = $levelRepository->findAll();

    //recuperation des question
    $questions = $questionRepository->findAll();

    //recuperation des video
    $videos = $videoRepository->findAll();



    //on soccupe de la question   
    // set entitymanager pour pouvoir modifier les entity 
    if (isset($_POST['id'])) {
      $entityManager = $this->getDoctrine()->getManager();
      $question = $entityManager->getRepository(Question::class)->find($_POST['id']);
    }
    
    // enregistrement de la question 

    // on verifie que le formulaire n'est pas vide
    if (isset($_POST['questionLibelle']) && isset($_POST['lvl'])) {

      if (isset($_POST['submit'])) {
        $message = sprintf('Création de question abandonnée');
        $this->addFlash('', $message);
        return $this->redirectToRoute('Formation_formations');
      }



      //on set les info de la nouvelle question 
      $question->setLibelle($_POST['questionLibelle']);
      $question->setCreatedAt(new \DateTime());
      $question->setModifyAt(new \DateTime());
      $question->setIdLvl($levelRepository->find($_POST['lvl']));
      if ($type_actuel != $_POST['type'] )  {
        if ($_POST['type'] == 3 || $_POST['type'] == 2) {
        $prop = $propositionRepository->propositionParQuestion($categoryId);
        
       foreach ($prop as $props) {
          $idprops = $props['id'];
          $props = $entityManager->getRepository(Proposition::class)->find($idprops);
          $entityManager->remove($props);
          $entityManager->flush();
       }
        
      }
      }
      $question->setIdType($typeRepository->find($_POST['type']));
      $question->setIdVideo($videoRepository->find($_POST['video']));

      //creation des order pour la question
      $order = 0;
      $id_N_question = $question->getId();
      foreach ($questions as $laquestion) {

        $id_question = $laquestion->getIdPallier();
        $id_question = $id_question->getId();

        if ($id_question == $id_N_question) {
          $order = $order + 1;
        }
      }
      $question->setOrdering($order);


      //on soccupe du palier 
      // $pallier_id = 1;

      // enregistrement du palier

      $palierTime = $pallierRepository->pallierByVideo($question->getIdVideo());
      $pallier_id = 0;

      //conversion du timecode user en datetime

      // verifie si l'utilisateur a rentrer un palier dans le menue deroulent
      if (isset($_POST["palliers"]) && $_POST["palliers"] != 0) {

        $question->setIdPallier($pallierRepository->find($_POST["palliers"]));
      } elseif (isset($_POST["pallier"])) {

        //nouveau pallier 
        $palier = new Pallier;
        $palier->setTimecode($_POST['pallier']);
        $palier->setTitreGroupeQuestion($_POST['titrePallier']);

        //enregistrement du nouveau pallier
        $entityManager->persist($palier);
        $entityManager->flush();

        //set l'id question pallier avec l'id du nouveau pallier 
        $id_pallier = $palier->getId();
        $question->setIdPallier($pallierRepository->find($id_pallier));
      }

      //enregistement nouvelle question
      $entityManager->persist($question);
      $entityManager->flush();

  
        return $this->redirectToRoute('Question_listequestion', ['moduleId' => $id_du_module->getId()]);
      }
    
    

    return $this->render('module_quizz/edit.html.twig', [
      'pageTitle' => 'Modification',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'edit',
      'pageColor' => 'md-bg-grey-100',

      'user' => $user,
      'categoryId' => $categoryId,
      'categoryInfos' => $categoryInfos,
      'types' => $types,
      'formation' => $formation,
      'levels' => $levels,
      'videos' => $videos,
      'palliers' => $listepalier,
      'modules' => $modules,
      'listePallier' => $listepalier,
      'palier_actuel'=> $palier_actuel,
      'titre_actuel' => $titre_actuel,
      'level'=> $level_actuel,
      'type' => $type_actuel,
      'video' => $video_actuel,
      

    ]);
  }

  /**
   * @Route("/deleteQuestion/{questionId} ", name="deleteQuestion")
   */
  public function deleteQuestion(Request $request, userinterface $user, $questionId): Response
  {

    $repository_question = $this->getDoctrine()->getRepository(Question::class);
    $question = $repository_question->find($questionId);

    $manager = $this->getDoctrine()->getManager();
    $manager->remove($question);
    $manager->flush();
    $message = sprintf('Question supprimé !');
    $this->addFlash('', $message);

    return $this->redirectToRoute("Formation_formations");
  }
}
