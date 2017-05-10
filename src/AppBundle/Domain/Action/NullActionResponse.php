<?php

namespace AppBundle\Domain\Action;

class NullActionResponse
{
    /**
     * @return array
     */
    public function getParameters()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return [];
    }
}