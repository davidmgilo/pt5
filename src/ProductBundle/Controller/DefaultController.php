<?php

namespace ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ProductBundle\Form\ProducteType;
use ProductBundle\Entity\Producte;
use Symfony\Component\HttpFoundation\Request;

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
        
//        foreach ($products as $product) {
//            echo $product->getId(). ' - ' . $product->getNom().' - '. $product->getDescripcio() .' - '. $product->getPreu() ."<br>";
//        }
//        die();
        return $this->render('ProductBundle:Default:list.html.twig', array(
            "products"=> $products
        ));
    }
    
    public function readAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $product = $product_repo->find($id);
        
        if($product == null){
            echo "Producte inexistent";
            die();
        }
        
        echo $product->getId(). ' - ' . $product->getNom().' - '. $product->getDescripcio() .' - '. $product->getPreu();
        die();
    }
    
    public function updateAction($id,$nom,$descripcio,$preu)
    {        
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $product = $product_repo->find($id);
        
        if($product == null){
            echo "Producte inexistent";
            die();
        }
        
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
    
    public function deleteAction($id){
         
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $product = $product_repo->find($id);
        
        if($product == null){
            echo "Producte inexistent";
            die();
        }
        
        $em->remove($product);
        $flush = $em->flush();
               
        if($flush == null){
           echo "Esborrat correctament";
        }
        die();
    }
     
    public function dqlAction($preu1,$preu2)
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $products = $product_repo->preuEntre($preu1,$preu2);
        
        foreach ($products as $product) {
            echo $product['id']. ' - '.$product['nom']. ' - '.$product['descripcio']. ' - '.$product['preu']."<br>";
        }
        die();
        
        //return $this->render('AnimalBundle:Default:index.html.twig');
    }
    
    public function igualAction($preu){
        $em = $this->getDoctrine()->getEntityManager();
        
        $product_repo = $em->getRepository("ProductBundle:Producte");
        $products = $product_repo->findBy(array('preu' => $preu));
        
        foreach ($products as $product) {
            echo $product->getId(). ' - ' . $product->getNom().' - '. $product->getDescripcio() .' - '. $product->getPreu() ."<br>";
        }
        die();
    }
    
    public function sqlAction($preu, $lletres)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
        
        $sql = "SELECT * FROM productes WHERE preu=".$preu." AND nom LIKE '".$lletres."%'";
        
        $stmt = $db->prepare($sql);
        $params = array();
        $stmt->execute($params);
        
        $products = $stmt->fetchAll();
        
        foreach ($products as $product) {
            echo $product['id']. ' - '.$product['nom']. ' - '.$product['descripcio']. ' - '.$product['preu']."<br>";
        }
        die();
        
    }
    
    public function formAction(Request $request){
        $product = new Producte();
        $form = $this->createForm(ProducteType::class,$product);
        
        $form->handleRequest($request);
       if($form->isValid()){
            $status = "Formulari OK";
            $data = array(
              'nom' => $form->get("nom")->getData(),  
              'descripcio' => $form->get("descripcio")->getData(),  
              'preu' => $form->get("preu")->getData(),  
              'esBeguda' => $form->get("esBeguda")->getData()  
            );
            
            $product->setNom($data['nom']);
            $product->setDescripcio($data['descripcio']);
            $product->setPreu($data['preu']);
            $product->setEsBeguda($data['esBeguda']);
        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($product);
            $em->flush();
            
            // return $this->redirectToRoute('replace_with_some_route');
            
        } else{
            $status = null;
            $data = null;
       }
        
        return $this->render('ProductBundle:Default:form.html.twig', array(
            'form' => $form->createView(),
            'status' => $status,
            'data' => $data
        ));
    }
}
