<?php

namespace AppBundle\Repository;

use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Game;
use AppBundle\Entity\Interfaces\TeamHolder;
use Doctrine\ORM\EntityRepository;


class TeamRepository extends EntityRepository
{
    public function fetchTeamsIfExist(KickerUserInterface $player1, KickerUserInterface $player2)
    {
        return $this->findOneBy(['player1' => $player1->getEntity(), 'player2' => $player2->getEntity()]);
    }

    /**
     * @param Game $game
     * @return Game
     */
    public function useExistingTeamsFor(Game &$game) {
        $game->setLocal($this->getExistingTeam($game->getLocal()));
        $game->setVisitor($this->getExistingTeam($game->getVisitor()));
        return $game;
    }

    /**
     * @param TeamHolder $team
     * @return TeamHolder|null|object
     */
    public function getExistingTeam(TeamHolder $team) {
        $aTeam = $this->fetchTeamsIfExist(
            $team->getPlayer1(),
            $team->getPlayer2()
        );

        $aTeam instanceof TeamHolder && ($team = $aTeam);
        return $team;
    }
}