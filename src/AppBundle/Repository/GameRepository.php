<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }

    public function saveUsingFormData(array $data)
    {
        $local1 = $data['player1'];
        $local2 = $data['player2'];
        $visitor1 = $data['player3'];
        $visitor2 = $data['player4'];
        $game = new Game();
        $localTeam = new Team($local1, $local2);
        $visitorTeam = new Team($visitor1, $visitor2);
        $localTeam->setName($local1->getUsername().'-'.$local2->getUsername());
        $visitorTeam->setName($visitor1->getUsername().'-'.$visitor2->getUsername());
        if (true && $game->hasConflicts($localTeam, $visitorTeam)) {
            $game->setLocal($localTeam)->setVisitor($visitorTeam);
            $game->setWhenPlayed($data['when']['date']);
        }
        // but, the original `$task` variable has also been updated
        // $task = $form->getData();

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!

        $this->_em->persist($game);
        $this->_em->flush();

        // return $this->redirectToRoute('task_success');
    }
}