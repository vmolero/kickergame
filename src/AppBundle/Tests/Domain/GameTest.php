<?php

namespace AppBundle\Tests\Domain;


use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use AppBundle\Entity\User;

class GameTest extends DomainTestCase
{
    private $game;

    public function setUp()
    {
        $this->game = new Game();
        $this->game->setWhenPlayed(new \DateTime());
    }

    public function testHasNoConflictsVisitorConflict()
    {
        $local = new Team();
        $visitor = new Team();
        $local->setPlayer1(User::createPlayer()->setUsername('local1'));
        $local->setPlayer2(User::createPlayer()->setUsername('local2'));
        $visitor->setPlayer1(User::createPlayer()->setUsername('visitor1'));
        $visitor->setPlayer2(User::createPlayer()->setUsername('local1'));
        $this->game->setLocal($local);
        $this->game->setVisitor($visitor);
        $this->assertTrue($this->game->hasConflicts($local, $visitor));
    }
}

