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
                // on lit le fichier json qui est un tableau 
                // dans ce tableau y'a le resultat qui est aussi un tableau avec toutes les informations
                // ici on a donc recuperer dans resultat un tableau avec l'ensemble des formations
                $this->resultat = $json['results'][0];
               
                }else
                {  
                   return $this->throwException("1");
                }
    
            }
             catch (TransportExeptionInterface $e) {
                $this->error = $e->getMessage();
            }
    }

    public function getResults()
    {
         return $this->resultat;
    }


    //on doit faire des repertoires poles  pour ca on va d'abord tous les recuperer
    //on parcours donc rsultat( qui est le tableau des formations ) pour mettre chq formations dans le tableau pole
    
    public function getPole()
    {
        $tblPole = array();
        foreach($this->resultat as $form => $key)
        {
            $tblPole[]= $form;
        }

        return $tblPole;
    }

    //fonction qui creer un tableau a chq formation d'un pole
    public function createTabFormPole($pole)
    {
        $tblForm= $this->getResults()[$pole];  
        $tblDetail= array();
        foreach($tblForm as $param => $key)
        {
            $tblDetail[]= $param;
        }
       return $tblDetail;
    }


        // fonction qui permet d'ecrire un fichier json par formation d'un pole
    public function writeFile($pole)
    {
        // on recuperer toutes les formations pour chaque pole
        $tblForm =$this-> createTabFormPole($pole);
        // chemin pour le fichier
        $path = dirname(__DIR__, 2);
        $nbWriteFile=0; 

        // maintenant on va creer un fichier pour chaque pole
        foreach($tblForm as $param => $key)
        {
            // on recupere le contenu
           $tblDetail= $this->getResults()[$pole];  
           $result = json_encode($tblDetail[$key]);      
           // pour un affichage plus propre
           $text = json_decode($result);
         
          //creation d'un fchier     
         file_put_contents($path."/var/formations/".$pole."/".$key.".json",  "<pre>".print_r($text, true)."</pre>" );
            $nbWriteFile++;
        }
        // le nombre de fichier creer 
        return $nbWriteFile;
    }

    //on doit creer tous les fichiers pour tous les poles
    public function createFile()
    {
            $nbRepoForm=0;         
            $nbWriteFile=0;
            // on parcour tous les poles 
            foreach($this->resultat as $form => $key)
            {        
               $nbWriteFile +=$this->writeFile($form);
               $nbRepoForm++;
            }  
            // creer un tableau avec le nombre de pole et le nombre de fichier creer 
            $nb=array('nbWriteFile' => $nbWriteFile, "nbRepoForm" => $nbRepoForm );
            // aff5chage du tableau avec les nombres en json
             return json_encode($nb);
    }

   public function getError()
   {
      return $this->error;
   }

   // fonction qui renvoit les erreurs au format json
   public function throwException($type)
   {
    return json_encode([
        'Event' => 'api/load',
        'Error' => 'pvf',
        'Type' => 'type'
       ]);
   }

}
    
?>
