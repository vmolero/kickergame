<?php
namespace AppBundle\Domain;

use AppBundle\Entity\Role;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class Player
 * @package AppBundle\Domain
 */
class Player extends RoleUser
{

    /**
     * Player constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        parent::__construct($user);
        $this->setRole(Role::create(Role::PLAYER));
    }
}