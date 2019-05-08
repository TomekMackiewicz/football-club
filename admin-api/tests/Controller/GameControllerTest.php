<?php

// ./bin/phpunit

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
//use Guzzle\Http\Exception\ClientErrorResponseException;

class GameControllerTest extends WebTestCase
{ 
    public static $apiUrl = 'http://localhost:8000/api/game';
    
    public static function setUpBeforeClass(): void
    {
        self::buildDb();
    }
    
    public function testGetGamesEmptyResult()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get(self::$apiUrl.'/all?sort=date&order=desc&page=1&size=10');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewGame()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        $response = $client->post(self::$apiUrl.'/new', [
            'json' => [
                'date' => $date->format('Y-m-d'),
                'location' => 'Test location',
                'game_type' => '1',
                'host_team' => '1',
                'guest_team' => '1',
                'host_score' => '1',
                'guest_score' => '1' 
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('game.added', $msg);

    }

    public function testGetGames()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get(self::$apiUrl.'/all?sort=date&order=desc&page=1&size=10');
        $this->assertEquals(200, $response->getStatusCode());
    }    
    
    public function testAddNewGameScoreRegex()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        
        $response = $client->post(self::$apiUrl.'/new', [
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
        //$msg = json_decode($response->getBody(true), true);

        $this->assertEquals(500, $response->getStatusCode());
        //$this->assertContains('validation.digits', $msg['errors']);       
        
    }    

    public function testAddNewGameNullValuesRegex()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        
        $response = $client->post(self::$apiUrl.'/new', [
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
        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);       
        
    }
    
    public function testEditGame()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        $response = $client->patch(self::$apiUrl.'/update', [
            'json' => [
                'id' => 1,
                'date' => $date->format('Y-m-d'),
                'location' => 'Different location',
                'game_type' => '1',
                'host_team' => '1',
                'guest_team' => '1',
                'host_score' => '1',
                'guest_score' => '1' 
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('game.edited', $msg);        
    }
    
    private static function buildDb()
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        $application = new Application($kernel);

        $application->setAutoExit(false);    

        $application->run(new ArrayInput(array(
            'doctrine:schema:drop',
            '--force' => true
        )));

        $application->run(new ArrayInput(array(
            'doctrine:schema:create'
        )));

//        $application->run(new ArrayInput(array(
//            'fos:user:create', 
//            'username' => 'admin', 
//            'email' => 'admin@test.com', 
//            'password' => 'admin', 
//            '--super-admin' =>true
//        )));    
//
//        $application->run(new ArrayInput(array(
//            'fos:user:create', 
//            'username' => 'user', 
//            'email' => 'user@test.com', 
//            'password' => 'user'
//        )));    

    }   
}       