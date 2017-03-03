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
namespace App\Controller;

use App\Form;
use App\Model;
use Pop\Auth;

/**
 * Index controller class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
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
        $this->prepareView('index.phtml');
        $this->view->title     = 'Dashboard';
        $this->view->dbVersion = $this->services['database']->getVersion();
        $this->view->database  = (strtolower($this->application->config()['database']['adapter']) == 'pdo') ?
            $this->application->config()['database']['type'] . ' (pdo)' :
            $this->view->database = $this->application->config()['database']['adapter'];

        $this->send();
    }

    /**
     * Side nav example action method
     *
     * @return void
     */
    public function side()
    {
        $this->prepareView('index.phtml');

        $this->view->fluidNav  = $this->services['nav.fluid'];
        $this->view->title     = 'Dashboard';
        $this->view->dbVersion = $this->services['database']->getVersion();
        $this->view->database  = (strtolower($this->application->config()['database']['adapter']) == 'pdo') ?
            $this->application->config()['database']['type'] . ' (pdo)' :
            $this->view->database = $this->application->config()['database']['adapter'];

        $this->send();
    }

    /**
     * Static side nav example action method
     *
     * @return void
     */
    public function staticSide()
    {
        $this->prepareView('index-static.phtml');

        $this->view->staticNav = $this->services['nav.static'];
        $this->view->title     = 'Dashboard';
        $this->view->dbVersion = $this->services['database']->getVersion();
        $this->view->database  = (strtolower($this->application->config()['database']['adapter']) == 'pdo') ?
            $this->application->config()['database']['type'] . ' (pdo)' :
            $this->view->database = $this->application->config()['database']['adapter'];

        $this->send();
    }

    /**
     * Profile action method
     *
     * @return void
     */
    public function profile()
    {
        $this->prepareView('profile.phtml');
        $this->view->title = 'My Profile';

        $user = new Model\User();
        $user->getById($this->sess->user->id);

        $this->view->form = Form\Profile::createFromFieldsetConfig($this->application->config()['forms']['App\Form\Profile']);
        $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
             ->setFieldValues($user->toArray());

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filterValues();

                $user = new Model\User();
                $user->update(
                    $this->view->form,
                    $this->application->config()['application_title'],
                    $this->application->services()->get('mailer'),
                    $this->sess
                );
                $this->view->id = $user->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect('/profile');
            }
        }

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
        $this->view->form  = Form\Login::createFromFieldsetConfig($this->application->config()['forms']['App\Form\Login']);

        if ($this->request->isPost()) {
            $auth = new Auth\Table('App\Table\Users');

            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost(), $auth);

            $user    = new Model\User();
            $session = new Model\Session();

            if ($this->view->form->isValid() && ($session->validate($auth->getUser(), $this->application->config()))) {
                $user->login($auth->getUser(), $this->sess, $this->application->config());
                $this->redirect('/');
            } else {
                if ((null !== $auth->getUser()) && (null !== $auth->getUser()->id)) {
                    $user->failed($auth->getUser());
                    if ($this->view->form->isValid()) {
                        $this->sess->setRequestValue('failed', true);
                        $this->redirect('/login');
                    }
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
        if (isset($this->sess->user)) {
            $user = new Model\User();
            $user->logout($this->sess);
        }

        if ((int)$this->request->getQuery('expired') == 1) {
            $this->sess->setRequestValue('expired', true);
        }

        $this->redirect('/login');
    }

    /**
     * Forgot action method
     *
     * @return void
     */
    public function forgot()
    {
        $this->prepareView('forgot.phtml');
        $this->view->title   = 'Password Reset';
        $this->view->success = false;
        $this->view->form    = Form\Forgot::createFromFieldsetConfig($this->application->config()['forms']['App\Form\Forgot']);

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filterValues();

                $user = new Model\User();
                $user->forgot(
                    $this->view->form,
                    $this->application->config()['application_title'],
                    $this->application->services()->get('mailer')
                );
                $this->view->id      = $user->id;
                $this->view->success = true;
            }
        }

        $this->send();
    }

    /**
     * Verify action method
     *
     * @param  int    $id
     * @param  string $hash
     * @return void
     */
    public function verify($id, $hash)
    {
        $this->prepareView('verify.phtml');
        $this->view->title = 'Verify Your Email';

        $user = new Model\User();
        $this->view->result = $user->verify($id, $hash);
        $this->view->id     = $user->id;

        $this->send();
    }

}