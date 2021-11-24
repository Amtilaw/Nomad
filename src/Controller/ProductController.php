<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;
use App\Entity\NmdProduct;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\NmdProductType;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

          /**
     * @Route("/nmdProduct", name="nmdProduct")
     */
    public function nmdProduct(Request $request, UserInterface $userI): Response
    {
              $isEnabled=$request->get('isEnabled');
        if($isEnabled==null or $isEnabled==''){
            $isEnabled='1';
        }
        $sqlIsEnabled="";
        $userId = $request->get('userId');
        if($userId==null or $userId==''){
            $rolesI=$userI->getRoles();
            foreach ($rolesI as $rI) {
                $roleI = $rI;
            }
            if ($roleI=='ROLE_FINANCIAL') {
                $userId=$userI->getParent();
            }
            if ($roleI=='ROLE_COMPANY' or $roleI=='ROLE_USER') {
                $userId=$userI->getId();
            }
        }

        $now=new DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');


        $isAll=$request->get('isAll');
        if($isEnabled=='1'){
            $sqlIsEnabled=" AND is_enabled='1' ";
        }else{
            $sqlIsEnabled=" AND (is_enabled='0' OR is_enabled IS NULL) ";
        }
            $roleP='COMPANY';
                $users=[];
                $repository_product=$this->getDoctrine()->getRepository(NmdProduct::class);
                $productTest=$repository_product->allProduct();

                foreach ($productTest as $itemList){
                    $users[]=[
                        'id'=>$itemList['id'],
                        'is_enabled'=>1,
                        'lastname'=>$itemList['product'],
                        'firstname'=>$itemList['price'],
                        'email'=>$itemList['organization_naming'],
                        'phone'=>$itemList['remuneration_product_name'],
                        'role'=>'product'
                    ];
                }


        return $this->render('product/index.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'product',
            'rootPage' => 'nmdProduct',
            'pageIcon' => 'build',
            'pageColor' => 'md-bg-grey-100',

            //data
            'users'=>$users,
            'm' => $month,
            'yearChoise' => $year,
            'userId' => $userId,
            'isAll' => $isAll,
            'role'=>$roleP,
            'isEnabled'=>$isEnabled
        ]);
    }

        /**
     * @Route("/edit{productId}", name="edit")
     */
    public function edit(Request $request, UserInterface $user, $productId): Response
    {

        $repository = $this->getDoctrine()->getRepository(NmdProduct::class);
        $productInfos = $repository->find($productId);

        $form = $this->createForm(NmdProductType::class, $productInfos);

        if (!$productInfos) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
           $productInfos = $form->getData();
          $entityManager->persist($productInfos);

//          $productInfos->setProduct($_POST['product']);
//          if (strlen($_POST['price2']) > 0 ){
//          $productInfos->setPrice2($_POST['price2']);
//          }
//          $productInfos->setPrice($_POST['price']);
//          $productInfos->setOrganizationNaming($_POST['organizationNaming']);
//          $productInfos->setRemunerationProductName($_POST['remunerationProductName']);
//          $productInfos->setDescription($_POST['description']);
//          $productInfos->setOrganizationPrice($_POST['organizationPrice']);
//          $productInfos->setOrganizationCategory($_POST['organizationCategory']);
//          if (isset($_POST['isAddProduct']))
//            $productInfos->setIsAdditionalProduct(0);
//          else 
//            $productInfos->setIsAdditionalProduct(1);
          //

          $entityManager->flush();


        $now=new DateTime();
        $month=$now->format('m');
        $year=$now->format('Y');


            $roleP='COMPANY';
                $users=[];
       return $this->redirectToRoute('nmdProduct');
        }

        return $this->renderForm('product/edit.html.twig', [
            'pageTitle' => 'Production',
            'rootTemplate' => 'product',
            'pageIcon' => 'group',
            'rootPage' => 'edit',
            'pageColor' => 'md-bg-grey-100',

            'user'=>$user,
            'productInfos'=>$productInfos,
            'productIds' => $productId,
            'form' => $form,

        ]);

    }


}

