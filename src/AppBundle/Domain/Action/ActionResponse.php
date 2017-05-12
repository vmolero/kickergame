<?php

namespace AppBundle\Domain\Action;

/**
 * Class ActionResponse
 * @package AppBundle\Domain\Action
 */
class ActionResponse
{
    /**
     * @var array
     */
    private $parameters = [];
    /**
     * @var array
     */
    private $messages = [];

    /**
     * ActionResponse constructor.
     * @param array $parameters
     * @param array $messages
     */
    public function __construct(array $parameters = [], array $messages = [])
    {
        $this->parameters = $parameters;
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return ActionResponse
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return ActionResponse
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param $key
     * @param $message
     */
    public function addMessage($key, $message)
    {
        $this->messages[$key] = $message;
    }
}