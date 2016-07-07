<?php

namespace App\Form;

use App\Table;
use Pop\Form\Form;
use Pop\Validator;

class Profile extends Form
{

    public function __construct(array $fields, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'profile-form');
        $this->setAttribute('class', 'form-signin');
        $this->setIndent('    ');
    }

    public function setFieldValues(array $values = null)
    {
        parent::setFieldValues($values);

        if (($_POST) && (null !== $this->username)) {
            // Check for dupe username
            $user = null;
            if (null !== $this->username) {
                $user = Table\Users::findBy(['username' => $this->username]);
                if (isset($user->id) && ($this->id != $user->id)) {
                    $this->getElement('username')
                         ->addValidator(new Validator\NotEqual($this->username, 'That username is not allowed.'));
                }
            }

            // Check for dupe email
            $email = Table\Users::findBy(['email' => $this->email]);
            if (isset($email->id) && ($this->id != $email->id)) {
                $this->getElement('email')
                     ->addValidator(new Validator\NotEqual($this->email, 'That email is not allowed.'));
            }

            // Check password matches
            if (!empty($this->password1)) {
                $this->getElement('password2')
                     ->setRequired(true)
                     ->addValidator(new Validator\Equal($this->password1, 'The passwords do not match.'));
            }
        }

        return $this;
    }

}