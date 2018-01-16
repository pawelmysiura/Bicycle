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

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        $categoryList = [
            'inne' => 'Inne',
            'rowery' => 'rowery',
            'trasy' => 'Trasy',
            'nowe' => 'Nowe'
        ];

        foreach ($categoryList as $key => $name)
        {
            $cat = new \AppBundle\Entity\Category();
            $cat->setName($name);

            $this->addReference('category_'.$key, $cat);
            $manager->persist($cat);
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