<?php

namespace AppBundle\RoleHandler;

use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class RoleHandlerFactory
{
    public static function create(
        AuthorizationCheckerInterface $checker
    ) {
        $i = null;
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