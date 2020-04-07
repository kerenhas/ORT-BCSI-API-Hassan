<?php 

namespace App\Controller;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class viewController extends AbstractController

{
/**
* @Route("/view",name="projet_view")

*/
public function view()
{

            $clientCurl = new CurlHttpClient();
            $response=$clientCurl->request("POST", "http://concoursphoto.ort-france.fr/api/matrice",
                        ['headers' => ['Content-Type' => 'application/json'],'body' => '{}']);

                        $Content=$response->getContent();
            dd($Content);
            return new Response('ORT-CSI TP API ');
                    
        
}
}

 ?>