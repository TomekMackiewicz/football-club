<?php

// ./bin/phpunit --filter category

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class PostControllerTest extends WebTestCase
{ 
    private static $apiUrl = 'http://localhost:8000/api/v1/post';
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
    
    public function testGetPostEmptyResult()
    {
        $response = $this->client->get(self::$apiUrl.'?sort=publishDate&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""}');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewPost()
    {
        $date = new \DateTime();        
        $response = $this->client->post(self::$apiUrl, [
            'json' => [
                'title' => 'Title',
                'body' => 'Lorem ipsum...',
                'slug' => 'test-slug',
                'categories' => [1]
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('post.added', $msg);

    }

    public function testGetPosts()
    {
        $response = $this->client->get(self::$apiUrl.'?sort=publishDate&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""}');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddNewPostNullValuesRegex()
    {
        $response = $this->client->post(self::$apiUrl, [
            'json' => [
                'title' => '',
                'body' => '',
                'slug' => '',
                'categories' => []
             ],
             'http_errors' => false
        ]);
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);       
    }
   
    public function testEditPost()
    {
        $response = $this->client->patch(self::$apiUrl.'/1', [
            'json' => [
                'id' => 1,
                'title' => 'New title',
                'body' => 'Lorem ipsum dolor si emet...',
                'slug' => 'test-slug',
                'categories' => [1]
             ]
        ]);
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('post.edited', $msg);        
    }

    public function testUniqueSlug()
    {        
        $response = $this->client->post(self::$apiUrl, [
            'json' => [
                'title' => 'Title',
                'body' => 'Lorem ipsum...',
                'slug' => 'test-slug',
                'categories' => [1]
             ],
            'http_errors' => false
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.unique', $msg['errors']); 
    }    
 
    public function testDeletePost()
    {
        $response = $this->client->delete(self::$apiUrl, [
            'json' => [1,2,3]
        ]); 
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('posts.deleted', $msg);         
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
