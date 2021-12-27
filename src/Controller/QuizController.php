<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\Pallier;
use App\Entity\Proposition;
use App\Entity\Reponse;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\PropositionRepository;
use App\Repository\QuestionRepository;
use App\Repository\VideoRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class QuizController extends AbstractController
{
  /**
   * @Route("/quiz/index/{idVideo}/{idModule}", name="quiz")
   */
  public function index($idVideo, $idModule,QuestionRepository $questionRepository,VideoRepository $videoRepository,
  PropositionRepository $propositionRepository): Response
  {
   
    $questions = $questionRepository->questionByVideoAndPallier($idVideo, $idModule);
    dump($questions);
    $videoName = $videoRepository->getVideoName($idVideo);
    $arrayResponse = [];

    for ($i = 0; $i < count($questions); $i++) {
      
      $arrayResponseProposition = [];
      $proposition = $propositionRepository->propositionParQuestion($questions[$i]["id"]);
      for ($j = 0; $j < count($proposition); $j++) {
        $arrayResponseProposition[$j] = [
          "id" => $proposition[$j]["id"],
          "libelle" => $proposition[$j]["libelle"],
          "isCorrect" => $proposition[$j]["is_correct"],
        ];
      }
      if (count($proposition) >= 1) {
        $arrayResponse[count($arrayResponse)] = [
          "id" => $questions[$i]["id"],
          "libelle" => $questions[$i]["questionLibelle"],
          "timecode" => $questions[$i]["timecode"],
          "titrePallier" => $questions[$i]["titre_groupe_question"],
          "type" => $questions[$i]["tipe"],
          "propositions" => $arrayResponseProposition,
        ];
      }else {
        $arrayResponse[count($arrayResponse)] = [
          "id" => $questions[$i]["id"],
          "libelle" => $questions[$i]["questionLibelle"],
          "timecode" => $questions[$i]["timecode"],
          "titrePallier" => $questions[$i]["titre_groupe_question"],
          "type" => $questions[$i]["tipe"],
        ];
      }
    }

    // foreach ($questions as $question) {
      
    //   // pour chaque question retournée => on vérifie si il y a des propositions liées
    //   $questionId = $question['id'];
    //   $propositions = $propositionRepository->propositionParQuestion($questionId);

    //   // SWITCH CASE TYPE 

    //   // vérification si type = 1 AND not empty
    //   if (!empty($propositions)) {

    //     $arrayResponseProposition = [];
        
    //     foreach ($propositions as $proposition) {

    //       array_push($arrayResponseProposition, $proposition);
    //       $arrayResponse[$questionId] = [
          
    //         "questionId" => $question["id"],
    //         "libelle" => $question["questionLibelle"],
    //         "timecode" => $question["timecode"],
    //         "titrePallier" => $question["titre_groupe_question"],
    //         "type" => $question["tipe"],
    //         "propositions" => $arrayResponseProposition

    //       ];
          
    //     }

    //   }

    // }
    $json = json_encode($arrayResponse);


    return $this->render('quiz/index.html.twig', [
      'pageTitle' => 'Quiz auto-formation',
      'rootTemplate' => 'quiz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
      'questions' => $questions,
      'json' => $json,
      "videoName" => $videoName[0]["url"],
    ]);
  }

  /**
   * @Route("/userRespond", name="userRespond")
   */
 
  public function userRespond(Request $request, UserInterface $userI)
  {
    $userId = $request->get("userId");
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
    $entityManager = $this->getDoctrine()->getManager();
    for ($i = 0; $i < count($request->request->get("answerId")); $i++) {
      $reponse = new Reponse();
      $reponse->setQuestionId($request->request->get("question_id"));
      $idprop = $request->request->get("answerId")[$i];
      $reponse->setPropositionId($idprop);
      $reponse->setPropositionValue($request->request->get("answer")[$i]);
      // log($request->request->get("realAnswer")[$i]);
      $answer = $request->request->get("answer")[$i];
      dump(array('$answer'));
      $reponse->setAnswer($request->request->get("answer")[$i]);
      $reponse->setUserId($userId);
      $dateTime = new \DateTime();
      $dateTime->format('Y-m-d H:i:s');
      $reponse->setCreatedAt($dateTime);
      if ($request->request->get("answer")[$i] == $request->request->get("realAnswer")[$i]["isCorrect"])
      $reponse->setResultIntermediaire(1);
      else
        $reponse->setResultIntermediaire(0);
      $entityManager->persist($reponse);
      $entityManager->flush();
    }
    return new JsonResponse(['sucess' => 'yes']);
  }
  



/**
 * @Route("/enregistreRespond", name="enregistreRespond")
 */
  public function enregistreRespond(Request $request, UserInterface $userI)
  {
    $userId = $request->get("userId");
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
    $entityManager = $this->getDoctrine()->getManager();
  
    
      $reponse = new Reponse();
      $reponse->setQuestionId($request->request->get("question_id"));
      $reponse->setUserId($userId);
      $dateTime = new \DateTime();
      $dateTime->format('Y-m-d H:i:s');
      $reponse->setCreatedAt($dateTime);
      $reponseDonne = $request->request->get("reponseDonne");
        $reponse->setAnswer($reponseDonne);
      $entityManager->persist($reponse);
      $entityManager->flush();

    
    return new JsonResponse(['sucess' => 'yes']);
  }
}