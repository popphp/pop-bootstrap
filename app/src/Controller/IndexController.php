<?php

namespace App\Controller;

use App\Form;
use Pop\Auth;

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
        $this->view->title = 'Hello, World!';
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
        $this->view->form  = new Form\Login($this->application->config()['forms']['App\Form\Login']);

        if ($this->request->isPost()) {
            $auth = new Auth\Auth(new Auth\Adapter\Table('App\Table\Users',Auth\Auth::ENCRYPT_BCRYPT));

            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost(), $auth);

            if ($this->view->form->isValid()) {
                $this->sess->user = new \ArrayObject([
                    'id'       => $auth->adapter()->getUser()->id,
                    'username' => $auth->adapter()->getUser()->username
                ], \ArrayObject::ARRAY_AS_PROPS);

                $this->redirect('/');
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
        $this->sess->kill();
        $this->redirect('/login');
    }

}