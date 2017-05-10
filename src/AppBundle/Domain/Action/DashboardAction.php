<?php

namespace AppBundle\Domain\Action;


use FOS\UserBundle\Model\UserInterface;

/**
 * Class DashboardAction
 * @package AppBundle\Domain\Action
 */
class DashboardAction extends Action
{
    /**
     * @param UserInterface $user
     * @return NullActionResponse
     */
    public function visitPlayer(UserInterface $user)
    {
        return new NullActionResponse();
    }

    /**
     * @param UserInterface $user
     * @return NullActionResponse
     */
    public function visitAdmin(UserInterface $user)
    {
        return new NullActionResponse();
    }
}