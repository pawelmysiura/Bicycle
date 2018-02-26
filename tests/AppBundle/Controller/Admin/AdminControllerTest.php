<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\UserFixtures'
        ];
    }

    public function testAdmin()
    {
        $crawler = $this->client->request('GET', '/admin/');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin Panel', $crawler->filter('h3')->text());
    }
}