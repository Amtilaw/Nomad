<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/example", name="example_")
*/
class ExampleController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('example/index.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'example',
            'rootPage' => 'index',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100'
        ]);
    }

    /**
     * @Route("/exempleUn", name="exempleUn")
     */
    public function exempleUn(): Response
    {
        return $this->render('example/exempleUn.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'example',
            'rootPage' => 'exempleUn',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100'
        ]);
    }

    /**
     * @Route("/exempleDeux", name="exempleDeux")
     */
    public function exempleDeux(): Response
    {
        return $this->render('example/exempleDeux.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'example',
            'rootPage' => 'exempleDeux',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100'
        ]);
    }

    /**
     * @Route("/exempleRenderView", name="exempleRenderView")
     */
    public function exempleRenderView(Request $request)
    {

        $arrayResponse = [];

        // Après une requete par exemple
        array_push( $arrayResponse, 'hello world');

        return new JsonResponse([
            'content' => $this->renderView('example/component/partials/index/renderView_edit_something.html.twig', [
                'arrayResponse' => $arrayResponse,
            ])
        ]);
        
    }

    /**
     * @Route("/exempleRenderViewDeux", name="exempleRenderViewDeux")
     */
    public function exempleRenderViewDeux(Request $request)
    {

        $arrayResponse = [];

        // Après une requete par exemple
        array_push( $arrayResponse, 'hello world');

        return new JsonResponse([
            'content' => $this->renderView('example/component/partials/index/renderView_add_something.html.twig', [
                'arrayResponse' => $arrayResponse,
            ])
        ]);
        
    }

}
