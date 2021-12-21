<?php

namespace App\Controller;

use App\Repository\QuestionRepository;

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
use App\Entity\Video;
use App\Repository\ModuleRepository;
use App\Repository\NmdProductRepository;
use App\Repository\PallierRepository;
use App\Repository\PropositionRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Name;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/upload", name="upload_")
 */

class upload extends AbstractController
{


    /**
     * @Route("/video", name="video")
     */
    public function video(Request $request)
    {
        

        if (isset($_POST['submit'])) {
            $message = sprintf('Création de question abandonnée');
            $this->addFlash('', $message);
            return $this->redirectToRoute('module_formations');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $Video = new Video();
        
        if (empty($_POST['URL']) && $_POST['URL'] != "") {
            $Video->setUrl($_POST['URL']);

            $entityManager->persist($Video);
            $entityManager->flush();

            return $this->redirectToRoute('module_listeVideos');
        }
        dump($_POST);
        dump($_FILES['fileToUpload']);
        $videoname = $_FILES['fileToUpload']["name"];

                   
        
$target_dir = "C:/Users/nolan/Nomad-2/public/assets/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$videoFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if file already exists
if (file_exists($target_file)) {
            $message = sprintf("Sorry, file already exists.");
            $this->addFlash('', $message);
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000000) {
            $message = sprintf("Sorry, your file is too large.");
            $this->addFlash('', $message);
  $uploadOk = 0;
}

// Allow certain file formats
if($videoFileType != "mp4" ) {
            $message = sprintf("Sorry, only mp4 files are allowed.");
            $this->addFlash('', $message);
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
            $message = sprintf("Sorry, your file was not uploaded.");
            $this->addFlash('', $message);
// if everything is ok, try to upload file
} else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $message = sprintf("The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.");
                $this->addFlash('', $message);
            } else {
                $message = sprintf("Sorry, there was an error uploading your file.");
                $this->addFlash('', $message);
                $Video->setUrl($videoname);
                $entityManager->persist($Video);
                $entityManager->flush();
            }

            $message = sprintf("The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.");
            $this->addFlash('', $message);
  
    }

        return $this->redirectToRoute('module_listeVideos');
}
}
