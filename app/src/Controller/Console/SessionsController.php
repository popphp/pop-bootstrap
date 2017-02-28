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

use App\Model;
use Pop\Console\Console;

/**
 * Console Roles Controller class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class SessionsController extends ConsoleController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $sessions = (new Model\Session())->getAll();

        $this->console->append("ID  \tUsername\tIP\t\tStart");
        $this->console->append("----\t--------\t--\t\t-----");

        foreach ($sessions as $session) {
            $ago = time() - $session->start;
            if ($ago < 60) {
                $ago = '< 1 minute ago';
            } else if (($ago >= 60) && ($ago < 3600)) {
                $minutes = round($ago / 60);
                $ago     = $minutes . ' min' . (($minutes > 1) ? 's' : '') . ' ago';
            } else {
                $hours = round($ago / 3600);
                $ago   = $hours . ' hour' . (($hours > 1) ? 's' : '') . ' ago';
            }
            $this->console->append(
                $session->id . "\t" . $session->username . "\t\t" . $session->ip . "\t" .
                date('M j Y H:i:s', $session->start) . ' [ ' . $ago . ' ]'
            );
        }

        $this->console->send();
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        $sessId = $this->getSessionId();

        $session = new Model\Session();
        $session->remove([$sessId]);

        $this->console->write();
        $this->console->write($this->console->colorize('Session Removed!', Console::BOLD_RED));
    }

    /**
     * Get role id
     *
     * @return int
     */
    protected function getSessionId()
    {
        $sessions = (new Model\Session())->getAll();
        $sessIds = [];
        foreach ($sessions as $session) {
            $sessIds[] = $session->id;
            $this->console->append($session->username . ":\t" . $session->id);
        }

        $this->console->append();
        $this->console->send();

        $sessId = null;
        while (!is_numeric($sessId) || !in_array($sessId, $sessIds)) {
            $sessId = $this->console->prompt('Select Session ID: ');
        }

        return $sessId;
    }

}
