<?php

namespace App\Form;

use App\Table;
use Pop\Form\Form;
use Pop\Validator;

class Forgot extends Form
{

    public function __construct(array $fields, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'login-form');
        $this->setAttribute('class', 'form-signin');
        $this->setIndent('    ');
    }

    public function setFieldValues(array $values = null)
    {
        parent::setFieldValues($values);

        if (($_POST) && (null !== $this->email)) {
            $user = Table\Users::findBy(['email' => $this->email]);
            if (!isset($user->id)) {
                $this->getElement('email')
                     ->addValidator(new Validator\NotEqual($this->email, 'That email does not exist.'));
            }
        }

        return $this;
    }

}