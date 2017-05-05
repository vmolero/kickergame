<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayerController
 * @package AppBundle\Controller
 */
class PlayerController extends KickerController
{
    /**
     * @CFG\Security("has_role('ROLE_PLAYER')")
     * @CFG\Route("/players/", name="players")
     */
    public function playersAction(Request $request)
    {
        /* @var $handler RoleHandler  */
        $handler = $this->get('app.role_handler');
        $data = $handler->handle('players',
            $request,
            [
                'userRepository' => $this->getDoctrine()
                    ->getRepository(User::REPOSITORY),
            ]);
        return $this->buildResponse($data);
    }
}