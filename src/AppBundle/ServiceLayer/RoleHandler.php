<?php

namespace AppBundle\ServiceLayer;

use AppBundle\Domain\Action\Action;
use AppBundle\Domain\Admin;
use AppBundle\Domain\Player;
use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class RoleHandler
 * @package AppBundle\ServiceLayer
 */
class RoleHandler implements RoleInterface
{
    /**
     * @var Admin|Player
     */
    private $user;
    /**
     * @var ActionResponse
     */
    private $actionResponse;

    /**
     * RoleHandler constructor.
     * @param AuthorizationCheckerInterface $checker
     * @param TokenStorageInterface $storage
     */
    public function __construct(AuthorizationCheckerInterface $checker, TokenStorageInterface $storage)
    {
        $user = new Player($storage->getToken()->getUser());
        $checker->isGranted(Role::ADMIN) && ($user = new Admin($user));
        $this->user = $user;
    }

    /**
     * @param Action $action
     * @return $this
     */
    public function handle(Action $action)
    {
        $this->actionResponse = $action->visit($this->user);
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleTemplateParameter()
    {
        return strtolower(substr($this->getRole(), 5));
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->user->getRole();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->actionResponse->getMessages();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->actionResponse->getParameters();
    }

}