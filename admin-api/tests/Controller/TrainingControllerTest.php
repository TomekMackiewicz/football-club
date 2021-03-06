<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use GuzzleHttp\Client;

class TrainingControllerTest extends WebTestCase
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
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime();
        $this->endDate->add(new \DateInterval('PT1H'));        
    }

    public static function setUpBeforeClass(): void
    {
        self::createSchema(self::$kernel, self::$application);
    }
    
    public static function tearDownAfterClass(): void
    {
        self::dropSchema(self::$application);
    }

    public function testGetTrainingsEmptyResult(): void
    {
        $response = $this->client->get(
            $this->apiUrl.'/trainings?sort=startDate&order=desc&page=1&size=10&filters={"location":""}'
        );
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewTraining(): void
    {
        $this->prepareTrainer();
        $this->prepareTeam();
        
        $response = $this->client->post($this->apiUrl.'/trainings', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location1',
                'trainers' => [1],
                'teams' => [1]
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('training.added', $msg);
    }

    public function testAddNewTrainingOverlapingDatesForLocation(): void
    { 
        $response = $this->client->post($this->apiUrl.'/trainings', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location1',
                'trainers' => [],
                'teams' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.overlapingDateForLocation', $msg['errors']);
    } 

    public function testEditTrainingOverlapingDatesForLocation(): void
    { 
        $response = $this->client->patch($this->apiUrl.'/trainings/1', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location1',
                'trainers' => [1],
                'teams' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('training.edited', $msg);
    }

    public function testAddNewTrainingOverlapingDatesForTrainer(): void
    { 
        $response = $this->client->post($this->apiUrl.'/trainings', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location2',
                'trainers' => [1],
                'teams' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.overlapingDateForTrainer', $msg['errors']);
    } 

    public function testEditTrainingOverlapingDatesForTrainer(): void
    { 
        $response = $this->client->patch($this->apiUrl.'/trainings/1', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location2',
                'trainers' => [1],
                'teams' => [1]
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('training.edited', $msg);
    }

    public function testAddNewTrainingOverlapingDatesForTeam(): void
    { 
        $response = $this->client->post($this->apiUrl.'/trainings', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location2',
                'trainers' => [],
                'teams' => [1]
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.overlapingDateForTeam', $msg['errors']);
    } 

    public function testEditTrainingOverlapingDatesForTeam(): void
    { 
        $response = $this->client->patch($this->apiUrl.'/trainings/1', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location2',
                'trainers' => [],
                'teams' => [1]
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('training.edited', $msg);
    }
    
    public function testGetTrainings(): void
    {
        $response = $this->client->get(
            $this->apiUrl.'/trainings?sort=startDate&order=desc&page=1&size=10&filters={"location":""}'
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddNewTrainingNullValuesRegex(): void
    { 
        $response = $this->client->post($this->apiUrl.'/trainings', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => '',
                'trainers' => [],
                'teams' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);
    }
    
    public function testEditTraining(): void
    {
        $response = $this->client->patch($this->apiUrl.'/trainings/1', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Berlin',
                'trainers' => [],
                'teams' => []
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('training.edited', $msg);
    }
    
    public function testDeleteTrainings(): void
    {
        $response = $this->client->delete($this->apiUrl.'/trainings', [
            'json' => [1,2,3],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('trainings.deleted', $msg);
    }
    
    public function testStartDateGreaterThanEndDate(): void
    {
        $this->startDate->add(new \DateInterval('PT2H'));
        $response = $this->client->post($this->apiUrl.'/trainings', [
            'json' => [
                'startDate' => $this->startDate->format('Y-m-d H:i'),
                'endDate' => $this->endDate->format('Y-m-d H:i'),
                'location' => 'Location2',
                'trainers' => [],
                'teams' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.startDateGreaterThanEndDate', $msg['errors']);
    }

    private function prepareTrainer()
    {
        $this->client->post($this->apiUrl.'/trainers', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'login' => 'JD',
                'email' => 'john@gmail.com',
                'status' => 1
             ],
            'http_errors' => false
        ]);
    }    

    private function prepareTeam()
    {
        $this->client->post($this->apiUrl.'/teams', [
            'json' => [
                'name' => 'TeamOne',
                'year' => 2008,
                'belongsToUser' => true,
                'playsLeague' => true
             ],
            'http_errors' => false
        ]);
    }
    
    private static function createSchema($kernel, $application): void
    {
        $application->setAutoExit(false);
        $doctrine = $kernel->getContainer()->get('doctrine');
        $schemaManager = $doctrine->getConnection()->getSchemaManager();

        if ($schemaManager->tablesExist(array('training')) === false) {
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
