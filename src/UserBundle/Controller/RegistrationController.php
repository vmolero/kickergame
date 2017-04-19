<?php

namespace UserBundle\Controller;

use Exception;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController extends BaseController
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        try {
            $process = $formHandler->process($confirmationEnabled);
        } catch (Exception $e) {
            $process = false;
            $this->setFlash('fos_user_success', 'User validation failed: '.$e->getMessage());
        }
        if ($process) {
            $route = 'fos_user_registration_confirmed';
            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            return $response;
        }

        $username = $this->getFlash('fos_user_username');

        return $this->container->get('templating')->renderResponse(
            'UserBundle:Registration:register.html.'.$this->getEngine(),
            [
                'form' => $form->createView(),
                'username' => $username,
                'menuUrl' => $this->container->get('request')->getBaseUrl(),
            ]
        );
    }

    /**
     * @param string $action
     */
    private function getFlash($action)
    {
        $this->container->get('session')->getFlashBag()->get($action);
    }

    /**
     * Tell the user his account is now confirmed
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function confirmedAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (is_object($user) && $user instanceof UserInterface) {
            $this->setFlash('fos_user_username', $user->getUsername());
        }
        $request = $this->container->get('request');

        return new RedirectResponse($request->getBaseUrl().'/register');
    }

}