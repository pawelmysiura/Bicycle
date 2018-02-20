<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class FrontControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    private $client;


    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function pageTest($url, $message)
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans($message, [], 'controller'), $crawler->filter('.container h1')->text());

    }

    public function testIndex()
    {
        $this->pageTest('/', 'front.index.text_1');
    }

    public function testAbout()
    {
        $this->pageTest('/about', 'front.title.about');
    }

    public function testcontact()
    {
        $this->pageTest('/contact', 'front.title.contact');
    }

}