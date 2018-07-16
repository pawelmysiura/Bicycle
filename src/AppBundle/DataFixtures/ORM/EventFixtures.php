<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 09:02
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Event;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $eventList = [
            [
                'title' => 'Phasellus nec enim odio. Morbi.',
                'description' => 'Proin eu volutpat urna, a accumsan nibh. Maecenas felis justo, dapibus id sem eget, tempus ultricies ipsum. Suspendisse quis pretium felis. Mauris luctus massa et urna porttitor egestas. Vivamus cursus placerat risus, ac semper justo tempus in. Mauris ultricies urna a libero semper, quis iaculis risus accumsan. Etiam auctor nunc.',
                'author' => 'admin',
                'start' => '{"lat":49.9672344,"lng":18.861218000000008}',
                'end' => '{"lat":49.9679214,"lng":19.783434400000033}',
                'waypoints' => '{"0":{"lat":50.0392603,"lng":19.138980400000037},"1":{"lat":49.9836863,"lng":19.789433700000018}}',
                'createDate' => '2018-06-28 12:31:14',
                'endDateOfRegistration' => '2018-07-28 12:31:14',
                'startDate' => '2018-07-31 12:00:00'
            ],
            [
                'title' => 'Pellentesque congue sem magna, ac ullamcorper augue blandit.',
                'description' => 'Pellentesque vulputate dolor in diam ullamcorper, eu convallis elit dignissim. Pellentesque convallis eget purus facilisis congue. Sed bibendum pretium urna sit amet fringilla.',
                'author' => 'admin',
                'start' => '{"lat":50.04567,"lng":19.940220100000033}',
                'end' => '{"lat":50.0338467,"lng":19.216779299999985}',
                'waypoints' => '{"0":{"lat":50.00452560000001,"lng":19.571619000000055}}',
                'createDate' => '2018-07-01 15:26:54',
                'endDateOfRegistration' => '2018-08-31 15:00:00',
                'startDate' => '2018-09-18 12:00:00'
            ],
            [
                'title' => 'Mauris felis lacus, tempor quis vehicula.',
                'description' => 'Sed vel dignissim est. Mauris a libero eu leo faucibus accumsan in ac sem. Pellentesque rutrum justo et enim tincidunt, quis semper felis mattis. Praesent non consequat eros. Mauris nibh nunc, convallis sed nulla pretium, varius vestibulum leo. Vestibulum eget ultricies dui. Proin a mi mauris. Nulla mattis tortor massa.',
                'author' => 'Pawel',
                'start' => '{"lat":50.0503724,"lng":19.95018749999997}',
                'end' => '{"lat":50.1280285,"lng":19.37431379999998}',
                'waypoints' => '{"0":{"lat":50.0519814,"lng":19.784151299999962},"1":{"lat":50.1197576,"lng":19.543959299999983}}',
                'createDate' => '2018-05-28 12:31:14',
                'endDateOfRegistration' => '2018-06-20 12:31:14',
                'startDate' => '2018-06-28 18:00:00'
            ],
            [
                'title' => 'Curabitur tristique neque metus, nec tristique diam bibendum ac. Donec.',
                'description' => 'Duis a felis a felis volutpat ornare quis at ligula. Sed eu massa quis nibh sagittis commodo vel a nulla. Sed sit amet metus sapien. Etiam porttitor varius ipsum ac dignissim. Quisque interdum mauris mauris, et semper urna rutrum a. Integer eget eros hendrerit magna interdum malesuada sed non erat. Quisque vel purus hendrerit, consectetur augue eget, fringilla lectus. Sed non arcu metus. Quisque auctor enim ac felis sollicitudin sagittis.',
                'author' => 'admin',
                'start' => '{"lat":49.9672344,"lng":18.861218000000008}',
                'end' => '{"lat":49.9679214,"lng":19.783434400000033}',
                'waypoints' => '{"0":{"lat":50.0392603,"lng":19.138980400000037},"1":{"lat":49.9836863,"lng":19.789433700000018}}',
                'createDate' => '2018-06-04 17:21:05',
                'endDateOfRegistration' => '2018-10-25 12:00:00',
                'startDate' => '2018-11-04 11:30:00'
            ]
        ];

        foreach ($eventList as $key => $details){
            $event = new Event();
            $event->setTitle($details['title']);
            $event->setDescription($details['description']);
            $event->setAuthor($this->getReference('user_'.$details['author']));
            $event->setStart($details['start']);
            $event->setEnd($details['end']);
            $event->setWaypoints($details['waypoints']);
            $event->setCreateDate(new \DateTime($details['createDate']));
            $event->setEndDateOfRegistration(new \DateTime($details['endDateOfRegistration']));
            $event->setEventDate(new \DateTime($details['startDate']));
            $manager->persist($event);
            $this->addReference('event_'.$details['title'], $event);
        }
        $manager->flush();

    }
    public function getOrder()
    {
        return 2;
    }
}