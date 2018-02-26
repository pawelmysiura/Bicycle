<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
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
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/contact');
        $this->pageTest('/contact', 'front.title.contact');
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['contact[email]'] = 'test@test.pl';
        $form['contact[subject]'] = 'test';
        $form['contact[message]'] = 'message test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.front.message_send', [], 'message'), $response->getContent());
    }

}