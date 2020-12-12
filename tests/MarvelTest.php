<?php


namespace App\Tests;


use App\Manager\MarvelManager;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MarvelTest extends TestCase
{
    /** @var MarvelManager */
    private $marvelManager;

    public function __construct()
    {
        parent::__construct();
        $this->marvelManager = new MarvelManager();

    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetCharacters(): array
    {
        $data = $this->marvelManager->getCharacters(0, 20);
        $this->assertArrayHasKey("total", $data);
        $this->assertArrayHasKey("data", $data);
        $characters = $data["data"];
        $this->assertIsArray($characters);
        $this->assertSame(20, sizeof($characters));
        $character = current($characters);
        $this->assertArrayHasKey("id", $character);
        $this->assertArrayHasKey("name", $character);
        $this->assertArrayHasKey("thumbnail", $character);
        $this->assertIsInt($character["id"]);
        $this->assertIsString($character["name"]);
        $this->assertIsString($character["thumbnail"]);
        return [$character["id"]];
    }

    /**
     * @depends  testGetCharacters
     * @param array $ids
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetCharacter(array $ids): array
    {
        $id = current($ids);
        $character = $this->marvelManager->getCharacter($id);
        $this->assertIsArray($character);
        $this->assertArrayHasKey("id", $character);
        $this->assertArrayHasKey("name", $character);
        $this->assertArrayHasKey("description", $character);
        $this->assertArrayHasKey("comicsNumber", $character);
        $this->assertArrayHasKey("thumbnail", $character);
        $this->assertIsInt($character["id"]);
        $this->assertIsString($character["name"]);
        $this->assertIsString($character["description"]);
        $this->assertIsInt($character["comicsNumber"]);
        $this->assertIsString($character["thumbnail"]);
        return [$character["id"]];
    }

    /**
     * @depends  testGetCharacter
     * @param array $ids
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetCharacterComics(array $ids)
    {
        $id = current($ids);
        $comics = $this->marvelManager->getCharacterComics($id, 0, 2);
        $this->assertIsArray($comics);
        $this->assertSame(2, sizeof($comics));
        $this->assertIsString($comics[0]);
    }

}
