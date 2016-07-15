<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Controller\Console;

use Pop\Console\Console;

/**
 * Console Help Controller class
 *
 * @category   Pop_Bootstrap
 * @package    Pop_Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.0
 */
class HelpController extends ConsoleController
{

    /**
     * Help action method
     *
     * @return void
     */
    public function index()
    {
        $yellow = Console::BOLD_YELLOW;
        $green = Console::BOLD_GREEN;

        $this->console->append('./app ' . $this->console->colorize('help', $yellow) . "\t\t\tShow this help screen");

        $this->console->append();
        $this->console->append('./app ' . $this->console->colorize('users', $yellow) . "\t\t\tList users");
        $this->console->append('./app ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('add', $green) . "\t\tAdd a user");
        $this->console->append('./app ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('password', $green) . "\tChange a user password");
        $this->console->append('./app ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('activate', $green) . "\tActivate a user");
        $this->console->append('./app ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('deactivate', $green) . "\tDeactivate a user");
        $this->console->append('./app ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('remove', $green) . "\t\tRemove a user");

        $this->console->append();
        $this->console->append('./app ' . $this->console->colorize('roles', $yellow) . "\t\t\tList roles");
        $this->console->append('./app ' . $this->console->colorize('roles', $yellow) . ' ' . $this->console->colorize('add', $green) . "\t\tAdd a role");
        $this->console->append('./app ' . $this->console->colorize('roles', $yellow) . ' ' . $this->console->colorize('edit', $green) . "\t\tEdit a role");
        $this->console->append('./app ' . $this->console->colorize('roles', $yellow) . ' ' . $this->console->colorize('remove', $green) . "\t\tRemove a role");

        $this->console->append();
        $this->console->append('./app ' . $this->console->colorize('sessions', $yellow) . "\t\tList sessions");
        $this->console->append('./app ' . $this->console->colorize('sessions', $yellow) . ' ' . $this->console->colorize('remove', $green) . "\tRemove a session");

        $this->console->send();
    }

}

