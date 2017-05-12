<?php

namespace AppBundle\Repository;

use AppBundle\Domain\Admin;
use AppBundle\Domain\Player;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $this->toCollection($qb->getQuery()->getResult());
    }

    public function toCollection(array $objects)
    {
        $collection = [];
        /** @var User $object */
        foreach ($objects as $object) {
            $user = new Player($object);
            $object->hasRole(Role::ADMIN) && ($user = new Admin($user));
            $collection[] = $user;
        }

        return $collection;
    }

    public function findAll()
    {
        return $this->toCollection(parent::findAll());
    }
}