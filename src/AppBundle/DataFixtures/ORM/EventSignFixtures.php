<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 09:02
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\EventSign;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EventSignFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $eventSignList = [
            self::setEventSign('Curabitur tristique neque metus, nec tristique diam bibendum ac. Donec.', 'admin', 'uniquecode123', 1, 2, '2018-06-04 17:21:05', null),
            self::setEventSign('Curabitur tristique neque metus, nec tristique diam bibendum ac. Donec.', 'Pawel', 'uniquecode456', 0, 0, '2018-06-30 12:45:21', null),
            self::setEventSign('Curabitur tristique neque metus, nec tristique diam bibendum ac. Donec.', 'wsad', 'uniquecode789', 0, 0, '2018-07-02 08:02:57', null),
            self::setEventSign('Mauris felis lacus, tempor quis vehicula.', 'Pawel', '123456789', 1, 2, '2018-05-28 12:31:14', 1),
            self::setEventSign('Mauris felis lacus, tempor quis vehicula.', 'admin', 'code123123123', 1, 0, '2018-06-12 22:32:54', 2),
            self::setEventSign('Mauris felis lacus, tempor quis vehicula.', 'wsad', 'code98765', 1, 0, '2018-06-14 18:11:23', 3),
            self::setEventSign('Pellentesque congue sem magna, ac ullamcorper augue blandit.', 'admin', '123456789', 1, 2, '2018-07-01 15:26:54', null),
            self::setEventSign('Phasellus nec enim odio. Morbi.', 'admin', 'wsad123', 1, 2, '2018-06-28 12:31:14', null),
        ];

        foreach ($eventSignList as $details){
            $eventSigm = new EventSign();
            $eventSigm->setEvent($this->getReference('event_'.$details['title']));
            $eventSigm->setUser($this->getReference('user_'.$details['user']));
            $eventSigm->setCode($details['code']);
            $eventSigm->setVerify($details['verify']);
            $eventSigm->setPermissions($details['permissions']);
            $eventSigm->setJoinDate(new \DateTime($details['joinDate']));
            $eventSigm->setStartNumber($details['startNumber']);

            $manager->persist($eventSigm);
        }
        $manager->flush();
    }
    public function getOrder()
    {
        return 3;
    }

    public function setEventSign(string $eventTitle, string $user, string $code, string $verify, int $permissions, string $joinDate, $startNumber)
    {
        return [
            'title' => $eventTitle,
            'user' => $user,
            'code' => $code,
            'verify' => $verify,
            'permissions' => $permissions,
            'joinDate' => $joinDate,
            'startNumber' => $startNumber
        ];
    }
}