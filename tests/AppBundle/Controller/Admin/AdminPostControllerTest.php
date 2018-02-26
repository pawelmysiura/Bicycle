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
        $this->client->request('GET', '/admin/posts');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
    }

    public function testPostEdit()
    {
        $post = $this->em->getRepository(Post::class)->findOneBy([
            'id' => 2
        ]);
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('POSt', '/admin/post/edit/'.$post->getSlug());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_post[title]'] = 'Test title post';
        $form['create_post[category]'] = ;
        $form['create_post[tag][]'] = ;
        $form['name="create_post[content]"'] = 'Test post content';
        $form['create_post[publishDate][date][year]'] = ;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
    }
}