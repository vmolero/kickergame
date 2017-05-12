<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Action\DashboardAction;
use AppBundle\ServiceLayer\RenderService;
use AppBundle\ServiceLayer\RoleHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as CFG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccessController
 * @package AppBundle\Controller
 */
class AccessController extends Controller
{
    /**
     * @CFG\Route("/", name="home")
     *
     * @param Request $request
     * @return RedirectResponse
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
     *
     * @param Request $request
     * @return mixed
     */
    public function dashboardAction(Request $request)
    {
        /* @var $handler RoleHandler  */
        $handler = $this->get('app.role_handler');
        $handler->handle(new DashboardAction($request));
        /* @var $render RenderService  */
        $render = $this->get('app.render');

        return $render->setTemplate($this->getParameter('template.dashboard'))->buildResponse($handler);
    }
}