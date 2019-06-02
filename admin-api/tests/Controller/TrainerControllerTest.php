<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use GuzzleHttp\Client;

class TrainerControllerTest extends WebTestCase
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
        self::createSchema(self::$kernel, self::$application);
    }
    
    public static function tearDownAfterClass(): void
    {
        self::dropSchema(self::$application);
    }

    public function testGetTrainersEmptyResult(): void
    {
        $response = $this->client->get(
            $this->apiUrl.'/trainers?sort=login&order=desc&page=1&size=10&filters={"firstName":"","lastName":"","login":"","email":""}'
        );
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewTrainer(): void
    {        
        $response = $this->client->post($this->apiUrl.'/trainers', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'login' => 'JD',
                'email' => 'john@gmail.com',
                'status' => 1
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('trainer.added', $msg);
    }

    public function testGetTrainers(): void
    {
        $response = $this->client->get(
            $this->apiUrl.'/trainers?sort=login&order=desc&page=1&size=10&filters={"firstName":"","lastName":"","login":"","email":""}'
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddNewTrainerNullValuesRegex(): void
    {        
        $response = $this->client->post($this->apiUrl.'/trainers', [
            'json' => [
                'firstName' => '',
                'lastName' => '',
                'login' => 'JD',
                'email' => 'doe@gmail.com',
                'status' => 1
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);
    }

    public function testAddNewTrainerInvalidEmail(): void
    {        
        $response = $this->client->post($this->apiUrl.'/trainers', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'login' => 'JD',
                'email' => 'doegmail.com',
                'status' => 1
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.invalidEmail', $msg['errors']);
    }    

    public function testAddNewTrainerUniqueEmail(): void
    {        
        $response = $this->client->post($this->apiUrl.'/trainers', [
            'json' => [
                'firstName' => 'Johny',
                'lastName' => 'Doe',
                'login' => 'JD',
                'email' => 'john@gmail.com',
                'status' => 1
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.uniqueEmail', $msg['errors']);
    }
    
    public function testEditTrainer(): void
    {
        $response = $this->client->patch($this->apiUrl.'/trainers/1', [
            'json' => [
                'id' => 1,
                'firstName' => 'John',
                'lastName' => 'Snow',
                'login' => 'JS',
                'email' => 'johnsnow@gmail.com',
                'status' => 0
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('trainer.edited', $msg);
    }

    public function testDeleteTrainer(): void
    {
        $response = $this->client->delete($this->apiUrl.'/trainers', [
            'json' => [1,2,3],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('trainers.deleted', $msg);
    }

    private static function createSchema($kernel, $application): void
    {
        $application->setAutoExit(false);
        $doctrine = $kernel->getContainer()->get('doctrine');
        $schemaManager = $doctrine->getConnection()->getSchemaManager();

        if ($schemaManager->tablesExist(array('trainer')) === false) {
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
