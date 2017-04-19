<?php

namespace AppBundle\Form\Handler;

use Exception;
use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;

class RegistrationFormHandler extends BaseHandler
{
    /**
     * @param bool $confirmation
     * @throws \Exception
     * @return boolean
     */
    public function process($confirmation = false)
    {
        if ('POST' !== $this->request->getMethod()) {
            return false;
        }
        $user = $this->createUser();
        $this->form->setData($user);
        $this->form->bind($this->request);
        $msg = [];
        !$this->form->isValid() && ($msg[] = 'Invalid form data');
        (count($user->getRoles()) == 0) && ($msg[] = 'No role selected');
        if (!empty($msg)) {
            throw new Exception(implode('. ', $msg));
        }

        return $this->onSuccess($user, $confirmation);
    }

    /**
     * @param UserInterface $user
     * @param bool $confirmation
     * @return bool
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        try {
            parent::onSuccess($user, $confirmation);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }


}