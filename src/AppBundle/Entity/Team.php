<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\TeamHolder;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="team",
 *            indexes={@ORM\Index(name="team_idx2", columns={"player1", "player2"})},
 *            uniqueConstraints={@ORM\UniqueConstraint(name="unique_player1_player2_1", columns={"player1", "player2"})}
 *           )
 */
class Team implements TeamHolder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="player1", referencedColumnName="id")
     */
    protected $player1;
    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="player2", referencedColumnName="id")
     */
    protected $player2;

    public function __construct(UserInterface $player1 = null, UserInterface $player2 = null)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Team
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @param mixed $player1
     * @return Team
     */
    public function setPlayer1(UserInterface $player1)
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @param mixed $player2
     * @return Team
     */
    public function setPlayer2(UserInterface $player2)
    {
        $this->player2 = $player2;

        return $this;
    }

    public function hasConflicts()
    {
        return $this->player1 === $this->player2;
    }
}