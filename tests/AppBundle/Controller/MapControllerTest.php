<?php

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\Map;
use Symfony\Component\HttpFoundation\Response;

class MapControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
       return [
            'AppBundle\DataFixtures\ORM\UserFixtures',
            'AppBundle\DataFixtures\ORM\MapFixtures'
        ];
    }

    public function testCreateMap()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('POST', '/panel/map/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.maps.title.create', [], 'controller'), $crawler->filter('h3')->text());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_map[name]'] = 'Test name';
        $form['create_map[description]'] = 'test description';
        $form['create_map[start]'] = '{"lat":49.9672344,"lng":18.861218000000008}';
        $form['create_map[end]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $form['create_map[waypoints]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.map.map_create', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testShowMap()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $crawler = $this->client->request('POST', '/panel/map/show/'.$map->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Quisque condimentum tellus vel ultrices.', $crawler->filter('h3')->text());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['map_comment[comment]'] = 'Test comment';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.comment_send', [], 'message'), $this->client->getResponse()->getContent());
    }
    public function testEditMap()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $crawler = $this->client->request('POST', '/panel/map/edit/'.$map->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.maps.title.edmit', [], 'controller'), $crawler->filter('h3')->text());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_map[name]'] = 'Test name';
        $form['create_map[description]'] = 'test description';
        $form['create_map[start]'] = '{"lat":49.9672344,"lng":18.861218000000008}';
        $form['create_map[end]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $form['create_map[waypoints]'] = '{"lat":49.9679214,"lng":19.783434400000033}';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.map.map_edit', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testRemoveMap()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $this->client->request('POST', '/panel/map/delete/'.$map->getId());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.map.map_delete', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testAllMaps()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/panel/maps');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.maps.title.list', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('search.submit', [], 'form'))->form();
        $form['search_content[search]'] = 'Quisque';
        $this->client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('search', [], 'controller').' Quisque', $this->client->getResponse()->getContent());
    }

    public function testFavourite()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $this->client->request('POST', '/panel/add/favourite/'.$map->getId());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.map.add_favourite', [], 'message'), $this->client->getResponse()->getContent());

    }

    public function testRemoveFavourite()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $this->client->request('POST', '/panel/remove/favourite/'.$map->getId());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.map.remove_favourite', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testFavouriteList()
    {
        $container = self::$kernel->getContainer();
        $this->client->request('GET', '/panel/map/favourite');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.maps.title.favorite', [], 'controller'), $this->client->getResponse()->getContent());
    }

    public function testUserMap()
    {
        $container = self::$kernel->getContainer();
        $this->client->request('GET', '/panel/user/maps');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.maps.title.user_list', [], 'controller'), $this->client->getResponse()->getContent());
    }

    public function testHandleSearch()
    {
        $container = self::$kernel->getContainer();
        $this->client->request('GET', '/panel/maps/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.maps.title.list', [], 'controller'), $this->client->getResponse()->getContent());
    }
}