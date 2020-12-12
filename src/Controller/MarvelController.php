<?php


namespace App\Controller;


use App\Manager\MarvelManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MarvelController extends AbstractController
{

    /**
     * @Route("/character/{id}/comics", methods={"GET"})
     * @param Request $request
     * @param string $id
     * @param MarvelManager $marvelManager
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCharacterComicsAction(Request $request, string $id, MarvelManager $marvelManager): Response
    {
        $offset = $request->query->get("offset") ?? 0;
        $limit = $request->query->get("limit") ?? 1;
        $character = $marvelManager->getCharacterComics($id, $offset, $limit);
        return new Response(json_encode($character));
    }

    /**
     * @Route("/character/{id}", methods={"GET"})
     * @param string $id
     * @param MarvelManager $marvelManager
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCharacterAction(string $id, MarvelManager $marvelManager): Response
    {
        $character = $marvelManager->getCharacter($id);
        return new Response(json_encode($character));
    }

    /**
     * @Route("/characters", methods={"GET"})
     * @param Request $request
     * @param MarvelManager $marvelManager
     * @return Response
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getCharactersAction(Request $request, MarvelManager $marvelManager): Response
    {
        $offset = $request->query->get("offset") ?? 0;
        $limit = $request->query->get("limit") ?? 1;
        $characters = $marvelManager->getCharacters($offset, $limit);
        return new Response(json_encode($characters));
    }
}
