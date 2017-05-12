<?php

namespace AppBundle\Domain\Action;


use AppBundle\Domain\Interfaces\KickerUserInterface;

/**
 * Class DashboardAction
 * @package AppBundle\Domain\Action
 */
class DashboardAction extends Action
{
    /**
     * @param KickerUserInterface $user
     * @return NullActionResponse
     */
    public function visitPlayer(KickerUserInterface $user)
    {
        return new NullActionResponse();
    }

    /**
     * @param KickerUserInterface $user
     * @return NullActionResponse
     */
    public function visitAdmin(KickerUserInterface $user)
    {
        return new NullActionResponse();
    }
}