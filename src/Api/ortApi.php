<?php
namespace App\Api ;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\TransportExeptionInterface;

class ortApi
{
   public $resultat = array();
   public $error;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $clientCurl = new CurlHttpClient();
        $json = array();
        
        try {
            $response=$clientCurl->request("POST", "http://concoursphoto.ort-france.fr/api/matrice",
            ['headers' => ['Content-Type' => 'application/json'],'body' => '{}']);
    
            $Content=$response->getContent();
    
            if($response->getStatusCode() == 201)
            {
             //mettre la response dans un tableau
              $json = $response->toArray();
              // on lit ke fichier json qui est un tableau 
              // dans ce tableau y'a le resultat qui est aussi un tableau avec toutes les informations
              // ici on a donc recuperer dans resultat un tableau avec l'ensemble des formations
              $this->resultat = $json['results'][0];
           //  dd($resultat); 
            }else
            {
                $this->error = "erreur https";
            }
    
            } catch (TransportExeptionInterface $e) {
                $this->error = $e->getMessage();
            }
    }

    public function getResults()
    {
         return $this->resultat;
    }
    //on doit faire des repertoires poles  pour ca on va d'abord tous les recuper
    public function getPole()
    {
        $tblPole = array();
        foreach($this->resultat as $form => $key)
        {
            $tblPole[]= $form;
        }

        return $tblPole;
    }

    //on retourne les informations pour chq pole 
    public function getFormation($pole)
    {
        $tblForm= $this->getResults()[$pole];
        //une fois qu'on a recupere on va les mettre dans le fichier 
        foreach($tblForm as $param => $key)
        {
            //on creer un fichier si y'en a pas deja un 
            $path = dirname(__DIR__, 2);

           // maintenant on va creer pour chaque param un repo
                if(!file_exists($path."/var/cacheApi/".$pole."/".$param))
                {
                mkdir($path."/var/cacheApi/".$pole."/".$param);
                }

        }
        return $tblForm;
    }

   public function getError()
   {
      return $this->error;
   }
}

?>