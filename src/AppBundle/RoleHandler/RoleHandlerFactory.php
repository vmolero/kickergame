<?php

namespace AppBundle\RoleHandler;

use AppBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;


class RoleHandlerFactory
{
    public static function create(
        AuthorizationCheckerInterface $checker,
        EngineInterface $templating,
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $i = null;
        switch (true) {
            case $checker->isGranted(Role::ADMIN):
                $i = new AdminHandler($templating, $session, $translator);
                break;
            case $checker->isGranted(Role::PLAYER):
                $i = new PlayerHandler($templating, $session, $translator);
                break;
        }

        return $i;
    }
}