<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 10:48
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        $tagList = [
            'Qua',
            'Fugiunt',
            'Palus',
            'Hafnia',
            'Amicitia',
            'Hilotaes',
            'Berolinum',
            'Cirpi',
            'Adgium',
            'Pes',
            'Heuretess'
        ];

        foreach ($tagList as $key => $name)
        {
            $tag = new \AppBundle\Entity\Tag();
            $tag->setName($name);

            $this->addReference('tag_'.$name, $tag);
            $manager->persist($tag);
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
        return 1;
    }

}