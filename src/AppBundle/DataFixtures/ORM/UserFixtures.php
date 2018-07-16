<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 10:20
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserFixtures constructor.
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userList = [
            self::getUser('admin', '123', 'pawmastero@gmail.com', true, 'ROLE_SUPER_ADMIN', 'Adam', 'Kowalski'),
            self::getUser('Pawel', '123', 'testtesttestorlo@gmail.com', true, 'ROLE_ADMIN', 'Paweł', 'Nowak'),
            self::getUser('wsad', '123', 'acrposlka@gmail.com', true, 'ROLE_USER', 'Grzegorz', 'Brzęczyszczykiewicz'),
        ];

        $userManager = $this->container->get('fos_user.user_manager');
        foreach ($userList as $userDetails){
            $user = $userManager->createUser();
            $user->setUsername($userDetails['username']);
            $user->setPlainPassword($userDetails['password']);
            $user->setEmail($userDetails['email']);
            $user->setEnabled($userDetails['enable']);
            $user->setRoles([$userDetails['role']]);
            $user->setSurname($userDetails['lastName']);
            $user->setFirstName($userDetails['firstName']);


            $this->addReference('user_'.$userDetails['username'], $user);
            $userManager->updateUser($user, true);
        }
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

    public function getUser(string $username, string $password, string $email, $enable, string $role, string $firstName, string $lastName)
    {
        return[
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'enable' => $enable,
            'role' => $role,
            'firstName' => $firstName,
            'lastName' => $lastName
        ];
    }

}