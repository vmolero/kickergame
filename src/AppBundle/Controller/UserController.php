<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Action\DisplayUsersAction;
use AppBundle\Entity\User;
use AppBundle\ServiceLayer\RenderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayerController
 * @package AppBundle\Controller
 */
class UserController extends Controller
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
            )
        );

        /* @var $render RenderService  */
        $render = $this->get('app.render');

        return $render->setTemplate($this->getParameter('template.players'))->buildResponse($handler);
    }
}