<?php
//declare(strict_types=1);

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use GuzzleHttp\Client;

class CategoryControllerTest extends WebTestCase
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
        self::clearSchema(self::$application);
    }
    
    public function testGetCategoriesEmptyResult(): void
    {
        $response = $this->client->get($this->apiUrl.'/categories?sort=name&order=desc&page=1&size=10&filters={"name":""}');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewCategory(): void
    {        
        $response = $this->client->post($this->apiUrl.'/categories', [
            'json' => [
                'name' => 'Default'
             ]
        ]);
        $msg = json_decode($response->getBody(true), true);
        //$msg = (string) $response->getBody();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('category.added', $msg);
    }

    public function testGetCategories(): void
    {
        $response = $this->client->get($this->apiUrl.'/categories?sort=name&order=desc&page=1&size=10&filters={"name":""}');
        $this->assertEquals(200, $response->getStatusCode());
    }    

    public function testAddNewCategoryNullValuesRegex(): void
    {        
        $response = $this->client->post($this->apiUrl.'/categories', [
            'json' => [
                'name' => ''
             ],
             'http_errors' => false
        ]);
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);       
        
    }
    
    public function testEditCategory(): void
    {       
        $response = $this->client->patch($this->apiUrl.'/categories/1', [
            'json' => [
                'id' => 1,
                'name' => 'New' 
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('category.edited', $msg);        
    }
    
    public function testDeleteCategory(): void
    {
        $response = $this->client->delete($this->apiUrl.'/categories', [
            'json' => [1,2,3]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('categories.deleted', $msg);         
    }

    private static function buildDb($kernel, $application): void
    {        
        $application->setAutoExit(false);
        $doctrine = $kernel->getContainer()->get('doctrine');       
        $schemaManager = $doctrine->getConnection()->getSchemaManager();
        
        if ($schemaManager->tablesExist(array('category')) === false) {
            $application->run(new ArrayInput(array(
                'doctrine:schema:drop',
                '--force' => true
            )));

            $application->run(new ArrayInput(array(
                'doctrine:schema:create'
            )));            
        }
    }
    
    private static function clearSchema($application): void
    {
        $application->setAutoExit(false);
        $application->run(new ArrayInput(array(
            'doctrine:schema:drop',
            '--force' => true
        )));        
    }
}       
