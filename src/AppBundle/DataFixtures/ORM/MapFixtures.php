<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 09:02
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Map;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MapFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $mapList = [
            [
            'name' => 'Quisque condimentum tellus vel ultrices.',
            'description' => 'Nullam vitae convallis risus. Ut dignissim lacus feugiat massa accumsan pretium. Suspendisse euismod condimentum nunc, nec molestie elit convallis ut. Nulla vitae tincidunt sem. Nunc vel lorem sed velit molestie semper et eu libero. Etiam auctor, magna id mollis accumsan, justo magna sodales odio, non vestibulum libero lectus et eros. Cras id eros volutpat, luctus dolor non, semper nunc. Vestibulum suscipit lectus eu purus gravida egestas. Integer commodo mi ac lectus feugiat porta. Mauris gravida posuere lectus, ut elementum magna mollis eu. Phasellus volutpat, nunc sit amet scelerisque scelerisque, sem tortor porttitor nisl, et commodo lacus orci vitae ante.',
            'author' => 'admin',
            'start' => '{"lat":49.9672344,"lng":18.861218000000008}',
            'end' => '{"lat":49.9679214,"lng":19.783434400000033}',
            'waypoints' => '{"0":{"lat":50.0392603,"lng":19.138980400000037},"1":{"lat":49.9836863,"lng":19.789433700000018}}',
                'createDate' => '2018-01-28 12:31:14'
        ],
            [
                'name' => 'Quisque eget aliquam.',
                'description' => 'Aenean porta ultricies elit, nec rutrum enim porttitor ut. Quisque eget leo accumsan massa vestibulum placerat a ac enim. Proin ornare venenatis lacus varius rutrum. Integer sodales dolor magna, quis sollicitudin tellus tempor vitae. Duis pellentesque nulla in ligula dapibus ullamcorper. Nam lectus mauris, imperdiet at ante vulputate, hendrerit posuere felis. Integer faucibus feugiat nisi, in facilisis enim feugiat id. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut ex tortor, gravida in odio ut, convallis commodo est. Vestibulum quis nisi at elit sollicitudin blandit. Fusce imperdiet leo luctus semper placerat. Maecenas mollis justo at lectus rutrum cursus. Proin diam diam, accumsan vel euismod quis, molestie aliquam risus. Pellentesque nibh urna, faucibus vel facilisis a, bibendum ac purus. Aliquam fringilla placerat nisi, sed aliquet est varius et. Proin porta et mauris non imperdiet.',
                'author' => 'wsad',
                'start' => '{"lat":50.04567,"lng":19.940220100000033}',
                'end' => '{"lat":50.0338467,"lng":19.216779299999985}',
                'waypoints' => '{"0":{"lat":50.00452560000001,"lng":19.571619000000055}}',
                'createDate' => '2018-02-28 18:11:15'
            ],
            [
                'name' => 'Etiam in enim malesuada.',
                'description' => 'Morbi viverra ipsum sit amet facilisis condimentum. Fusce a nunc vulputate ligula ullamcorper porta a ac tortor. Duis porta odio a lectus convallis, dapibus elementum elit fringilla. Donec a tellus quis eros posuere fringilla. Praesent ut felis vitae nisl facilisis iaculis. Integer non risus justo. In hac habitasse platea dictumst.',
                'author' => 'Pawel',
                'start' => '{"lat":50.0503724,"lng":19.95018749999997}',
                'end' => '{"lat":50.1280285,"lng":19.37431379999998}',
                'waypoints' => '{"0":{"lat":50.0519814,"lng":19.784151299999962},"1":{"lat":50.1197576,"lng":19.543959299999983}}',
                'createDate' => '2018-02-29 12:41:14'
            ],
            ];

        foreach ($mapList as $key => $details){
            $map = new Map(null);
            $map->setName($details['name']);
            $map->setDescription($details['description']);
            $map->setAuthor($this->getReference('user_'.$details['author']));
            $map->setStart($details['start']);
            $map->setEnd($details['end']);
            $map->setWaypoints($details['waypoints']);
            $map->setCreateDate(new \DateTime($details['createDate']));
            $manager->persist($map);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}