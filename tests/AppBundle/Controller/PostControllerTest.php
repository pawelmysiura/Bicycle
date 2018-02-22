<?php

namespace Tests\AppBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
       return [
            'AppBundle\DataFixtures\ORM\TagFixtures',
            'AppBundle\DataFixtures\ORM\CategoryFixtures',
            'AppBundle\DataFixtures\ORM\UserFixtures',
            'AppBundle\DataFixtures\ORM\PostFixtures'
        ];
    }

    public function testPanel()
    {
        $crawler = $this->client->request('GET', '/panel');
        $this->client->followRedirect();
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}