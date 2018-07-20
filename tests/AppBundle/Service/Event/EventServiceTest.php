<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.07.18
 * Time: 13:10
 */

namespace Tests\AppBundle\Service\Event;


use AppBundle\Entity\Event;
use AppBundle\Entity\EventSign;
use AppBundle\Entity\User;
use AppBundle\Service\Event\EventService;
use AppBundle\Service\Event\Generator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

class EventServiceTest extends WebTestCase
{

    private $doctrine;

    private $tcpdf;

    private $generator;

    public function setUp()
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->tcpdf = $this->createMock(TCPDFController::class);
        $this->generator = $this->createMock(Generator::class);
    }
    protected function getEventService()
    {

        $eventService = new EventService($this->doctrine, $this->tcpdf, $this->generator);
        return $eventService;
    }

    public function testApplyEventFail()
    {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('123');
        $event = new Event();
        $event->setTitle('Test title');
        $event->setAuthor(1);
        $event->setDescription('test test');
        $eventSign = $this->createMock(ObjectRepository::class);
        $eventSign->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $this->doctrine->expects($this->once())
            ->method('getRepository')
            ->willReturn($eventSign);
        $eventService = $this->getEventService()->applyEvent($user, $event, 0, 0);
        $this->assertFalse($eventService);
    }

    /**
     * @group time-sensitive
     */
    public function testApplyEventPass()
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
        $contestant = new EventSign();
        $contestant->setEvent($event);
        $contestant->setUser($user);
        $contestant->setCode('ASD123');
        $contestant->setVerify(0);
        $contestant->setPermissions(0);
        $contestant->setJoinDate(\DateTime::createFromFormat('U', time()));
        $eventSign = $this->createMock(ObjectRepository::class);
        $eventSign->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $this->doctrine->expects($this->once())
            ->method('getRepository')
            ->willReturn($eventSign);
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())
            ->method('persist')
            ->with($contestant);
        $this->doctrine
            ->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);

        $this->generator->expects($this->once())
            ->method('codeGenerator')
            ->willReturn('ASD123');
        $eventService = $this->getEventService()->applyEvent($user, $event, 0, 0);
        $this->assertTrue($eventService);
    }

    public function testChangeNumberSwap()
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
        $contestant = new EventSign();
        $contestant->setEvent($event);
        $contestant->setUser($user);
        $contestant->setCode('123123123');
        $contestant->setVerify(null);
        $contestant->setPermissions(0);
        $contestant->setJoinDate(\DateTime::createFromFormat('U', time()));
        $contestant->setStartNumber(2);

        $contestant2 = new EventSign();
        $contestant2->setEvent($event);
        $contestant2->setUser($user);
        $contestant2->setCode('123123123');
        $contestant2->setVerify(null);
        $contestant2->setPermissions(0);
        $contestant2->setJoinDate(\DateTime::createFromFormat('U', time()));
        $contestant2->setStartNumber(1);


        $signRepo= $this->createMock(ObjectRepository::class);
        $signRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($contestant);
        $signRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($contestant2);
        $this->doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($signRepo);
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('persist')
            ->with($contestant2);
        $objectManager->expects($this->any())
            ->method('persist')
            ->with($contestant);
        $this->doctrine
            ->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);
        $test = $this->getEventService()->changeNumber(['startNumber' => 1, 'changeNumber' => 2], $event, $user);
        $this->assertEquals(1, $test->getStartNumber());
    }

    public function testChangeNumberNoSwap()
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
        $contestant = new EventSign();
        $contestant->setEvent($event);
        $contestant->setUser($user);
        $contestant->setCode('123123123');
        $contestant->setVerify(null);
        $contestant->setPermissions(0);
        $contestant->setJoinDate(\DateTime::createFromFormat('U', time()));
        $contestant->setStartNumber(2);

        $signRepo= $this->createMock(ObjectRepository::class);
        $signRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($contestant);

        $this->doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($signRepo);
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('persist')
            ->with($contestant);
        $this->doctrine
            ->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);
        $test = $this->getEventService()->changeNumber(['startNumber' => 1, 'changeNumber' => 2], $event, $user);
        $this->assertEquals(1, $test->getStartNumber());
    }

    public function testIsUserAlreadySignPass()
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

        $signRepo= $this->createMock(ObjectRepository::class);
        $signRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);
        $this->doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($signRepo);

        $test = $this->getEventService()->isUserAlreadySign($user, $event);
        $this->assertEquals(false, $test);
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testIsUserAlreadySignException()
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
        $contestant = new EventSign();
        $contestant->setEvent($event);
        $contestant->setUser($user);
        $contestant->setCode('123123123');
        $contestant->setVerify(null);
        $contestant->setPermissions(0);
        $contestant->setJoinDate(\DateTime::createFromFormat('U', time()));
        $contestant->setStartNumber(2);

        $signRepo= $this->createMock(ObjectRepository::class);
        $signRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($contestant);
        $this->doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($signRepo);
        $this->getEventService()->isUserAlreadySign($user, $event);
    }
}