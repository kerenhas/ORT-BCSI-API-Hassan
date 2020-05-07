<?php 

namespace App\Controller;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\TransportExeptionInterface;
use App\Api\ortApi;
use App\Api\cacheApi;

class viewController extends AbstractController

{

/**
* @Route("/view",name="projet_view")
* @return Reponse
*/
public function view()
{
    return new Response('Bienvenue dans la page home ');                   
        
}

/**
* @Route("/",name="projet_api")
* @return Reponse
*/
public function api()
{
    $json = array();
    // on met notre api dans laquelle on peut lire 
   $api = new ortApi();

   // dans notre json on aura notre resultat, ici ce era l'ensemble des formations
  if($api->getError() !=null)
  {
    $json = $api->getResults();
  }
  
   $poles=$api->getPole();

   $cache = new cacheApi();
   $cache->createRepo($poles);

   //Ici on recupere les formations pour chq pole
   $form = $api->getFormation("MODE");
   
   return $this->render('base.html.twig',[
       'reponse' =>$json,
       'erreur' =>$api->getError()
   ]);
}

}

 ?>