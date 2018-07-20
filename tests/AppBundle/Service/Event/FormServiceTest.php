<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.07.18
 * Time: 13:10
 */

namespace Tests\AppBundle\Service\Event;


use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Service\Event\EventService;
use AppBundle\Service\Event\FormService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class FormServiceTest extends WebTestCase
{

    private $doctrine;

    private $eventService;


    public function setUp()
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->eventService = $this->createMock(EventService::class);
    }
    protected function getFormService()
    {

        $formService = new FormService($this->doctrine, $this->eventService);
        return $formService;
    }

    public function testSubmitCreateFormPass()
    {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('123');
        $user->setFirstName('Tester');
        $user->setSurname('Test');
        $event = new Event();
        $event->setTitle('Test title');
        $event->setAuthor(1);
        $event->setDescription('test test');
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('persist')
            ->with($event);
        $this->doctrine->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);
        $this->eventService->expects($this->once())
            ->method('applyEvent')
            ->willReturn(null);
        $test = $this->getFormService()->submitCreateForm($user, $event);
        $this->assertEquals('success', $test);
    }
}