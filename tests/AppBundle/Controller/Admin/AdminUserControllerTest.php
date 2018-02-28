<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class AdminUserControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\UserFixtures'
        ];
    }

    public function testPostList()
    {
        $container = self::$kernel->getContainer();
        $crawler = $this->client->request('GET', '/admin/users');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.users', [], 'controller'), $this->client->getResponse()->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('search.submit', [], 'form'))->form();
        $form['search_content[search]'] = 'admin';
        $this->client->submit($form);
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('search', [], 'controller').': admin', $this->client->getResponse()->getContent());
    }

    public function testActiveUser()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'wsad']);
        $container = self::$kernel->getContainer();
        $this->client->request('POST', '/admin/user/active/'.$user->getId());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.user_deactive', [], 'message'), $this->client->getResponse()->getContent());
    }

    public function testHandleSearch(){
        $container = self::$kernel->getContainer();
        $this->client->request('GET', '/admin/user/search');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.users', [], 'controller'), $this->client->getResponse()->getContent());
    }
}