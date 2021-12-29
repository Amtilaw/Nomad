<?php

namespace App\Controller;

use App\Repository\QuestionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Formation;
use App\Entity\Module;
use App\Entity\Level;

/**
 * @Route("/module", name="module_")
 */
class ModuleQuizzController extends AbstractController

{
  /**
   * @Route("/module/{id_formation}", name="module")
   */
  public function module(int $id_formation = 1, Request $request): Response
  {

    if ($request->get('submit') == 'annuler') {
      $message = sprintf('Création de module abandonnée');
      $this->addFlash('', $message);
      return $this->redirectToRoute('Formation_formations');
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
      return $this->redirectToRoute('Formation_formations');
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
   * @Route("/delete/{categoryId}", name="delete")
   */
  public function delete(Request $request, userinterface $user, $categoryId, QuestionRepository $questionRepository): Response
  {

    $moduleId = $questionRepository->findIdModule($categoryId);
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
      return $this->redirectToRoute('Question_listequestion', ['moduleId' => $moduleId]);
    }
  }


}
