<?php

namespace App\Controller;

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

      return new Response('Saved new product with id ' . $module->getId());
    }


    return $this->render('module_quizz/creationModule.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'id_formation' => $id_formation,
      'formations' => $formations,
      'levels' => $levels,
    ]);
  }

  /**
   * @Route("/createQuestion", name="createQuestion")
   */
  public function createQuestion(Request $request): Response
  {
    $palliers = ["0" => ["id" => 1, "timecode" => 2.3]];



    $repository = $this->getDoctrine()->getRepository(Type::class);
    $types = $repository->findAll();
    $repositoryLvl = $this->getDoctrine()->getRepository(Level::class);
    $levels = $repositoryLvl->findAll();

    $repositoryVideo = $this->getDoctrine()->getRepository(Video::class);
    $videos = $repositoryVideo->findAll();



    $question = new Question();

    if (isset($_POST['questionLibelle']) && isset($_POST['lvl'])) {
      $entityManager = $this->getDoctrine()->getManager();

      $question->setLibellle($_POST['questionLibelle']);

      $module->setIdFormation($repository->find($_POST['formation']));

      $module->setIdLvl($repositoryLvl->find($_POST['lvl']));

      $entityManager->persist($module);

      $entityManager->flush();

      return new Response('Saved new product with id ' . $module->getId());
    }
    if ($request->isXmlHttpRequest()) {
      $repositoryPallier = $this->getDoctrine()->getRepository(Pallier::class);
      $palliers = $repositoryPallier->pallierByVideo($request->request->get("idVideo"));

      $response = array(
        "palliers" => $palliers,
        "response" => $this->render('module_quizz/creationQuestion.html.twig')->getContent()
      );
      dd($response);
      return new JsonResponse($response);
    }


    return $this->render('module_quizz/creationQuestion.html.twig', [
      'controller_name' => 'ModuleQuizzController',
      'types' => $types,
      'levels' => $levels,
      'videos' => $videos,
      'palliers' => $palliers,
      'pageTitle' => 'Creation Question',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
    ]);
  }
}
