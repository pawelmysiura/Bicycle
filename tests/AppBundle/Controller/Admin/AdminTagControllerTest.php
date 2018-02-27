<?php

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Response;

class AdminTagControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\TagFixtures',
            'AppBundle\DataFixtures\ORM\UserFixtures'
        ];
    }

    public function testTagList()
    {
        $container = self::getContainer();
        $this->client->request('GET', '/admin/tags');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.post_tags', [], 'controller'), $response->getContent());
    }

    public function testTagyEdit()
    {
        $newName = 'Test tag';
        $tag = $this->em->getRepository(Tag::class)->findOneBy(['id' => 3]);
        $container = self::getContainer();
        $crawler = $this->client->request('GET', '/admin/tag/edit/'.$tag->getSlug());
        $response = $this->client->getResponse();
        $this->assertContains($container->get('translator')->trans('admin.title.edit_tag', [], 'controller'), $response->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_tag[name]'] = $newName;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.tag_edited', [], 'message'), $this->client->getResponse()->getContent());
//        $editTag = $this->em->getRepository(Tag::class)->findOneBy(['name' => $newName])->getName();
//        $this->assertEquals($newName, $editTag);
    }

    public function testTagCreate()
    {
        $name = 'New test tag';
        $container = self::getContainer();
        $crawler = $this->client->request('GET', '/admin/tag/create');
        $response = $this->client->getResponse();
        $this->assertContains($container->get('translator')->trans('admin.tag.create', [], 'controller'), $response->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_tag[name]'] = $name;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.tag_created', [], 'message'), $this->client->getResponse()->getContent());
        $newTag = $this->em->getRepository(Tag::class)->findOneBy(['name' => $name])->getName();
        $this->assertEquals($name, $newTag);
    }

    public function testTagyDelete()
    {
        $tag = $this->em->getRepository(Tag::class)->findOneBy(['id' => 1]);
        $container = self::getContainer();
        $this->client->request('POST', '/admin/tag/delete/'.$tag->getSlug());
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.tag_deleted', [], 'message'), $this->client->getResponse()->getContent());
    }
}