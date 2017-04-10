<?php

namespace AppBundle\Domain;

use AppBundle\Entity\Team as TeamModel;
use FOS\UserBundle\Model\UserInterface;


class Team extends TeamModel
{
    public function __construct(UserInterface $player1 = null, UserInterface $player2 = null)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function getPlayer1()
    {
        return $this->player1;
    }

    public function getPlayer2()
    {
        return $this->player2;
    }
}