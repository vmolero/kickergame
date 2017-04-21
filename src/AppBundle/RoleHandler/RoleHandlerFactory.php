<?php

namespace AppBundle\RoleHandler;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use AppBundle\Entity\Role;


class RoleHandlerFactory
{
    public static function create(AuthorizationCheckerInterface $checker)
    {
        switch (true) {
            case $checker->isGranted(Role::ADMIN):
                $i = new AdminHandler();
                break;
            case $checker->isGranted(Role::PLAYER):
                $i = new PlayerHandler();
                break;
        }

        return $i;
    }
}