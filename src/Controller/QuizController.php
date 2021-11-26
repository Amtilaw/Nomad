<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz/index", name="quiz")
     */
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig', [
            'pageTitle' => 'Quiz auto-formation',
            'rootTemplate' => 'production',
            'pageIcon' => 'group',
            'rootPage' => 'lists',
            'pageColor' => 'md-bg-grey-100',
        ]);
    }
}
