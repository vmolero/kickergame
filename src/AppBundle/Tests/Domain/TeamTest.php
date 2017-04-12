<?php

namespace AppBundle\Tests\Domain;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;

class TeamTest extends DomainTestCase
{
    public function testCreateTeam()
    {
        $player1 = User::createPlayer();
        $player1->setFullname('Pepito palotes');
        $player2 = User::createPlayer();
        $player2->setFullname('Pepito grillo');


        $team = new Team($player1, $player2);

        $this->assertEquals($player1, $team->getPlayer1());
        $this->assertEquals($player2, $team->getPlayer2());
    }
}
