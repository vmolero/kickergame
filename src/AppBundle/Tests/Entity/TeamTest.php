<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/6/17
 * Time: 9:36 AM
 */

namespace AppBundle\Test\Entity;

use AppBundle\Domain\Team;
use AppBundle\Domain\Users\Player;

class TeamTest extends \PHPUnit_Framework_TestCase
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
