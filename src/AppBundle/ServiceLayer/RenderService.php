<?php

namespace AppBundle\ServiceLayer;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class RenderService
 * @package AppBundle\ServiceLayer
 */
class RenderService
{
    /**
     * @var EngineInterface
     */
    private $renderEngine;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * RenderService constructor.
     * @param EngineInterface $renderEngine
     * @param SessionInterface $session
     */
    public function __construct(EngineInterface $renderEngine, SessionInterface $session)
    {
        $this->renderEngine = $renderEngine;
        $this->session = $session;
    }

    /**
     * @param RoleHandler $handler
     * @return Response
     */
    public function buildResponse(RoleHandler $handler)
    {
        $this->fillFlashBag($handler->getMessages());

        return $this->render(
            $handler->getTemplate(),
            $handler->getParameters()
        );
    }

    /**
     * @param array $messages
     */
    protected function fillFlashBag(array $messages)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $this->session->getFlashBag();
        foreach ($messages as $index => $message) {
            $flashBag->set($index, $message);
        }
    }

    /**
     * @param $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (!$this->renderEngine) {
            throw new \LogicException(
                'You can not use the "render" method if the Templating Engine is not available.'
            );
        }
        if ($response === null) {
            $response = new Response();
        }
        return $response->setContent($this->renderEngine->render($view, $parameters));
    }
}