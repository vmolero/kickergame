<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Game;
use AppBundle\Entity\Interfaces\TeamHolder;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamRepository extends EntityRepository
{
    public function fetchTeamsIfExist(UserInterface $player1, UserInterface $player2)
    {
        return $this->findOneBy(['player1' => $player1, 'player2' => $player2]);
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