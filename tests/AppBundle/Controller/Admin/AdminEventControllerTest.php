<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Response;

class AdminEventControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\UserFixtures',
            'AppBundle\DataFixtures\ORM\EventFixtures',
            'AppBundle\DataFixtures\ORM\EventSignFixtures'
        ];
    }

    public function testEvent()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 1
        ]);
        $crawler = $this->client->request('POST', '/admin/event');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.events', [], 'controller'), $this->client->getResponse()->getContent());
        $this->assertEquals(5, $crawler->filter('tr')->count());
    }

    public function testDeleteEvent()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 1
        ]);
        $this->client->request('DELETE', '/admin/event/delete/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.delete', [], 'message'), $this->client->getResponse()->getContent());
        $this->assertEquals(4, $crawler->filter('tr')->count());

    }

    public function testHandleSearch(){
        $container = self::$kernel->getContainer();
        $this->client->request('GET', '/admin/event/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.events', [], 'controller'), $this->client->getResponse()->getContent());
    }

}