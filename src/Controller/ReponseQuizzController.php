<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use App\Repository\PropositionRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\NmdPartner;
use App\Entity\NmdUserConfiguration;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;
use App\Entity\NmdCategorieProduct;


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
use App\Entity\Reponse;
use App\Entity\Video;
use App\Repository\FormationRepository;
use App\Repository\LevelRepository;
use App\Repository\NmdProductRepository;
use App\Repository\PallierRepository;
use App\Repository\TypeRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Validator\Constraints\Json;

use function PHPSTORM_META\type;

/**
 * @Route("/reponse", name="reponse_")
 */
class ReponseQuizzController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ReponseRepository $reponseRepository, Request $request): Response
    {
       $reponse_par_module_utilisateur_question = $reponseRepository->listedesreponse();
     

        return $this->render('reponse_quizz/index.html.twig', [
            'pageTitle' => 'reponse',
            'rootTemplate' => 'reponse_quizz',
            'pageIcon' => 'group',
            'rootPage' => 'lists',
            'pageColor' => 'md-bg-grey-100',
            'reponse' => $reponse_par_module_utilisateur_question
            
        ]);
    }

    /**
     * @Route("/liste/{id_module}/{id_user}", name="liste")
     */
    public function listeparmodule($id_user,$id_module,ReponseRepository $reponseRepository,PropositionRepository $propositionRepository ,QuestionRepository $questionRepository): Response
    {
        $reponse_par_module_utilisateur = $reponseRepository->reponseParModuleUtilisateurQuestion($id_module, $id_user);
        
         $toute_les_proposition = $propositionRepository->findall();
         $lesquestion = $questionRepository->findIdModule($id_module);
         $toute_les_question = $questionRepository->findall();
        $proposition_attandue = [];
        $i = 0;
         foreach ($toute_les_proposition as $proposition) {
            foreach ($reponse_par_module_utilisateur as $reponse) {

            $idprop = $proposition->getId();
            // dump($idprop);
            
            
            if (isset($reponse[$i]["proposition_id"])) {
                $idrep = $reponse[$i]["proposition_id"];
            }
                // dump($idrep);
                if (isset($idrep)) {
                    dump($idrep);
                }  
            if (isset($reponse[$i]["proposition_id"]) && $idprop  == $idrep) {
  
            if ($proposition->getIsCorrect() == 1) {
                    dump($proposition);
                $idquestion= $proposition->getIdQuestion();
                $question = $questionRepository->find($idquestion);
                $iddelaquestion = $question->getId();
                $nom_reponse_attenude = $proposition->getLibelle();
                $idprop = $proposition->getId();
                $proposition_attandue= [
                    "prop" => $idprop,
                    "ques" => $iddelaquestion,
                    "libelle" => $nom_reponse_attenude,

                ]; 
            }
        }else {
                    $proposition_attandue = [
                        "prop" => NULL,
                        "ques" => 0,
                        "libelle" => "LIBRE",

                    ]; 
            }
            $i=$i+1;
        }
    }



        return $this->render('reponse_quizz/listereponse.html.twig', [
            'pageTitle' => 'reponse',
            'rootTemplate' => 'reponse_quizz',
            'pageIcon' => 'group',
            'rootPage' => 'lists',
            'pageColor' => 'md-bg-grey-100',
            'reponse' => $reponse_par_module_utilisateur,
            'bonne_reponse' => $proposition_attandue

        ]);
    }
}