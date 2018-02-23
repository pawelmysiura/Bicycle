<?php

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\Post;
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

    public function pageTest($url, $message, $selector)
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans($message, [], 'controller'), $crawler->filter($selector)->text());

        return $crawler;
    }

    public function testPanel()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->pageTest('/panel', 'panel.post.title.posts', 'h3');
        $form = $crawler->selectButton($container->get('translator')->trans('search.submit', [], 'form'))->form();
        $form['search_content[search]'] = 'Varius';
        $this->client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('search', [], 'controller').': Varius', $this->client->getResponse()->getContent());

    }

    public function testCategory()
    {
        $slug = 'trasy';
        $this->pageTest('/panel/post/category/'.$slug, 'panel.post.title.category', 'h3');
    }

    public function testTag()
    {
        $slug = 'hilotaes';
        $this->pageTest('/panel/post/tag/'.$slug, 'panel.post.title.tag', 'h3');
    }

    public function testPost()
    {
        $container = self::$kernel->getContainer();
        $post = $this->em->getRepository(Post::class)->findOneBy([
            'id' => 4
        ]);
        $slug = $post->getSlug();
        $crawler = $this->client->request('POST', '/panel/post/'.$slug);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($post->getTitle(), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submint', [], 'form'))->form();
        $form['post_comment[comment]'] = 'Test comment';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.comment_send', [], 'message'), $this->client->getResponse()->getContent());
    }
    public function testHandleSearch(){
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/panel/posts/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('panel.post.title.posts', [], 'controller'), $this->client->getResponse()->getContent());
    }
}