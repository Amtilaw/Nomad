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
use App\Entity\Question;
use App\Entity\Proposition;
use App\Entity\Video;
use App\Repository\ModuleRepository;
use App\Repository\PallierRepository;
use App\Repository\PropositionRepository;
use App\Repository\VideoRepository;
/**
 * @Route("/video", name="video_")
 */
class VideoQuizzController extends AbstractController

{
  /**
   * @Route("/AddVideo", name="AddVideo")
   */
  public function AddVideo(Request $request, $idQuestion = null, QuestionRepository $repositoryQuestion, PallierRepository $repositoryPalier): Response
  {

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
    $videos = $repositoryVideo->findAll();
    $repositoryProposition = $this->getDoctrine()->getRepository(Proposition::class);
    dump($_POST);
    dump($_FILES);




    return $this->render('module_quizz/AddVideo.html.twig', [
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
   * @Route("/listeVideo/{questionId}", name="listeVideo")
   */
  public function listeVideo(Request $request,VideoRepository $videoRepository, UserInterface $userI, QuestionRepository $questionRepository, PropositionRepository $propositionRepository, $questionId, ModuleRepository $moduleRepository): Response
  {

    $all = $videoRepository->isall();
    $question = $questionRepository->find($questionId);
    $listevideo = $videoRepository->getVideoByQuestion($questionId);
    $proposition = $propositionRepository->findAll();

    $isAll = $request->get('isAll');




    return $this->render('module_quizz/listeVideo.html.twig', [
      'pageTitle' => 'categorys',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      //data

      'isAll' => $isAll,

      'all' => $all,
      'proposition' => $proposition,
      'questionId' => $questionId,
      'question' => $question,
      'listevideo' => $listevideo,


    ]);
}
  /**
   * @Route("/listeVideos", name="listeVideos")
   */
  public function listeVideos(Request $request, VideoRepository $videoRepository, UserInterface $userI, QuestionRepository $questionRepository, PropositionRepository $propositionRepository,  ModuleRepository $moduleRepository): Response
  {

    $all = $videoRepository->isall();
    $listevideo = $videoRepository->isAll();
    $proposition = $propositionRepository->findAll();

    $isAll = $request->get('isAll');




    return $this->render('module_quizz/listeVideo.html.twig', [
      'pageTitle' => 'categorys',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',

      //data

      'isAll' => $isAll,

      'all' => $all,
      'proposition' => $proposition,
      'listevideo' => $listevideo,


    ]);
  }

  /**
   * @Route("/deleteVideo/{videoId} ", name="deleteVideo")
   */
  public function deleteVideo(Request $request, userinterface $user, $videoId): Response
  {

    $repository_question = $this->getDoctrine()->getRepository(Question::class);
    $video = $repository_question->find($videoId);

    $manager = $this->getDoctrine()->getManager();
    $manager->remove($video);
    $manager->flush();
    $message = sprintf('Video supprimé !');
    $this->addFlash('', $message);

    return $this->redirectToRoute("video_listeVideos");
  }
}
