<?php
namespace AppBundle\Domain\Interfaces;

use FOS\UserBundle\Model\UserInterface;

interface KickerUserInterface extends UserInterface
{
    public function getEntity();
}