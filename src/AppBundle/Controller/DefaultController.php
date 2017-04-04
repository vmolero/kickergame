<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->redirect(rtrim($request->getRequestUri(), '/') . '/login');
    }

    /**
     * @Route("/login_check", name="loginCheck")
     */
    public function loginCheck(Request $request)
    {
        die("check");
        return $this->redirect($request->getRequestUri() . 'players/');
    }



    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/players/", name="showGames")
     */
    public function showGamesAction(Request $request)
    {

        return new Response('Default response');

        // replace this example code with whatever you need
        // return $this->render('default/index.html.twig', array(
        //    'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        //));
    }

    /**
     * @Method({"GET"})
     * @Route("/admin/users/add", name="addUserForm")
     */
    public function addUserFormAction(Request $request)
    {
        return $this->render('Users/adduser.html.twig', []);
    }

    /**
     * @Method({"POST"})
     * @Route("/admin/users/add", name="addUser")
     */
    public function addUserAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');
        $role = $request->get('role');
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setRoles([$role == 1 ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $user->setEmail($email);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->addUserFormAction($request);
    }
}