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
namespace App\Auth\Model;

use App\Model\AbstractModel;
use App\Auth\Table;
/**
 * Auth model class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.0
 */
class AuthUser extends AbstractModel
{

    /**
     * Authenticate user
     *
     * @param  string $username
     * @param  string $password
     * @param  int    $attempts
     * @return int
     */
    public function authenticate($username, $password, $attempts = 0)
    {
        $result = 0;
        $user   = Table\AuthUsers::findOne(['username' => $username]);

        if (((int)$attempts > 0) && ((int)$user->attempts >= (int)$attempts)) {
            $result = -1;
            $user->attempts++;
            $user->save();
        } else if (isset($user->id) && ($user->active)) {
            $result = (int)password_verify($password, $user->password);
            if ($result) {
                $user->attempts = 0;
                $user->save();

                $this->data = array_merge($this->data, $user->toArray());
            } else {
                $user->attempts++;
                $user->save();
            }
        }

        return $result;
    }

}
