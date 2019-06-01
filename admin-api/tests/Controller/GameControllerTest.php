<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use GuzzleHttp\Client;

class GameControllerTest extends WebTestCase
{
    private $apiUrl;
    private $client;
    protected static $kernel;
    private static $application;
    
    public function __construct()
    {
        parent::__construct();
        self::$kernel = new Kernel('test', true);
        self::$kernel->boot();
        self::$application = new Application(self::$kernel);
        $this->client = new Client();
        $this->apiUrl = self::$kernel->getContainer()->getParameter('base_url');
    }

    public static function setUpBeforeClass(): void
    {
        self::buildDb(self::$kernel, self::$application);
    }

    public static function tearDownAfterClass(): void
    {
        self::dropSchema(self::$application);
    }

    public function testGetGamesEmptyResult(): void
    {
        $response = $this->client->get($this->apiUrl.'/games?sort=date&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"location":"","gameType":"","team":""}');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewGame(): void
    {
        $date = new \DateTime();
        $response = $this->client->post($this->apiUrl.'/games', [
            'json' => [
                'date' => $date->format('Y-m-d'),
                'location' => 'Test location',
                'game_type' => '1',
                'host_team' => '1',
                'guest_team' => '1',
                'host_score' => '1',
                'guest_score' => '1' 
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('game.added', $msg);
    }

    public function testGetGames(): void
    {
        $response = $this->client->get($this->apiUrl.'/games?sort=date&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"location":"","gameType":"","team":""}');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddNewGameScoreRegex(): void
    {
        $date = new \DateTime();
        $response = $this->client->post($this->apiUrl.'/games', [
            'json' => [
                'date' => $date->format('Y-m-d'),
                'location' => 'Test location',
                'game_type' => '1',
                'host_team' => '1',
                'guest_team' => '1',
                'host_score' => 'a',
                'guest_score' => 'b'
             ],
             'http_errors' => false
        ]);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testAddNewGameNullValuesRegex(): void
    {
        $date = new \DateTime();
        $response = $this->client->post($this->apiUrl.'/games', [
            'json' => [
                'date' => $date->format('Y-m-d'),
                'location' => '',
                'game_type' => '',
                'host_team' => '',
                'guest_team' => '',
                'host_score' => '',
                'guest_score' => '' 
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);
    }

    public function testEditGame(): void
    {
        $date = new \DateTime();
        $response = $this->client->patch($this->apiUrl.'/games/1', [
            'json' => [
                'id' => 1,
                'date' => $date->format('Y-m-d'),
                'location' => 'Different location',
                'game_type' => '1',
                'host_team' => '1',
                'guest_team' => '1',
                'host_score' => '1',
                'guest_score' => '1'
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('game.edited', $msg);
    }

    public function testDeleteGame(): void
    {
        $response = $this->client->delete($this->apiUrl.'/games', [
            'json' => [1,2,3],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('games.deleted', $msg);
    }

    private static function buildDb($kernel, $application): void
    {
        $kernel->boot();
        $application->setAutoExit(false);
        $doctrine = $kernel->getContainer()->get('doctrine');
        $schemaManager = $doctrine->getConnection()->getSchemaManager();
        
        if ($schemaManager->tablesExist(array('game')) === false) {
            $application->run(new ArrayInput(array(
                'doctrine:schema:drop',
                '--force' => true
            )));

            $application->run(new ArrayInput(array(
                'doctrine:schema:create'
            )));
        }
    }

    private static function dropSchema($application): void
    {
        $application->setAutoExit(false);
        $application->run(new ArrayInput(array(
            'doctrine:schema:drop',
            '--force' => true
        )));
    }

}
