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
namespace App\Http\Web\Controller;

use App\Auth;
use App\Http\Web\Form;

/**
 * Web index controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class IndexController extends AbstractController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $sess = $this->application->services['session'];

        $this->prepareView('index.phtml');
        $this->view->title    = 'Dashboard';
        $this->view->username = $sess->user->username;

        $this->send();
    }

    /**
     * Orders action method
     *
     * @return void
     */
    public function orders()
    {
        $sess = $this->application->services['session'];

        $this->prepareView('orders/index.phtml');
        $this->view->title    = 'Orders';
        $this->view->username = $sess->user->username;

        $this->send();
    }

    /**
     * Login action method
     *
     * @return void
     */
    public function login()
    {
        $this->prepareView('login.phtml');
        $this->view->title = 'Please Login';
        $this->view->form  = Form\Login::createFromFieldsetConfig(
            $this->application->config()['forms']['App\Web\Form\Login']
        );

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8', false])
                 ->setFieldValues($this->request->getPost());

            $username = $this->view->form->username;
            $password = $this->view->form->password;
            $authUser = new Auth\Model\AuthUser();
            $result   = $authUser->authenticate($username, $password, $this->application->config['auth_attempts']);

            if ($result == 1) {
                $authUser->login(
                    $this->application->services['session'],
                    $this->application->services['cookie'],
                    $this->application->config()['token_expires']
                );
                $this->redirect('/');
            } else {
                $this->view->error = 'Login Failed.';
                if ($result == -1) {
                    $this->view->error .= ' Too Many Attempts.';
                }
            }
        }

        $this->send();
    }

    /**
     * Logout action method
     *
     * @return void
     */
    public function logout()
    {
        $authUser = new Auth\Model\AuthUser();
        $authUser->logout(
            $this->application->services['session'],
            $this->application->services['cookie'],
            $this->request->getQuery('revoke')
        );
        $this->redirect('/login');
    }

}