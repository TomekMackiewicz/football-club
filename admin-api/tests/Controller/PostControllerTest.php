<?php

// ./bin/phpunit

namespace App\Tests\Controller;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
//use Guzzle\Http\Exception\ClientErrorResponseException;

class PostControllerTest extends WebTestCase
{ 
    public static $apiUrl = 'http://localhost:8000/api/v1/post';
    
    public static function setUpBeforeClass(): void
    {
        //self::buildDb();
    }
    
    public function testGetPostEmptyResult()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get(self::$apiUrl.'?sort=publishDate&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""}');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testAddNewPost()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        $response = $client->post(self::$apiUrl, [
            'json' => [
                'title' => 'Title',
                'body' => 'Lorem ipsum...',
                'slug' => 'test-slug',
                'publishDate' => $date->format('Y-m-d'),
                'modifyDate' => $date->format('Y-m-d')
             ]
        ]); 
        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('post.added', $msg);

    }

    public function testGetPosts()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get(self::$apiUrl.'?sort=publishDate&order=desc&page=1&size=10&filters={"dateFrom":null,"dateTo":null,"title":""}');
        $this->assertEquals(200, $response->getStatusCode());
    }    
   

    public function testAddNewPostNullValuesRegex()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        
        $response = $client->post(self::$apiUrl, [
            'json' => [
                'title' => '',
                'body' => '',
                'slug' => '',
                'publishDate' => $date->format('Y-m-d'),
                'modifyDate' => $date->format('Y-m-d')
             ],
             'http_errors' => false
        ]);
        $msg = json_decode($response->getBody(true), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.required', $msg['errors']);       
        
    }
    
    public function testEditPost()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();
        $response = $client->patch(self::$apiUrl, [
            'json' => [
                'id' => 1,
                'title' => 'New title',
                'body' => 'Lorem ipsum dolor si emet...',
                'slug' => 'test-slug',
                'publishDate' => $date->format('Y-m-d'),
                'modifyDate' => $date->format('Y-m-d') 
             ]
        ]);

        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('post.edited', $msg);        
    }

    public function testUniqueSlug()
    {
        $client = new \GuzzleHttp\Client();

        $date = new \DateTime();        
        $response = $client->post(self::$apiUrl, [
            'json' => [
                'title' => 'Title',
                'body' => 'Lorem ipsum...',
                'slug' => 'test-slug',
                'publishDate' => $date->format('Y-m-d'),
                'modifyDate' => $date->format('Y-m-d')
             ],
            'http_errors' => false
        ]); 
        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains('validation.unique', $msg['errors']); 

    }    
    
    public function testDeletePost()
    {
        $client = new \GuzzleHttp\Client();
        
        $response = $client->delete(self::$apiUrl, [
            'json' => [1,2,3]
        ]); 
        $msg = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('posts.deleted', $msg);         
    }
  
}       
