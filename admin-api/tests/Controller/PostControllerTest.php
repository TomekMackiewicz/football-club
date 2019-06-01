<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use GuzzleHttp\Client;

class PostControllerTest extends WebTestCase
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
        // TODO add category? (test for non existing cat?)
    }

    public static function tearDownAfterClass(): void
    {
        self::dropSchema(self::$application);
    }
    
    public function testGetPostEmptyResult()
    {
        $response = $this->client->get($this->apiUrl.'/posts?sort=publishDate&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""}');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewPost()
    {        
        $response = $this->client->post($this->apiUrl.'/posts', [
            'json' => [
                'title' => 'Title',
                'body' => 'Lorem ipsum...',
                'slug' => 'test-slug',
                'categories' => [1]
             ],
            'http_errors' => false
        ]); 
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('post.added', $msg);

    }

    public function testGetPosts()
    {
        $response = $this->client->get($this->apiUrl.'/posts?sort=publishDate&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""}');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddNewPostNullValuesRegex()
    {
        $response = $this->client->post($this->apiUrl.'/posts', [
            'json' => [
                'title' => '',
                'body' => '',
                'slug' => '',
                'categories' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);       
    }
   
    public function testEditPost()
    {
        $response = $this->client->patch($this->apiUrl.'/posts/1', [
            'json' => [
                'id' => 1,
                'title' => 'New title',
                'body' => 'Lorem ipsum dolor si emet...',
                'slug' => 'test-slug',
                'categories' => [1] 
             ],
            'http_errors' => false
        ]);
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('post.edited', $msg);        
    }

    public function testUniqueSlug()
    {        
        $response = $this->client->post($this->apiUrl.'/posts', [
            'json' => [
                'title' => 'Title',
                'body' => 'Lorem ipsum...',
                'slug' => 'test-slug',
                'categories' => [1]
             ],
            'http_errors' => false
        ]); 
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.unique', $msg['errors']); 
    }    
 
    public function testDeletePost()
    {
        $response = $this->client->delete($this->apiUrl.'/posts', [
            'json' => [1,2,3],
            'http_errors' => false
        ]); 
        $msg = json_decode((string) $response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('posts.deleted', $msg);         
    }

    private static function buildDb($kernel, $application)
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
