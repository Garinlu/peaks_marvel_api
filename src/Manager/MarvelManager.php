<?php


namespace App\Manager;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MarvelManager
{
    private static $MARVEL_ENDPOINT = "http://gateway.marvel.com/v1/publc";
    /** @var HttpClientInterface */
    private $http;

    public function __construct()
    {
        $ts = strval(time());
        $this->http = HttpClient::create(["query" => [
            "apiKey" => $_ENV["MARVEL_PUBLIC_KEY"],
            "ts" => $ts,
            "hash" => md5($ts . $_ENV["MARVEL_PRIVATE_KEY"] . $_ENV["MARVEL_PUBLIC_KEY"])
        ]]);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCharacters(int $offset, int $limit): array
    {
        $request = $this->http->request("GET", $this->guessURL("/characters"),
            ["limit" => $limit, "offset" => $offset]
        );
        var_dump($request);
        return [];
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
