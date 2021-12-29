<?php

namespace App\Controller;

use App\Repository\QuestionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\NmdCategorieProduct;

use App\Entity\Type;
use App\Entity\Proposition;
use App\Repository\ModuleRepository;
use App\Repository\PallierRepository;
use App\Repository\PropositionRepository;

/**
 * @Route("/Proposition", name="Proposition_")
 */
class PropositionQuizzController extends AbstractController

{

  /**
   * @Route("/createProposition/{questionId}", name="createProposition")
   */
  public function createProposition(Request $request, $questionId ,QuestionRepository $repositoryQuestion): Response
  {

    // enregistrement des proposition
    if (isset($_POST['libelleProps'])) {
      if (isset($_POST['submit'])) {
        $message = sprintf('Creation proposition abandonnée');
        $this->addFlash('', $message);
        return $this->redirectToRoute("Formation_formations");
      }

        if (isset($_POST['libelleProps'])) {
          $proposition = new Proposition();

          $id_question = $questionId;
          $proposition->setLibelle($_POST['libelleProps']);
          $proposition->setIdQuestion($repositoryQuestion->find($id_question));
          if (isset($_POST['iscorrect'])) {
          $proposition->setIsCorrect($_POST['iscorrect']);
          }
          
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($proposition);
          $entityManager->flush();
      }
      return $this->redirectToRoute('Proposition_Proposition', ['questionId' => $id_question]);
    }

    return $this->render('module_quizz/creationProposition.html.twig', [
      'controller_name' => 'PropositionQuizzController',
      'pageTitle' => 'Creation de proposition',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
      'questionId' => $questionId,
    ]);
  }

  /**
   * @Route("/listeProposition/{questionId}", name="Proposition")
   */
  public function listeProposition(Request $request, UserInterface $userI, QuestionRepository $questionRepository, PropositionRepository $propositionRepository, $questionId, ModuleRepository $moduleRepository): Response
  {

    $questionId = $questionId;
    $question = $questionRepository->find($questionId);
    $idModule = $questionRepository->findIdModule($questionId);
    $proposition = $propositionRepository->AllProposition();

    return $this->render('module_quizz/listeProposition.html.twig', [
      'pageTitle' => 'Liste des propositions',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      //data

      'proposition' => $proposition,
      'questionId' => $questionId,
      'question' => $question,
      'idModule' => $idModule[0]['id_module_id'],

    ]);
  }

  /**
   * @Route("/editProposition/{PropositionId}", name="editProposition")
   */
  public function editProposition(Request $request, userinterface $user, $PropositionId, PropositionRepository $propositionRepository, PallierRepository $RepositoryPallier, QuestionRepository $RepositoryQuestion): Response
  {
    $categoryInfos = $propositionRepository->find($PropositionId);


    $QuestionId = $propositionRepository->findIdQuestion($PropositionId);
    $QuestionId = $QuestionId[0]['id_question_id'];

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

        $proposition->setLibelle($_POST['libelleProps']);

        $proposition->setIsCorrect($_POST['iscorrect']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($proposition);
        $entityManager->flush();

        return $this->redirectToRoute('Proposition_Proposition', ['questionId' => $QuestionId]);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($proposition);
        $entityManager->flush();
        return $this->redirectToRoute('Proposition_Proposition', ['questionId' => $QuestionId]);
      }
    }


    if (isset($_POST['annuler'])) {
      return $this->redirectToRoute('Proposition_Proposition', ['questionId' => $QuestionId]);
    }


    return $this->render('module_quizz/editProposition.html.twig', [
      'pageTitle' => 'Modification des propositions',
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
  public function deleteProposition(Request $request, PropositionRepository $propositionRepository, userinterface $user, $PropositionId): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $repository_category = $this->getDoctrine()->getRepository(NmdCategorieProduct::class);
    $QuestionId = $propositionRepository->findIdQuestion($PropositionId);
    $QuestionId = $QuestionId[0]['id_question_id'];
    $categoryInfos = $repository_category->find($PropositionId);
    // suppresion de la proposition 
    $proposition = $propositionRepository ->find($PropositionId);
    $entityManager->remove($proposition);
    $entityManager->flush();
    $message = sprintf('Proposition supprime');
    $this->addFlash('', $message);


    return $this->redirectToRoute('Proposition_Proposition', ['questionId' => $QuestionId]);
  }


}
