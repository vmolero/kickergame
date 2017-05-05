<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Game;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function findAllTheGamesByPlayer($playerId)
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('game')
            ->from('AppBundle\Entity\Game', 'game')
            ->innerJoin('game.local', 'localTeam')
            ->innerJoin('game.visitor', 'visitorTeam')
            ->innerJoin('localTeam.player1', 'localPlayer1')
            ->innerJoin('visitorTeam.player1', 'visitorPlayer1')
            ->innerJoin('localTeam.player2', 'localPlayer2')
            ->innerJoin('visitorTeam.player2', 'visitorPlayer2')
            ->where('localPlayer1.id = :id')
            ->orWhere('visitorPlayer1 = :id')
            ->orWhere('localPlayer2.id = :id')
            ->orWhere('visitorPlayer2 = :id')
            ->setParameter('id', $playerId);

        return $query->getQuery()->getResult();
    }

    public function save(Game $game)
    {
        $this->_em->persist($game);
        return $this->_em->flush();
    }
}