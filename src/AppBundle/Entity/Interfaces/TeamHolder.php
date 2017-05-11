<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/12/17
 * Time: 10:16 AM
 */

namespace AppBundle\Entity\Interfaces;

use AppBundle\Domain\Interfaces\KickerUserInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="tvg_team")
 */
interface TeamHolder
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     * @return Team
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getPlayer1();

    /**
     * @param KickerUserInterface $player1
     * @return Team
     */
    public function setPlayer1(KickerUserInterface $player1);

    /**
     * @return mixed
     */
    public function getPlayer2();

    /**
     * @param KickerUserInterface $player2
     * @return Team
     */
    public function setPlayer2(KickerUserInterface $player2);

    /**
     * @param KickerUserInterface $player
     * @return boolean
     */
    public function hasPlayer(KickerUserInterface $player);
}