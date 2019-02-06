<?php
/**
 * Pop Bootstrap Application
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Http\Web\Controller;

use App\Users\Model\User;
use App\Http\Web\Form;
use Pop\Form\Filter\Filter;

/**
 * Web users controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
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
        $users = new User();
        $sess  = $this->application->services['session'];

        $this->prepareView('users/index.phtml');
        $this->view->title    = 'Users';
        $this->view->username = $sess->user->username;
        $this->view->userId   = $sess->user->id;
        $this->view->users    = $users->getAll();
        $this->send();
    }

    /**
     * Users create action method
     *
     * @throws \Pop\Db\Exception
     * @return void
     */
    public function create()
    {
        $sess = $this->application->services['session'];

        $this->prepareView('users/create.phtml');
        $this->view->title    = 'Users : Create';
        $this->view->username = $sess->user->username;
        $this->view->form     = Form\User::createFromFieldsetConfig($this->application->config['forms']['App\Http\Web\Form\User']);
        $this->view->form->addColumn(1, 'form-left-column');
        $this->view->form->addColumn([2, 3], 'form-right-column');

        if ($this->request->isPost()) {
            $this->view->form->addFilter(new Filter('strip_tags'))
                 ->addFilter(new Filter('htmlentities', [ENT_QUOTES, 'UTF-8', false]))
                 ->setFieldValues($this->request->getPost())
                 ->addValidators();

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter(new Filter('html_entity_decode', [ENT_QUOTES, 'UTF-8']))
                     ->filterValues();

                $user = new User();
                $user->save($this->view->form);

                $this->redirect('/users/' . $user->id);
            }
        }

        $this->send();
    }

    /**
     * Users update action method
     *
     * @param  int $id
     * @return void
     */
    public function update($id)
    {
        $user = (new User())->getById($id);

        if (!isset($user->id)) {
            $this->redirect('/users');
        }

        $sess = $this->application->services['session'];

        $this->prepareView('users/update.phtml');
        $this->view->title    = 'Users : ' . $user->username;
        $this->view->username = $sess->user->username;
        $this->view->form     = Form\User::createFromFieldsetConfig($this->application->config['forms']['App\Http\Web\Form\User']);
        $this->view->form->addColumn(1, 'form-left-column');
        $this->view->form->addColumn([2, 3], 'form-right-column');
        $this->view->form->setFieldValues($user->toArray());

        if ($this->request->isPost()) {
            $this->view->form->addFilter(new Filter('strip_tags'))
                 ->addFilter(new Filter('htmlentities', [ENT_QUOTES, 'UTF-8', false]))
                 ->setFieldValues($this->request->getPost())
                 ->addValidators();

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter(new Filter('html_entity_decode', [ENT_QUOTES, 'UTF-8']))
                     ->filterValues();

                $user = new User();
                $user->update($id, $this->view->form);

                $this->redirect('/users/' . $id);
            }
        }

        $this->send();
    }

    /**
     * Users delete action method
     *
     * @return void
     */
    public function delete()
    {
        if (null !== $this->request->getPost('rm_users')) {
            $user = new User();
            $user->remove($this->request->getPost('rm_users'));
        }
        $this->redirect('/users');
    }

}