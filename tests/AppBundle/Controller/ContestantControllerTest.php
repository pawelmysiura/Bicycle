<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventSign;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class ContestantControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
       return [
            'AppBundle\DataFixtures\ORM\UserFixtures',
            'AppBundle\DataFixtures\ORM\EventFixtures',
           'AppBundle\DataFixtures\ORM\EventSignFixtures'
        ];
    }

    public function testContestants()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 1
        ]);
        $crawler = $this->client->request('POST', '/panel/event/your/contestant/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.contestant_list', [], 'controller'), $this->client->getResponse()->getContent());
        $this->assertContains('admin', $crawler->filter('td')->text());
    }

    public function testAcceptContestant()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 4
        ]);
        $contestant = $this->em->getRepository(EventSign::class)->findOneBy([
            'id' => 2
        ]);
        $this->client->request('POST', '/panel/event/accept/'.$event->getSlug().'/'.$contestant->getCode());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.contestant.verify', [], 'message'), $this->client->getResponse()->getContent());
        $this->assertContains('Pawel', $this->client->getResponse()->getContent());
    }

    public function testSetNumbers()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 1
        ]);
        $this->client->request('POST', '/panel/event/setNumber/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.set_numbers', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testChangeNumbers()
    {
        $container = self::$kernel->getContainer();
        $user = $this->em->getRepository(User::class)->findOneBy([
            'id' => 3
        ]);
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 4
        ]);
        $crawler = $this->client->request('POST', '/panel/event/changeNumber/'.$event->getSlug().'/'.$user->getUsername());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['change_number[startNumber]'] = 4;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.change_number', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testEventApplication()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 4
        ]);
        $crawler = $this->client->request('POST', '/panel/event/application/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.confirm.confirm', [], 'controller'), $this->client->getResponse()->getContent());
        $this->assertContains('admin' , $this->client->getResponse()->getContent());
    }

    public function testSetPermissions()
    {
        $container = self::$kernel->getContainer();
        $user = $this->em->getRepository(User::class)->findOneBy([
            'id' => 2
        ]);
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 4
        ]);
        $crawler = $this->client->request('POST', '/panel/event/permissions/'.$event->getSlug().'/'.$user->getUsername());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['change_permissions[permissions]']->setValue('1');
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.contestant.change_permissions', [], 'message'), $this->client->getResponse()->getContent());
        $this->assertContains($container->get('translator')->trans('panel.event.service', [], 'controller'), $this->client->getResponse()->getContent());
    }
}