<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Map;
use Symfony\Component\HttpFoundation\Response;

class AdminMapControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\UserFixtures',
            'AppBundle\DataFixtures\ORM\MapFixtures'
        ];
    }

    public function testMapList()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/admin/maps');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.maps', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('search.submit', [], 'form'))->form();
        $form['search_content[search]'] = 'test map';
        $this->client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('search', [], 'controller').': test map', $this->client->getResponse()->getContent());
    }

    public function testEditMap()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $crawler = $this->client->request('POST', '/admin/map/edit/'.$map->getId());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.map_edit', [], 'controller'), $this->client->getResponse()->getContent());
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

    public function testdeleteMap()
    {
        $container = self::$kernel->getContainer();
        $map = $this->em->getRepository(Map::class)->findOneBy([
            'name' => 'Quisque condimentum tellus vel ultrices.'
        ]);
        $this->client->request('DELETE', '/admin/map/delete/'.$map->getId());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.map.map_delete', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testHandleSearch(){
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/admin/maps/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.maps', [], 'controller'), $this->client->getResponse()->getContent());
    }

//    public function testPostEdit()
//    {
//        $title = 'Test title post';
//        $post = $this->em->getRepository(Post::class)->findOneBy([
//            'id' => 2
//        ]);
//        $container = self::$kernel->getContainer();
//        $crawler = $this->client->request('POSt', '/admin/post/edit/'.$post->getSlug());
//        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
//        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
//        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
//        $form['create_post[title]'] = $title;
//        $form['create_post[category]']->setValue(2);
//        $form['create_post[tag]']->setValue([3, 5]);
//        $form['create_post[content]'] = 'Test post content';
//        $form['create_post[publishDate][date][day]']->setValue(22);
//        $form['create_post[publishDate][date][month]']->setValue(2);
//        $form['create_post[publishDate][date][year]']->setValue(2018);
//        $form['create_post[publishDate][time][hour]']->setValue(11);
//        $form['create_post[publishDate][time][minute]']->setValue(29);
//        $this->client->submit($form);
//        $this->client->followRedirect();
//        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
//        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.post_edit', [], 'message'), $this->client->getResponse()->getContent());
//        $newPost = $this->em->getRepository(Post::class)->findOneBy([
//            'title' => $title
//        ])->getTitle();
//        $this->assertEquals($title, $newPost);
//    }


}