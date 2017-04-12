<?php
/**
 * Created by PhpStorm.
 * User: vmolero
 * Date: 4/12/17
 * Time: 10:16 AM
 */

namespace AppBundle\Entity\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;


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
     * @param mixed $player1
     * @return Team
     */
    public function setPlayer1(UserInterface $player1);

    /**
     * @return mixed
     */
    public function getPlayer2();

    /**
     * @param mixed $player2
     * @return Team
     */
    public function setPlayer2(UserInterface $player2);
}