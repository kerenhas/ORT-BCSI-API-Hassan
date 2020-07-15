<?php
namespace App\Api ;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\TransportExeptionInterface;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
class formationApi
{
    public function __construct()
    {
       
    }

   
   /**
    * fonction qui va chercher la formation dans les poles passes en parametres
    * @return le bon fichier 
    */
    public function getFormation($id, $poles)
    {
        $path = dirname(__DIR__, 2);
         // on parcours tous les repos
        foreach ($poles as $form)
        {
            // si c'est e bon fichier json on le retourne
            if(file_exists($path."/var/formations/".$form."/".$id.".json"))
            {
                return file_get_contents($path."/var/formations/".$form."/".$id.".json");
            }
        }

    }

       // fonction qui renvoit les erreurs au format json
        public function throwException($type)
        {
            return json_encode([
                'Event' => 'api/view',
                'Error' => 'pvf',
                'Type' => $type
            ]);
        }
}