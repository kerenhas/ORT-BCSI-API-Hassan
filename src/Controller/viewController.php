<?php

namespace App\Controller;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\TransportExeptionInterface;
use App\Api\ortApi;
use App\Api\formationApi;
use App\Api\cacheApi;

class viewController extends AbstractController

{

    /**
     * @Route("/",name="accueil")
     * @return Reponse
     */
    function index()
    {
        return new Response("Bienvenue dans la page d'accueil");
    }


    /**
     * @Route("/load",name="projet_api")
     * @return Reponse
     */
    public function loadApiOrt()
    {
        $json = array();

        // on met notre api dans laquelle on peut la lire 
        $api = new ortApi();
        // dans notre json on aura notre resultat, ici ce sera l'ensemble des formations
        if ($api->getError() != null) {
            $json = $api->getResults();
        }

        // retoune un tableau avec chq poles
        $poles = $api->getPole();

        $cache = new cacheApi();
        // on creer un dossier pour chaque formation
        try {
            $cache->createRepo($poles);
        } catch (IOExceptionInterface $exception) {
            return new Response($api->throwException("2"));
        }

        try {
            //creation des fihiers json par formation
            return new Response($api->createFile());
        } catch (IOExceptionInterface $exception) {
            return new Response($api->throwException("3"));
        }
    }

    /**
     * @Route("/view/{id}", name="viewFormation")
     */
    public function viewFormation($id)
    {
        //Lecture de l’ensemble des fiches formations dans le répertoire var/formations/* 
        $formation = new formationApi();

        // on appelle la classe API pour la methode getPole
        $api = new ortApi();

        // retoune un tableau avec chq formations
        $poles = $api->getPole();

        if (!empty($formation->getFormation($id, $poles))) {
            // print "<font color='red'>Les détails de la formation $id : </font>";
            return new Response($formation->getFormation($id, $poles));
        } else {
            return new Response($formation->throwException("4"));
        }
    }
}
