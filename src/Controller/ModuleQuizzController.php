<?php

namespace App\Controller;

use App\Repository\QuestionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;


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
use App\Repository\PallierRepository;
use Symfony\Component\Validator\Constraints\Json;

class ModuleQuizzController extends AbstractController
{
  /**
   * @Route("/module/quizz", name="module_quizz")
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

      return $this->redirectToRoute('module', ['id_formation' => $formation->getId()]);
    }

    return $this->renderForm('module_quizz/index.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'form' => $form,
    ]);
  }

  /**
   * @Route("/module/{id_formation}", name="module")
   */
  public function module(int $id_formation = 1, Request $request): Response
  {

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
      return $this->redirectToRoute('formations');
    }
    if($request->get('submit') == 'annuler'){
        $message = sprintf('Création de module abandonnée');
        $this->addFlash('', $message);
        return $this->redirectToRoute('formations');
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
   * @Route("/createQuestion", name="createQuestion")
   */
  public function createQuestion(Request $request): Response
  {
    $palliers = ["0" => ["id" => 1, "timecode" => 2.3]];
    if ($request->isXmlHttpRequest()) {
      $repositoryPallier = $this->getDoctrine()->getRepository(Pallier::class);
      $palliers = $repositoryPallier->pallierByVideo($request->request->get("idVideo"));
      return new JsonResponse(json_encode($palliers));
    }


    $repository = $this->getDoctrine()->getRepository(Type::class);
    $types = $repository->findAll();
    $repositoryFormation =  $this->getDoctrine()->getRepository(Formation::class);
    $repositoryModule=  $this->getDoctrine()->getRepository(Module::class);

    $formation = $repositoryFormation->findAll();
    $modules =$repositoryModule->findAll();
    $repositoryLvl = $this->getDoctrine()->getRepository(Level::class);
    $levels = $repositoryLvl->findAll();
    $repositoryType = $this->getDoctrine()->getRepository(Type::class);
    $repositoryVideo = $this->getDoctrine()->getRepository(Video::class);
    $repositoryPalier = $this->getDoctrine()->getRepository(Pallier::class);
    $videos = $repositoryVideo->findAll();
    $palierTime= $repositoryPalier->allPallier();
    $repositoryProposition = $this->getDoctrine()->getRepository(Proposition::class);
    
    // ce que j'enredistre dans la bdd 

    $question = new Question();
    $palliers = new Pallier();
    

    // enregistrement du palier

    if (isset($_POST['questionLibelle']) && isset($_POST['lvl'])) {
      $entityManager = $this->getDoctrine()->getManager();

      $palliers->setTimecode($_POST['pallier']);
    
      $entityManager->persist($palliers);

      $entityManager->flush();
    }

    // enregistrement de la question 

    if (isset($_POST['questionLibelle']) && isset($_POST['lvl'])) {
      $entityManager = $this->getDoctrine()->getManager();

      $question->setLibelle($_POST['questionLibelle']);
      $question->setIdModule($repositoryModule->find($_POST['Module']));
      $question->setCreatedAt(new \DateTime());
      $question->setModifyAt(new \DateTime());
      $question->setIdLvl($repositoryLvl->find($_POST['lvl']));
      $question->setIdType($repositoryType->find($_POST['type']));
      $question->setIdVideo($repositoryVideo->find($_POST['video']));
      $question->getId();
      // $pallier_id = 1;
      $pallier_id = 0;
      foreach ($palierTime as $palier) {
 

        if ($palier["timecode"] == $_POST['pallier'] ) {
          $pallier_id = $palier["id"];
         //print_r(2);

        }
        
       }
       
      $question->setIdPallier($repositoryPalier->find($pallier_id));
      // var_dump($_POST);
      
      // var_dump( $palierTime);
       //var_dump($_POST['pallier']);
      

       $entityManager->persist($question);

       $entityManager->flush();
       

      // return new Response('Saved new product with id ' . $question->getId());
    }

    // enrefistrment des proposition
    for ($i = 1; $i < 30; $i++) {
     
    if (isset($_POST['libelleProps1']) ) {
      $entityManager = $this->getDoctrine()->getManager();
      if(isset($_POST['libelleProps' . $i])) {
          $proposition = new Proposition();
          $id_question = $question->getId();
   
          $proposition ->setLibelle($_POST['libelleProps' . $i]);
          $proposition->setIdQuestion($repositoryProposition->find($id_question));
          var_dump($id_question);
    
        }
      }
      $entityManager->flush();

      $entityManager->persist($proposition);
    }


    return $this->render('module_quizz/creationQuestion.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'types' => $types,
      'formation' => $formation,
      'levels' => $levels,
      'videos' => $videos,
      'palliers' => $palliers,
      'modules'=> $modules,
      'pageTitle' => 'Creation Question',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
    ]);
  }

  /**
   * @Route("/formations", name="formations")
   */
  public function displayFormations(Request $request): Response
  {
    $repository = $this->getDoctrine()->getRepository(Formation::class);
    $allFormations=$repository->findBy(array(), array('id' => 'ASC'));
    
    $formations=[];
    foreach ($allFormations as $formation){
      $allModules = $formation->getModules();
      $modules = [];
      foreach ($allModules as $module){
        $level = $this->getDoctrine()->getRepository(Level::class)->find($module->getIdLvl());
        $modules[]=[
          'id'=>$module->getId(),
          'nom'=>$module->getNom(),
          'level'=>$level->getNom()
        ];
      }
      $formations[]=[
        'id'=>$formation->getId(),
        'nom'=>$formation->getNom(),
        'modules'=>$modules
      ];
    }

    return $this->render('formations/index.html.twig', [
        'pageTitle' => 'Formations évaluation',
        'rootTemplate' => 'module_quizz',
        'pageIcon' => 'group',
        'rootPage' => 'lists',
        'pageColor' => 'md-bg-grey-100',

        'formations'=>$formations
    ]);
  }
}
