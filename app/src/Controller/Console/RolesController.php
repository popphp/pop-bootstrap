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
namespace App\Controller\Console;

use App\Model;
use Pop\Console\Console;

/**
 * Console Roles Controller class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class RolesController extends ConsoleController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $roles = (new Model\Role())->getAll();

        $this->console->append("ID  \tName");
        $this->console->append("----\t----");

        foreach ($roles as $role) {
            $this->console->append($role->id . "\t" . $role->name);
        }

        $this->console->send();
    }

    /**
     * Add action method
     *
     * @return void
     */
    public function add()
    {
        $name = '';
        while ($name == '') {
            $name = $this->console->prompt('Enter Name: ', null, true);
        }

        $fields = [
            'role_parent_id'    => '----',
            'name'              => $name
        ];

        $role = new Model\Role();
        $role->save($fields);

        $this->console->write();
        $this->console->write($this->console->colorize('Role Added!', Console::BOLD_GREEN));
    }

    /**
     * Password action method
     *
     * @return void
     */
    public function edit()
    {
        $roleId = $this->getRoleId();

        $name = '';
        while ($name == '') {
            $name = $this->console->prompt('Enter Name: ', null, true);
        }

        $fields = [
            'id'                => $roleId,
            'role_parent_id'    => '----',
            'name'              => $name
        ];

        $role = new Model\Role();
        $role->update($fields);

        $this->console->write();
        $this->console->write($this->console->colorize('Role Updated!', Console::BOLD_GREEN));
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        $roleId = $this->getRoleId();

        $role = new Model\Role();
        $role->remove(['rm_roles' => [$roleId]]);

        $this->console->write();
        $this->console->write($this->console->colorize('Role Removed!', Console::BOLD_RED));
    }

    /**
     * Get role id
     *
     * @return int
     */
    protected function getRoleId()
    {
        $roles   = (new Model\Role())->getAll();
        $roleIds = [];
        foreach ($roles as $role) {
            $roleIds[] = $role->id;
            $this->console->append($role->name . ":\t" . $role->id);
        }

        $this->console->append();
        $this->console->send();

        $roleId = null;
        while (!is_numeric($roleId) || !in_array($roleId, $roleIds)) {
            $roleId = $this->console->prompt('Select Role ID: ');
        }

        return $roleId;
    }

}
