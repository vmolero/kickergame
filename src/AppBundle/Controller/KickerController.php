<?php

namespace AppBundle\Controller;

use AppBundle\RoleHandler\RoleHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class PlayerController
 * @package AppBundle\Controller
 */
abstract class KickerController extends Controller
{
    public function buildResponse(RoleHandler $handler)
    {
        $this->fillFlashBag($handler->getMessages());

        return $this->render(
            $handler->getTemplate(),
            $handler->getParameters()
        );
    }

    protected function fillFlashBag(array $messages)
    {
        foreach ($messages as $index => $message) {
            $this->get('session')->getFlashBag()->set($index, $message);
        }
    }

    public function buildRedirect(RoleHandler $handler)
    {
        $this->fillFlashBag($handler->getMessages());

        return $this->redirectToRoute($handler->getRedirectTo());
    }
}