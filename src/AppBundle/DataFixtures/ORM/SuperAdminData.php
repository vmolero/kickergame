<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class SuperAdminData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('1234');
        $userAdmin->addRole(Role::ADMIN);

        $manager->persist($userAdmin);
        $manager->flush();
    }
}