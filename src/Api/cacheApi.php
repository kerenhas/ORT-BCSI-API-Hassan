<?php
namespace App\Api ;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\TransportExeptionInterface;

class cacheApi
{
    public function __construct()
    {
       
    }

    // dans cette classe on va devoir recuperer nos poles et creer des repertoire
    public function createRepo($poles)
    {
        // donc d'abord on va essayer de trouver ds quel dossier on se trouve 
        // notre chemin 
        $path = dirname(__DIR__, 2);

        //la on creer notre dossier ou y'aura tous nos dossier pour chaque pole
       // on fais une condition pour savoir si le dossier n'existe pas deja
       if(!file_exists($path."/var/cacheApi"))
       {
        mkdir($path."/var/cacheApi");
       }
       // maintenant on va creer pour chaque pole un repo
       foreach($poles as $pole)
       {
            if(!file_exists($path."/var/cacheApi/".$pole))
            {
            mkdir($path."/var/cacheApi/".$pole);
            }
           
       }
        
    }

}

?>