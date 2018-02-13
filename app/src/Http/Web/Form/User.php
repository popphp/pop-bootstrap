<?php
/**
 * Pop Bootstrap Application
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Http\Web\Form;

use App\Auth\Table;
use Pop\Form\Form;
use Pop\Validator;

/**
 * Web user form class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
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
        $this->setAttribute('class', 'data-form');
        $this->setAttribute('id', 'user-form');
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
                $user = Table\AuthUsers::findOne(['username' => $this->username]);
                if (isset($user->id) && ($this->id != $user->id)) {
                    $this->getField('username')
                         ->addValidator(new Validator\NotEqual($this->username, 'That username already exists.'));
                }
            }

            // If existing user
            if ((int)$_POST['id'] > 0) {
                if (!empty($this->password)) {
                    $this->getField('password2')
                         ->setRequired(true)
                         ->addValidator(new Validator\Equal($this->password, 'The passwords do not match.'));
                }
            // Else, if new user, check email and password matches
            } else {
                $this->getField('password2')
                     ->setRequired(true)
                     ->addValidator(new Validator\Equal($this->password, 'The passwords do not match.'));
            }
        }

        return $this;
    }

}