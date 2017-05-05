<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class PlayerController
 * @package AppBundle\Controller
 */
abstract class KickerController extends Controller
{
    public function buildResponse(array $handlerResult)
    {
        isset($handlerResult['messages']) && $this->fillFlashBag($handlerResult['messages']);

        return $this->render(
            $handlerResult['template'],
            $handlerResult['parameters']
        );
    }

    protected function fillFlashBag(array $messages)
    {
        foreach ($messages as $index => $message) {
            $this->get('session')->getFlashBag()->set($index, $message);
        }
    }

    public function buildRedirect(array $handlerResult)
    {
        isset($handlerResult['messages']) && $this->fillFlashBag($handlerResult['messages']);

        return $this->redirect(urldecode($handlerResult['url']));
    }
}