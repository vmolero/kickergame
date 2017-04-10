<?php

namespace AppBundle\Tests\Domain;

use AppBundle\Domain\Team;
use AppBundle\Domain\Users\Player;

class TeamTest extends DomainTestCase
{
    public function testCreateTeam()
    {
        $player1 = new Player();
        $player1->setFullname('Pepito palotes');
        $player2 = new Player();
        $player2->setFullname('Pepito grillo');


        $team = new Team($player1, $player2);

        $this->assertEquals($player1, $team->getPlayer1());
        $this->assertEquals($player2, $team->getPlayer2());
    }
}
