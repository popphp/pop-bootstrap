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
namespace App\Web\Controller;

use App\Auth\Model\AuthUser;
use App\Web\Form;

/**
 * Web users controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.0
 */
class UsersController extends AbstractController
{

    /**
     * Users index action method
     *
     * @return void
     */
    public function index()
    {
        $users = new AuthUser();
        $sess  = $this->application->services['session'];

        $this->prepareView('users/index.phtml');
        $this->view->title    = 'Users';
        $this->view->username = $sess->user->username;
        $this->view->userId   = $sess->user->id;
        $this->view->users    = $users->getAll();
        $this->send();
    }

    /**
     * Users add action method
     *
     * @return void
     */
    public function add()
    {
        $sess = $this->application->services['session'];

        $this->prepareView('users/add.phtml');
        $this->view->title    = 'Users : Add';
        $this->view->username = $sess->user->username;
        $this->view->form     = Form\User::createFromFieldsetConfig($this->application->config()['forms']['App\Web\Form\User']);

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8', false])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                    ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                    ->filterValues();

                $user = new AuthUser();
                $user->save($this->view->form);

                $this->redirect('/users/edit/' . $user->id);
            }
        }

        $this->send();
    }

    /**
     * Users add action method
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        $user = (new AuthUser())->getById($id);
        $sess = $this->application->services['session'];

        $this->prepareView('users/edit.phtml');
        $this->view->title    = 'Users : ' . $user->username;
        $this->view->username = $sess->user->username;
        $this->view->form     = Form\User::createFromFieldsetConfig($this->application->config()['forms']['App\Web\Form\User']);
        $this->view->form->setFieldValues($user->toArray());

        $this->send();
    }

    /**
     * Users remove action method
     *
     * @return void
     */
    public function remove()
    {
        $this->redirect('/users');
    }

}