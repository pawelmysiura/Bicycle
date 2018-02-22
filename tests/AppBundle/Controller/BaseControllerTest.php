<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

abstract class BaseControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    protected $client;


    public function setUp()
    {
        $credentials = [
            'username' => 'admin',
            'password' => '123'
        ];
        $this->client = $this->makeClient($credentials);
        $this->loadFixtures($this->getFixtures());
        $container = $this->getContainer();
    }

    public abstract function getFixtures();
}