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
        $localTeam = $this->fetchTeamsIfExist(
            $game->getLocal()->getPlayer1(),
            $game->getLocal()->getPlayer2()
        );
        $visitorTeam = $this->fetchTeamsIfExist(
            $game->getVisitor()->getPlayer1(),
            $game->getVisitor()->getPlayer2()
        );
        $localTeam instanceof TeamHolder && $game->setLocal($localTeam);
        $visitorTeam instanceof TeamHolder && $game->setVisitor($visitorTeam);
        return $game;
    }
}