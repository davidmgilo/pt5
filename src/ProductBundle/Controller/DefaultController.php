<?php

namespace ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProductBundle:Default:index.html.twig');
    }
    
    public function createAction($nom,$descripcio,$preu)
    {
        $product = new \ProductBundle\Entity\Producte;
        $product->setNom($nom);
        $product->setDescripcio($descripcio);
        $product->setPreu($preu);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($product);
        $flush = $em->flush();
        
        if($flush == null){
            echo "Creat correctament";
        }
        die();
    }
}
