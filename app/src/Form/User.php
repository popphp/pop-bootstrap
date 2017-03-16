<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Form;

use App\Table;
use Pop\Form\Form;
use Pop\Validator;

/**
 * User form class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class User extends Form
{

    /**
     * Constructor
     *
     * Instantiate the form object
     *
     * @param  array  $fields
     * @param  string $action
     * @param  string $method
     */
    public function __construct(array $fields = null, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'user-form');
        $this->setAttribute('class', 'data-form');
        $this->setIndent('    ');
    }

    /**
     * Set the field values
     *
     * @param  array $values
     * @return User
     */
    public function setFieldValues(array $values = null)
    {
        parent::setFieldValues($values);

        if (($_POST) && (null !== $this->username)) {
            // Check for dupe username and email
            $user  = null;
            $email = null;
            if (!empty($this->username)) {
                $user = Table\Users::findOne(['username' => $this->username]);
                if (isset($user->id) && ($this->id != $user->id)) {
                    $this->getField('username')
                         ->addValidator(new Validator\NotEqual($this->username, 'That username already exists.'));
                }
            }

            if (!empty($this->email)) {
                $email = Table\Users::findOne(['email' => $this->email]);
                if (isset($email->id) && ($this->id != $email->id)) {
                    $this->getField('email')
                         ->addValidator(new Validator\NotEqual($this->email, 'That email already exists.'));
                }
            }

            // If existing user
            if ((int)$_POST['id'] > 0) {
                if (!empty($this->password1)) {
                    $this->getField('password2')
                         ->setRequired(true)
                         ->addValidator(new Validator\Equal($this->password1, 'The passwords do not match.'));
                }
            // Else, if new user, check email and password matches
            } else {
                $this->getField('password2')
                     ->setRequired(true)
                     ->addValidator(new Validator\Equal($this->password1, 'The passwords do not match.'));
            }
        }

        return $this;
    }

}