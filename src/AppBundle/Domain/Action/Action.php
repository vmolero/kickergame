<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Role;
use AppBundle\RoleHandler\RoleHandler;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class Action
 * @package AppBundle\Domain\Action
 */
abstract class Action
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Action constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param RoleInterface $user
     * @return NullActionResponse
     */
    public function visit(RoleInterface $user)
    {
        if (!$user instanceof KickerUserInterface) {
            throw new LogicException('Object must implement UserInterface');
        }
        $result = new NullActionResponse();
        switch ($user->getRole()) {
            case Role::ADMIN:
                $result = $this->visitAdmin($user);
                break;
            case Role::PLAYER:
                $result = $this->visitPlayer($user);
                break;
        }

        return $result;
    }
}