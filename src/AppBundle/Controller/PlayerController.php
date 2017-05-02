<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayerController
 * @package AppBundle\Controller
 */
class PlayerController extends Controller
{
    /**
     * @Security("has_role('ROLE_PLAYER')")
     * @Route("/players/", name="players")
     */
    public function playersAction(Request $request)
    {
        $handler = $this->get('app.role_handler');

        return $handler->handle(
            'players',
            $request,
            [
                'userRepository' => $this->getDoctrine()
                    ->getRepository(User::REPOSITORY),
            ]
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/players/new/", name="newPlayers")

    public function newPlayerAction(Request $request)
    {
        $handler = $this->get('app.role_handler');

        return $handler->handle(
            'newPlayer',
            $request,
            [
                'formFactory' => $this->get('form.factory'),
                'translator' => $this->get('translator'),
                // 'formHandler' => $this->container->get('fos_user.registration.form.handler'),
            ]
        );
    }*/
}