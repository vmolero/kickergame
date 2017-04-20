<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function saveUsingFormData(array $data)
    {
        $local1 = $data['player1'];
        $local2 = $data['player2'];
        $visitor1 = $data['player3'];
        $visitor2 = $data['player4'];
        $game = new Game();
        $localTeam = isset($data['local']) ? $data['local'] : new Team($local1, $local2);
        $visitorTeam = isset($data['visitor']) ? $data['visitor'] : new Team($visitor1, $visitor2);
        $localTeam->setName($local1->getUsername().'-'.$local2->getUsername());
        $visitorTeam->setName($visitor1->getUsername().'-'.$visitor2->getUsername());
        if (is_numeric($data['localScore']) && is_numeric($data['visitorScore'])) {
            $game->setScoreLocal(intval($data['localScore']));
            $game->setScoreVisitor(intval($data['visitorScore']));
        }
        if (!$game->hasConflicts($localTeam, $visitorTeam)) {
            $game->setLocal($localTeam)->setVisitor($visitorTeam);
            /** @var \DateTime $date */
            $date = $data['when'];
            $game->setWhenPlayed($date);
            $this->_em->persist($game);
            $this->_em->flush();
        }
    }

    public function findAllGamesByPlayer($playerId)
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
}