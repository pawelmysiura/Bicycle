<?php

namespace Tests\AppBundle\Controller;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseControllerTest
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

   public function testEditAvatar()
   {
       $photo = new UploadedFile('/home/pawel/Obrazy/150x150avatar.png', 'photo.jpg', 'image/jpeg', 123);
       $container = self::$kernel->getContainer();
       $crawler = $this->client->request('GET', '/panel/user/avatar');
       $response = $this->client->getResponse();
       $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
       $this->assertContains($container->get('translator')->trans('user.avatar', [], 'form'), $response->getContent());
       $form = $crawler->selectButton($container->get('translator')->trans('submit', [], 'form'))->form();
       $form['avatar[imageFile]'] = $photo;
       $this->client->submit($form);
       $this->client->followRedirect();
       $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
       $this->assertContains($container->get('translator')->trans('flashmsg.success.user_avatar', [], 'message'), $this->client->getResponse()->getContent());

   }
}