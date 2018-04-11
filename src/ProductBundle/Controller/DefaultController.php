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
    
    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $products = $product_repo->findAll();
        
        foreach ($products as $product) {
            echo $product->getId(). ' - ' . $product->getNom().' - '. $product->getDescripcio() .' - '. $product->getPreu() ."<br>";
        }
        die();
    }
    
    public function readAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $product = $product_repo->find($id);
        
        echo $product->getId(). ' - ' . $product->getNom().' - '. $product->getDescripcio() .' - '. $product->getPreu();
        die();
    }
    
    public function updateAction($id,$nom,$descripcio,$preu)
    {        
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $product = $product_repo->find($id);
        
        $product->setNom($nom);
        $product->setDescripcio($descripcio);
        $product->setPreu($preu);
        
        $em->persist($product);
        $flush = $em->flush();
        
        if($flush == null){
            echo "Actualitzat correctament";
        }
        die();
    }
}
