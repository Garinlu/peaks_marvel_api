<?php


namespace App\Controller;


use App\Manager\MarvelManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MarvelController extends AbstractController
{

    /**
     * @Route("/characters", methods={"GET"})
     * @param MarvelManager $marvelManager
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function getCharactersAction(MarvelManager $marvelManager): Response
    {
        $characters = $marvelManager->getCharacters(1, 1);
        return new Response(json_encode($characters));
    }
}
