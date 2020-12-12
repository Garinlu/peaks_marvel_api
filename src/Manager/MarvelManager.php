<?php


namespace App\Manager;


use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MarvelManager
{
    private static $MARVEL_ENDPOINT = "https://gateway.marvel.com/v1/public";
    /** @var HttpClientInterface */
    private $http;

    public function __construct()
    {
        $this->http = HttpClient::create();
    }

    /**
     * Get detail about a character
     * @param string $id
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCharacterComics(string $id, int $offset, int $limit): array
    {
        try {
            $request = $this->request("GET", "/characters/" . $id . "/comics",
                [
                    "orderBy" => "onsaleDate",
                    "offset" => $offset,
                    "limit" => $limit,
                ]);
            return array_map(function ($comic) {
                return $comic["title"];
            }, $request->toArray()["data"]["results"]);
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * Get detail about a character
     * @param string $id
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCharacter(string $id): array
    {
        try {
            $request = $this->request("GET", "/characters/" . $id);
            $characterFull = $request->toArray()["data"]["results"][0];
            return [
                "id" => $characterFull["id"],
                "name" => $characterFull["name"],
                "description" => $characterFull["description"],
                "thumbnail" => $characterFull["thumbnail"]["path"] . "." . $characterFull["thumbnail"]["extension"],
                "comicsNumber" => $characterFull["comics"]["available"]
            ];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * Get a list of characters (id, name, thumbnail)
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getCharacters(int $offset, int $limit): array
    {
        try {
            $request = $this->request("GET", "/characters",
                [
                    "limit" => $limit,
                    "offset" => $offset,
                ]
            );
            return array_map(function ($character) {
                return [
                    "id" => $character["id"],
                    "name" => $character["name"],
                    "thumbnail" => $character["thumbnail"]["path"] . "." . $character["thumbnail"]["extension"],
                ];
            }, $request->toArray()["data"]["results"]);
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * Make a request to Marvel API
     * Note: The authentication infos will be included in each request.
     * @param string $method
     * @param string $url
     * @param array $params
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function request(string $method, string $url, array $params = []): ResponseInterface
    {
        $ts = strval(time());
        $defaultQuery = [
            "apikey" => $_ENV["MARVEL_PUBLIC_KEY"],
            "ts" => $ts,
            "hash" => md5($ts . $_ENV["MARVEL_PRIVATE_KEY"] . $_ENV["MARVEL_PUBLIC_KEY"])
        ];
        return $this->http->request($method, $this->guessURL($url), ["query" => array_merge($defaultQuery, $params)]);
    }

    /**
     * Get full URL which can be requested
     * @param string $uri
     * @return string
     */
    private function guessUrl(string $uri): string
    {
        return self::$MARVEL_ENDPOINT . $uri;
    }
}
