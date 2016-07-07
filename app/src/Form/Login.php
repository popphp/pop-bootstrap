<?php

namespace App\Form;

use App\Table;
use Pop\Auth\Auth;
use Pop\Form\Form;
use Pop\Validator;

class Login extends Form
{

    public function __construct(array $fields, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'login-form');
        $this->setAttribute('class', 'form-signin');
        $this->setIndent('    ');
    }

    public function setFieldValues(array $values = null, Auth $auth = null)
    {
        parent::setFieldValues($values);

        if (($_POST) && (null !== $this->username) && (null !== $this->password) && (null !== $auth)) {
            $auth->authenticate(
                html_entity_decode($this->username, ENT_QUOTES, 'UTF-8'),
                html_entity_decode($this->password, ENT_QUOTES, 'UTF-8')
            );

            if (!($auth->isValid())) {
                $this->getElement('password')
                     ->addValidator(new Validator\NotEqual($this->password, 'The login was not correct.'));
            } else if (!$auth->adapter()->getUser()->verified) {
                $this->getElement('password')
                     ->addValidator(new Validator\NotEqual($this->password, 'That user is not verified.'));
            } else if (!$auth->adapter()->getUser()->active) {
                $this->getElement('password')
                     ->addValidator(new Validator\NotEqual($this->password, 'That user is blocked.'));
            }
        }

        return $this;
    }

}