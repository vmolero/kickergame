<?php

namespace AppBundle\Domain;

use AppBundle\Entity\Role;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class Admin
 * @package AppBundle\Domain
 */
class Admin extends RoleUser
{

    /**
     * Admin constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        parent::__construct($user);
        $this->setRole(Role::create(Role::ADMIN));
    }
}