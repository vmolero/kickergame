<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/", name="adminHomepage")
     */
    public function indexAction(Request $request)
    {

        // !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') && $this->redirect();
        return $this->render(
            'admin/index.html.twig',
            [
                'url' => $request->getBaseUrl().'/register',
            ]
        );
    }

}