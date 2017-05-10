<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Action\DashboardAction;
use AppBundle\RoleHandler\RoleHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccessController
 * @package AppBundle\Controller
 */
class AccessController extends KickerController
{
    /**
     * @CFG\Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('dashboard');
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @CFG\Security("has_role('ROLE_PLAYER')")
     * @CFG\Route("/dashboard/", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        /* @var $handler RoleHandler  */
        $handler = $this->get('app.role_handler');
        $handler->handle(new DashboardAction($request), $this->getParameter('template.dashboard'));

        return $this->buildResponse($handler);
    }
}