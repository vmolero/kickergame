<?php

namespace AppBundle\Entity;

use AppBundle\Domain\Interfaces\KickerUserInterface;
use AppBundle\Entity\Interfaces\TeamHolder;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamRepository")
 * @ORM\Table(name="team",
 *            indexes={@ORM\Index(name="team_idx2", columns={"player1", "player2"})},
 *            uniqueConstraints={@ORM\UniqueConstraint(name="unique_player1_player2_1", columns={"player1", "player2"})}
 *           )
 */
class Team implements TeamHolder
{
    const REPOSITORY = 'AppBundle:Team';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="name", nullable=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id", cascade={"persist"})
     * @ORM\JoinColumn(name="player1", referencedColumnName="id")
     *
     * @var KickerUserInterface
     */
    protected $player1;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id", cascade={"persist"})
     * @ORM\JoinColumn(name="player2", referencedColumnName="id")
     *
     * @var KickerUserInterface
     */
    protected $player2;

    /**
     * Team constructor.
     * @param KickerUserInterface|null $player1
     * @param KickerUserInterface|null $player2
     */
    public function __construct(KickerUserInterface $player1 = null, KickerUserInterface $player2 = null)
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
     * @return string
     */
    public function getName()
    {
        if (null === $this->name) {
            return $this->player1->getUsername().'+'.$this->player2->getUsername();
        }

        return $this->name;
    }

    /**
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @param KickerUserInterface $player1
     * @return Team
     */
    public function setPlayer1(KickerUserInterface $player1)
    {
        $this->player1 = $player1;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @param KickerUserInterface $player2
     * @return Team
     */
    public function setPlayer2(KickerUserInterface $player2)
    {
        $this->player2 = $player2;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasConflicts()
    {
        return $this->player1->getEmail() === $this->player2->getEmail();
    }

    /**
     * @param KickerUserInterface $player
     * @return bool
     */
    public function hasPlayer(KickerUserInterface $player)
    {
        return $player->getEmail() === $this->player1->getEmail() ||
            $player->getEmail() === $this->player2->getEmail();
    }
}