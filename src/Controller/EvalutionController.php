<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use App\Entity\Question;
use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\NmdPartner;
use App\Entity\NmdUserConfiguration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;
use App\Entity\NmdCategorieProduct;
use symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\NmdCbl;
use App\Entity\NmdObjectifClusters;
use App\Entity\NmdObjectifManagement;
use App\Entity\NmdPrNeuvesPassage;
use App\Entity\NmdProduct;
use App\Entity\NmdTrack;
use Symfony\Component\HttpFoundation\JsonResponse;


class EvalutionController extends AbstractController
{
    /**
     * @Route("/evalution", name="evalution")
     */
    public function index(Request $request, UserInterface $user): Response
    {
        $repository_user = $this->getDoctrine()->getRepository(User::class);
        $user = $this->getUser();

        $roles = $user->getRoles();

        $repository_Question  = $this->getDoctrine()->getRepository(Question::class);

        $all = $repository_Question->isall();

        $connection = mysqli_connect("localhost", "root", "", "nomad-2");

        if (!$connection) { // Contrôler la connexion
            $MessageConnexion = die("connection impossible");
        } else {
            if (isset($_POST['Bouton'])) { // Autre contrôle pour vérifier si la variable $_POST['Bouton'] est bien définie

                $reponse = $_POST['reponse'];
                var_dump($reponse);
                $id_user = $_POST['id_user'];
                $id_question = $_POST['id_question'];


                // Requête d'insertion
                $ModifCategory = "INSERT INTO reponse  (id_question_id, id_user_id,reponse)
             VALUES ('$id_question', '$id_user', '$reponse') ;";

                // Exécution de la reqête
                mysqli_query($connection, $ModifCategory) or die('Erreur SQL !' . $ModifCategory . '<br>' . mysqli_error($connection));
               
               
                return $this->redirectToRoute("evalution");
            }
        }

        return $this->render('evalution/index.html.twig', [
            'pageTitle' => 'categorie',
            'rootTemplate' => 'category',
            'pageIcon' => 'group',
            'rootPage' => 'lists',
            'pageColor' => 'md-bg-grey-100',

            'controller_name' => 'EvalutionController',
            'all'=>$all,
            'roles'=>$roles,
            'user' => $user,
        ]);
    }
}
