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
        $visitorTeam = isset($data['visitor']) ? $data['visitor'] :new Team($visitor1, $visitor2);
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
        // but, the original `$task` variable has also been updated
        // $task = $form->getData();

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!



        // return $this->redirectToRoute('task_success');
    }
}