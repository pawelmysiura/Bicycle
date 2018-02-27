<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;

class AdminCategoryControllerTest extends BaseControllerTest
{

    public function getFixtures()
    {
        return [
            'AppBundle\DataFixtures\ORM\CategoryFixtures',
            'AppBundle\DataFixtures\ORM\UserFixtures'
        ];
    }

    public function testCategoryList()
    {
        $container = self::getContainer();
        $this->client->request('GET', '/admin/categories');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans('admin.title.post_categories', [], 'controller'), $response->getContent());
    }

    public function testCategoryEdit()
    {
        $newName = 'Test category';
        $category = $this->em->getRepository(Category::class)->findOneBy(['id' => 3]);
        $container = self::getContainer();
        $crawler = $this->client->request('GET', '/admin/category/edit/'.$category->getSlug());
        $response = $this->client->getResponse();
        $this->assertContains($container->get('translator')->trans('admin.title.edit_category', [], 'controller'), $response->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_category[name]'] = $newName;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.category_edited', [], 'message'), $this->client->getResponse()->getContent());
//        $editedCategory = $this->em->getRepository(Category::class)->findOneBy(['name' => $newName])->getName();
//        $this->assertEquals($newName, $editedCategory);
    }

    public function testCategoryCreate()
    {
        $name = 'New test category';
        $container = self::getContainer();
        $crawler = $this->client->request('GET', '/admin/category/create');
        $response = $this->client->getResponse();
        $this->assertContains($container->get('translator')->trans('admin.category.create', [], 'controller'), $response->getContent());
        $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
        $form['create_category[name]'] = $name;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.category_created', [], 'message'), $this->client->getResponse()->getContent());
        $newCategory = $this->em->getRepository(Category::class)->findOneBy(['name' => $name])->getName();
        $this->assertEquals($name, $newCategory);
    }

    public function testCategoryDelete()
    {
        $category = $this->em->getRepository(Category::class)->findOneBy(['id' => 1]);
        $container = self::getContainer();
        $this->client->request('POST', '/admin/category/delete/'.$category->getSlug());
        $response = $this->client->getResponse();
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains($container->get('translator')->trans('flashmsg.success.admin.category_deleted', [], 'message'), $this->client->getResponse()->getContent());
    }
}