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
namespace App\Console\Controller;

use App\Auth\Model\AuthUser;
use App\Auth\Table\AuthTokens;
use App\Auth\Table\AuthUsers;
use Pop\Console\Console;

/**
 * Console users controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.1
 */
class UsersController extends ConsoleController
{

    /**
     * Users index command
     *
     * @return void
     */
    public function index()
    {
        $users = (new AuthUser())->getAll();

        foreach ($users as $user) {
            $this->console->append($user->id . "\t" . $user->username);
        }

        $this->console->send();
    }

    /**
     * Users add command
     *
     * @return void
     */
    public function add()
    {
        $username = $this->console->prompt('Enter Username: ');
        $dupeUser = AuthUsers::findOne(['username' => $username]);

        while (($username == '') || isset($dupeUser->id)) {
            if (isset($dupeUser->id)) {
                $this->console->write($this->console->colorize('That username already exists.', Console::BOLD_RED));
            }
            $username = $this->console->prompt('Enter Username: ');
            $dupeUser = AuthUsers::findOne(['username' => $username]);
        }

        $password = '';
        while ($password == '') {
            $password = $this->console->prompt('Enter Password: ');
        }

        $active = '';
        while ((strtolower($active) != 'y') && (strtolower($active) != 'n')) {
            $active = $this->console->prompt('Active? (Y/N): ');
        }

        $user = new AuthUser();
        $user->save([
            'username' => $username,
            'password' => $password,
            'active'   => (strtolower($active) == 'y') ? 1 : 0
        ]);

        $this->console->write();
        $this->console->write($this->console->colorize('User Added!', Console::BOLD_GREEN));
    }

    /**
     * Users change username command
     *
     * @param  mixed  $user
     * @return void
     */
    public function username($user)
    {
        $u = (is_numeric($user)) ? AuthUsers::findById((int)$user) : AuthUsers::findOne(['username' => $user]);

        if (!isset($u->id)) {
            $this->console->write($this->console->colorize('That user does not exist.', Console::BOLD_RED));
        } else {
            $username = $this->console->prompt('Enter New Username: ');
            $dupeUser = AuthUsers::findOne(['username' => $username, 'id!=' => $u->id]);

            while (($username == '') || isset($dupeUser->id)) {
                if (isset($dupeUser->id)) {
                    $this->console->write($this->console->colorize('That username already exists.', Console::BOLD_RED));
                }
                $username = $this->console->prompt('Enter New Username: ');
                $dupeUser = AuthUsers::findOne(['username' => $username, 'id!=' => $u->id]);
            }
            $u->username = $username;
            $u->save();
            $this->console->write($this->console->colorize('User username changed.', Console::BOLD_GREEN));
        }
    }

    /**
     * Users change password command
     *
     * @param  mixed  $user
     * @return void
     */
    public function password($user)
    {
        $u = (is_numeric($user)) ? AuthUsers::findById((int)$user) : AuthUsers::findOne(['username' => $user]);

        if (!isset($u->id)) {
            $this->console->write($this->console->colorize('That user does not exist.', Console::BOLD_RED));
        } else {
            $password = '';
            while ($password == '') {
                $password = $this->console->prompt('Enter Password: ');
            }
            $u->password = password_hash($password, PASSWORD_BCRYPT);
            $u->save();
            $this->console->write($this->console->colorize('User password changed.', Console::BOLD_GREEN));
        }
    }

    /**
     * Users activate command
     *
     * @param  mixed $user
     * @return void
     */
    public function activate($user)
    {
        $u = (is_numeric($user)) ? AuthUsers::findById((int)$user) : AuthUsers::findOne(['username' => $user]);
        if (!isset($u->id)) {
            $this->console->write($this->console->colorize('That user does not exist.', Console::BOLD_RED));
        } else {
            $u->active = 1;
            $u->save();
            $this->console->write($this->console->colorize('User activated.', Console::BOLD_GREEN));
        }
    }

    /**
     * Users deactivate command
     *
     * @param  mixed $user
     * @return void
     */
    public function deactivate($user)
    {
        $u = (is_numeric($user)) ? AuthUsers::findById((int)$user) : AuthUsers::findOne(['username' => $user]);
        if (!isset($u->id)) {
            $this->console->write($this->console->colorize('That user does not exist.', Console::BOLD_RED));
        } else {
            $u->active = 0;
            $u->save();
            $this->console->write($this->console->colorize('User deactivated.', Console::BOLD_GREEN));
        }
    }

    /**
     * Users clear command
     *
     * @param  mixed $user
     * @return void
     */
    public function clear($user)
    {
        $u = (is_numeric($user)) ? AuthUsers::findById((int)$user) : AuthUsers::findOne(['username' => $user]);
        if (!isset($u->id)) {
            $this->console->write($this->console->colorize('That user does not exist.', Console::BOLD_RED));
        } else {
            $u->attempts = 0;
            $u->save();
            $this->console->write($this->console->colorize('User failed login attempts cleared.', Console::BOLD_GREEN));
        }
    }

    /**
     * Users revoke command
     *
     * @param  mixed $user
     * @return void
     */
    public function revoke($user)
    {
        $id = (!is_numeric($user)) ?
            (int)AuthUsers::findOne(['username' => $user])->id : (int)$user;

        $token = new AuthTokens();
        $token->delete(['user_id' => $id]);

        $this->console->write($this->console->colorize('That user has been revoked.', Console::BOLD_RED));
    }

    /**
     * Users remove command
     *
     * @param  mixed $user
     * @return void
     */
    public function remove($user)
    {
        $u = (is_numeric($user)) ? AuthUsers::findById((int)$user) : AuthUsers::findOne(['username' => $user]);
        if (!isset($u->id)) {
            $this->console->write($this->console->colorize('That user does not exist.', Console::BOLD_RED));
        } else {
            $u->delete();
            $this->console->write($this->console->colorize('User removed.', Console::BOLD_RED));
        }
    }

}