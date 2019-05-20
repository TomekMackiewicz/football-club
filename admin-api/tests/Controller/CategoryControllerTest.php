<?php

// ./bin/phpunit

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class CategoryControllerTest extends WebTestCase
{ 
    private static $apiUrl = 'http://localhost:8000/api/v1/category';
    private $client;
    
    public function __construct()
    {
        parent::__construct();
        $this->client = new \GuzzleHttp\Client();
    }

    public static function setUpBeforeClass(): void
    {
        self::buildDb();
    }
    
    public function testGetCategoriesEmptyResult()
    {
        $response = $this->client->get(self::$apiUrl.'?sort=name&order=desc&page=1&size=10&filters={"name":""}');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewCategory()
    {        
        $response = $this->client->post(self::$apiUrl, [
            'json' => [
                'name' => 'Default'
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('category.added', $msg);
    }

    public function testGetCategories()
    {
        $response = $this->client->get(self::$apiUrl.'?sort=name&order=desc&page=1&size=10&filters={"name":""}');
        $this->assertEquals(200, $response->getStatusCode());
    }    

    public function testAddNewCategoryNullValuesRegex()
    {        
        $response = $this->client->post(self::$apiUrl, [
            'json' => [
                'name' => ''
             ],
             'http_errors' => false
        ]);
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);       
        
    }
    
    public function testEditCategory()
    {       
        $response = $this->client->patch(self::$apiUrl, [
            'json' => [
                'id' => 1,
                'name' => 'New' 
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('category.edited', $msg);        
    }
    
    public function testDeleteCategory()
    {
        $response = $this->client->delete(self::$apiUrl, [
            'json' => [1,2,3]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('categories.deleted', $msg);         
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
