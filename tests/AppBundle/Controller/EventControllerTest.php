<?php

namespace Tests\AppBundle\Controller;



use AppBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Response;

class EventControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
       return [
            'AppBundle\DataFixtures\ORM\UserFixtures',
            'AppBundle\DataFixtures\ORM\EventFixtures',
           'AppBundle\DataFixtures\ORM\EventSignFixtures'
        ];
    }

    public function testCreateEvent()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('POST', '/panel/event/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.menu.event_create', [], 'controller'), $crawler->filter('h3')->text());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_event[title]'] = 'Test name';
        $form['create_event[description]'] = 'test description';
        $form['create_event[start]'] = '{"lat":49.9672344,"lng":18.861218000000008}';
        $form['create_event[end]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $form['create_event[waypoints]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $form['create_event[eventDate][date][day]']->setValue(20);
        $form['create_event[eventDate][date][month]']->setValue(10);;
        $form['create_event[eventDate][date][year]']->setValue(2018);;
        $form['create_event[eventDate][time][hour]']->setValue(12);;
        $form['create_event[eventDate][time][minute]']->setValue(0);;
        $form['create_event[endDateOfRegistration][date][day]']->setValue(10);
        $form['create_event[endDateOfRegistration][date][month]']->setValue(10);
        $form['create_event[endDateOfRegistration][date][year]']->setValue(2018);
        $form['create_event[endDateOfRegistration][time][hour]']->setValue(20);
        $form['create_event[endDateOfRegistration][time][minute]']->setValue(0);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.create', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testShowEvent()
    {
        $comment = 'test comment';
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'title' => 'Phasellus nec enim odio. Morbi.'
        ]);
        $crawler = $this->client->request('POST', '/panel/event/show/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($event->getTitle(), $crawler->filter('h3')->text());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['event_comment[comment]'] = $comment;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.comment_send', [], 'message'), $this->client->getResponse()->getContent());
        $this->assertContains($comment, $this->client->getResponse()->getContent());
    }

    public function testEventList()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('POST', '/panel/event');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.events', [], 'controller'), $crawler->filter('h3')->text());
        $this->assertContains('Phasellus nec enim odio. Morbi.', $crawler->filter('td')->text());
    }

    public function testEditEvent()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 1
        ]);
        $crawler = $this->client->request('POST', '/panel/event/edit/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.edit', [], 'controller'), $crawler->filter('h3')->text());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_event[title]'] = 'Test edit';
        $form['create_event[description]'] = 'test edit';
        $form['create_event[start]'] = '{"lat":49.9672344,"lng":18.861218000000008}';
        $form['create_event[end]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $form['create_event[waypoints]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $form['create_event[eventDate][date][day]']->setValue(20);
        $form['create_event[eventDate][date][month]']->setValue(10);;
        $form['create_event[eventDate][date][year]']->setValue(2018);;
        $form['create_event[eventDate][time][hour]']->setValue(12);;
        $form['create_event[eventDate][time][minute]']->setValue(0);;
        $form['create_event[endDateOfRegistration][date][day]']->setValue(10);
        $form['create_event[endDateOfRegistration][date][month]']->setValue(10);
        $form['create_event[endDateOfRegistration][date][year]']->setValue(2018);
        $form['create_event[endDateOfRegistration][time][hour]']->setValue(20);
        $form['create_event[endDateOfRegistration][time][minute]']->setValue(0);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.edit', [], 'message'), $this->client->getResponse()->getContent());
    }
    public function testApplyEvent()
    {
        $this->client = $this->makeClient([
            'username' => 'wsad',
            'password' => '123'
        ]);
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 2
        ]);
        $this->client->request('GET', '/panel/event/'.$event->getSlug().'/apply');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.join', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testYourEvents()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/panel/event/your');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.events', [], 'controller'), $crawler->filter('h3')->text());
        $this->assertContains('Phasellus nec enim odio. Morbi.', $crawler->filter('td')->text());

    }

    public function testDeleteEvent()
    {
        $container = self::$kernel->getContainer();
        $event = $this->em->getRepository(Event::class)->findOneBy([
            'id' => 1
        ]);
        $this->client->request('GET', '/panel/event/delete/'.$event->getSlug());
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.event.delete', [], 'message'), $this->client->getResponse()->getContent());
    }
    public function testHandleSearch(){
        $container = self::$kernel->getContainer();
        $this->client->request('GET', '/panel/event/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.event.events', [], 'controller'), $this->client->getResponse()->getContent());
    }
}