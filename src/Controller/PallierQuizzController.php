<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Question;
use App\Entity\Pallier;
use App\Repository\PallierRepository;

/**
 * @Route("/Pallier", name="Pallier_")
 */
class PallierQuizzController extends AbstractController

{
 
  /**
   * @Route("/listePalliers/{moduleId}", name="listePalliers")
   */
  public function listePalliers(Request $request, userinterface $user, $moduleId, PallierRepository $pallierRepository): Response
  {
    $palliers = $pallierRepository->findAllByModule($moduleId);

    return $this->render('module_quizz/listePalliers.html.twig', [
      'pageTitle' => 'liste des palliers',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'edit',
      'pageColor' => 'md-bg-grey-100',

      'user' => $user,
      'palliers' => $palliers,
      'moduleId' => $moduleId,

    ]);
  }

  /**
   * @Route("/editPallier/{pallierId}", name="editPallier")
   */
  public function editPallier(Request $request, userinterface $user, $pallierId): Response
  {

    $entityManager = $this->getDoctrine()->getManager();
    $palliers = $this->getDoctrine()->getRepository(Pallier::class);
    $pallier = $palliers->find($pallierId);

    $moduleId = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['id_pallier' => $pallierId])->getIdModule()->getId();

    if (isset($_POST['valider'])) {

        $pallier->setTimecode($_POST['pallierTimecode']);
        $pallier->setTitreGroupeQuestion($_POST['pallierTitreGroupeQuestion']);
        $pallier->setDescription($_POST['pallierDescription']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pallier);
        $entityManager->flush();

        return $this->redirectToRoute('Pallier_listePalliers', ['moduleId' => $moduleId]);
      }


    if (isset($_POST['annuler'])) {
      return $this->redirectToRoute('Pallier_listePalliers', ['moduleId' => $moduleId]);
    }

    return $this->render('module_quizz/editPallier.html.twig', [
      'pageTitle' => 'modification des palliers',
      'rootTemplate' => 'module_quizz',
      'pageIcon' => 'group',
      'rootPage' => 'edit',
      'pageColor' => 'md-bg-grey-100',

      'user' => $user,
      'pallier' => $pallier,

    ]);
  }

  /**
   * @Route("/createPallier/{idModule}", name="createPallier")
   */
  public function createPallier(Request $request, userinterface $user, $idModule): Response
  {

    if (empty($_POST["timecode"])) {

      return $this->render('module_quizz/createPallier.html.twig', [
        'controller_name' => 'CreatePallier',
        'pageTitle' => 'Creation du pallier',
        'rootTemplate' => 'module_quizz',
        'pageIcon' => 'group',
        'rootPage' => 'lists',
        'pageColor' => 'md-bg-grey-100',
        'idModule' => $idModule,
      ]);
    }

    //create Pallier

    $pallier = new Pallier();

    $pallier->setTimecode($_POST["timecode"]);
    $pallier->setTitreGroupeQuestion($_POST["titrePallier"]);
    $pallier->setDescription($_POST["description"]);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($pallier);
    $entityManager->flush();

    return $this->redirectToRoute("Pallier_listePalliers", ['moduleId' => $idModule]);
  }

  /**
   * @Route("/deletePallier/{idPallier}", name="deletePallier")
   */
  public function deletePallier(Request $request, userinterface $user, $idPallier, PallierRepository $pallierRepository): Response
  {

    $this->$pallierRepository->updatePallier($idPallier);

    $repository_pallier = $this->getDoctrine()->getRepository(Pallier::class);
    $pallier = $repository_pallier->find($idPallier);

    $manager = $this->getDoctrine()->getManager();
    $manager->remove($pallier);
    $manager->flush();
    $message = sprintf('Pallier supprimé !');
    $this->addFlash('', $message);

    return $this->redirectToRoute("Formation_formations");
  }

}
