<?php

namespace AppBundle\Domain\Action;


use AppBundle\Entity\Role;
use LogicException;
use AppBundle\RoleHandler\RoleHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\RoleInterface;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class Action
 * @package AppBundle\Domain\Action
 */
abstract class Action
{
    /**
     * @var null|Request
     */
    protected $request = null;

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
        if (!$user instanceof UserInterface) {
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