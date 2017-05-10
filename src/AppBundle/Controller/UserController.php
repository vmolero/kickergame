<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Action\DisplayUsersAction;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayerController
 * @package AppBundle\Controller
 */
class UserController extends KickerController
{
    /**
     * @CFG\Security("has_role('ROLE_PLAYER')")
     * @CFG\Route("/players/", name="players")
     */
    public function playersAction(Request $request)
    {
        /* @var $handler RoleHandler */
        $handler = $this->get('app.role_handler');
        $handler->handle(
            new DisplayUsersAction(
                $request, $this->getDoctrine()
                ->getRepository(User::REPOSITORY)
            ),
            $this->getParameter('template.players')
        );

        return $this->buildResponse($handler);
    }
}