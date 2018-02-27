<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;

class AdminPostControllerTest extends BaseControllerTest
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

    public function testPostList()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/admin/posts');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('search.submit', [], 'form'))->form();
        $form['search_content[search]'] = 'Varius';
        $this->client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('search', [], 'controller').': Varius', $this->client->getResponse()->getContent());
    }

    public function testPostEdit()
    {
        $title = 'Test title post';
        $post = $this->em->getRepository(Post::class)->findOneBy([
            'id' => 2
        ]);
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('POSt', '/admin/post/edit/'.$post->getSlug());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_post[title]'] = $title;
        $form['create_post[category]']->setValue(2);
        $form['create_post[tag]']->setValue([3, 5]);
        $form['create_post[content]'] = 'Test post content';
        $form['create_post[publishDate][date][day]']->setValue(22);
        $form['create_post[publishDate][date][month]']->setValue(2);
        $form['create_post[publishDate][date][year]']->setValue(2018);
        $form['create_post[publishDate][time][hour]']->setValue(11);
        $form['create_post[publishDate][time][minute]']->setValue(29);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.post_edit', [], 'message'), $this->client->getResponse()->getContent());
//        $newPost = $this->em->getRepository(Post::class)->findOneBy([
//            'title' => $title
//        ])->getTitle();
//        $this->assertEquals($title, $newPost);
    }

    public function testPostCreate()
    {
        $title = 'New test title post';
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('POSt', '/admin/post/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_post[title]'] = $title;
        $form['create_post[category]']->setValue(2);
        $form['create_post[tag]']->setValue([3, 5]);
        $form['create_post[content]'] = 'Test post content';
        $form['create_post[publishDate][date][day]']->setValue(22);
        $form['create_post[publishDate][date][month]']->setValue(2);
        $form['create_post[publishDate][date][year]']->setValue(2018);
        $form['create_post[publishDate][time][hour]']->setValue(11);
        $form['create_post[publishDate][time][minute]']->setValue(29);
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.post_created', [], 'message'), $this->client->getResponse()->getContent());
//        $newPost = $this->em->getRepository(Post::class)->findOneBy([
//            'title' => $title
//        ])->getTitle();
//        $this->assertEquals($title, $newPost);
    }

    public function testPostDelete()
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => 1]);
        $container = self::getContainer();
        $this->client->request('DELETE', '/admin/post/delete/'.$post->getSlug());
        $this->client->followRedirect();
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.post_delete', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testHandleSearch(){
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/admin/posts/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
    }
}