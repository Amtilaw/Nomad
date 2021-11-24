<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("", name="home_")
*/
class HomeController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        // dump($this->get('session'));

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
