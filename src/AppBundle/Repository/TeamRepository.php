<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Game;
use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamRepository extends EntityRepository
{
    public function fetchTeamsIfExist(UserInterface $player1, UserInterface $player2)
    {
        return $this->findOneBy(['player1' => $player1, 'player2' => $player2]);
    }
}