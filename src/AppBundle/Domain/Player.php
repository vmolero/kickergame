<?php
namespace AppBundle\Domain;

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
        $this->setRole(RoleUser::PLAYER);
    }
}