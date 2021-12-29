<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Formation;
use App\Entity\Level;
use App\Repository\ModuleRepository;

/**
 * @Route("/Formation", name="Formation_")
 */
class FormationQuizzController extends AbstractController

{
  /**
   * @Route("/createFormation", name="createFormation")
   */
  public function index(Request $request): Response
  {

    if ($request->get('submit') == 'annuler') {
      $message = sprintf('Création de formation abandonnée');
      $this->addFlash('', $message);
      return $this->redirectToRoute('Formation_formations');
    }

    if ($request->get('submit') == 'valider') {
      $entityManager = $this->getDoctrine()->getManager();
      $formation = new Formation();

      $formation->setNom($_POST['formationName']);

      $entityManager->persist($formation);

      $entityManager->flush();

      $message = sprintf('Formation créée');
      $this->addFlash('notice', $message);
      return $this->redirectToRoute('Formation_formations');
    }

    return $this->renderForm('module_quizz/index.html.twig', [
      'controller_name' => 'FormationQuizzController',
      'pageTitle' => 'Création de Formation',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'lists',
      'pageColor' => 'md-bg-grey-100',
    ]);
  }
  /**
   * @Route("/formations", name="formations")
   */
  public function displayFormations(Request $request,ModuleRepository $ModuleRepository): Response
  {
    $repository = $this->getDoctrine()->getRepository(Formation::class);
    $allFormations = $repository->findBy(array(), array('id' => 'ASC'));

    $formations = [];
    foreach ($allFormations as $formation) {
      $allModules = $formation->getModules();
      $modules = [];
      foreach ($allModules as $module) {
        $level = $this->getDoctrine()->getRepository(Level::class)->find($module->getIdLvl());
        $allVideo = $ModuleRepository->allVideos($module->getId());
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
   * @Route("/deleteFormation/{formationId}", name="deleteFormation")
   */
  public function deleteFormation(Request $request, userinterface $user, $formationId): Response
  {

    $repository_formation = $this->getDoctrine()->getRepository(formation::class);
    $formation = $repository_formation->find($formationId);

    $manager = $this->getDoctrine()->getManager();
    $manager->remove($formation);
    $manager->flush();
    $message = sprintf('Formation supprimée !');
    $this->addFlash('', $message);

    return $this->redirectToRoute("Formation_formations");
  }

 

}
