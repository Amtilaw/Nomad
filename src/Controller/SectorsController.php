<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/sectors", name="sectors_")
*/
class SectorsController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('sectors/index.html.twig', [
            'pageTitle' => 'Secteurs',
            'rootTemplate' => 'sectors',
            'pageIcon' => 'tab_unselected',
            'pageColor' => 'md-bg-grey-100'
        ]);
    }
}
