<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

abstract class BaseControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    public $client;

    /**
     * @var EntityManager $em
     */
    public $em;

    public function setUp()
    {
        $this->setEm();
        $this->fixtureSetUp();
        $credentials = [
            'username' => 'admin',
            'password' => '123'
        ];
        $this->client = $this->makeClient($credentials);
    }

    protected function setEm()
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    protected function fixtureSetUp()
    {
        if (!isset($metadata)) {
            $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
        $this->postFixtureSetup();
        $fixtures = $this->getFixtures();
        $this->loadFixtures($fixtures);
    }

    public abstract function getFixtures();
}