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
namespace App\Controller\Sessions;

use App\Controller\AbstractController;
use App\Model;
use Pop\Paginator\Form as Paginator;

/**
 * Sessions controller class
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
        $session = new Model\Session();

        $searchUsername = $this->request->getQuery('search_username');

        if ($session->hasPages($this->application->config()['pagination'], $searchUsername)) {
            $limit = $this->application->config()['pagination'];
            $pages = new Paginator($session->getCount($searchUsername), $limit);
        } else {
            $limit = null;
            $pages = null;
        }

        $this->prepareView('sessions/index.phtml');
        $this->view->title          = 'Sessions';
        $this->view->pages          = $pages;
        $this->view->queryString    = $this->getQueryString('sort');
        $this->view->searchUsername = $searchUsername;
        $this->view->users          = $session->getAllUsers();
        $this->view->sessions       = $session->getAll(
            $searchUsername, $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
        );
        $this->send();
    }

    /**
     * Logins action method
     *
     * @return void
     */
    public function logins()
    {
        $session = new Model\Session();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            if (isset($post['clear_all_logins']) && ((int)$post['clear_all_logins'] == 1)) {
                $session->clearLogins();
                $this->sess->setRequestValue('removed', true);
            } else if (isset($post['clear_logins_by']) && ($post['clear_logins_by'] != '----')) {
                $session->clearLogins($post['clear_logins_by']);
                $this->sess->setRequestValue('removed', true);
            } else if (isset($post['rm_logins'])) {
                $session->removeLogins($post['rm_logins']);
                $this->sess->setRequestValue('removed', true);
            }
            $this->redirect('/sessions/logins');
        } else {
            $searchUsername = $this->request->getQuery('search_username');

            if ($session->hasLoginPages($this->application->config()['pagination'], $searchUsername)) {
                $limit = $this->application->config()['pagination'];
                $pages = new Paginator($session->getLoginCount($searchUsername), $limit);
            } else {
                $limit = null;
                $pages = null;
            }

            $this->prepareView('sessions/logins.phtml');
            $this->view->title          = 'Sessions : Logins';
            $this->view->pages          = $pages;
            $this->view->queryString    = $this->getQueryString('sort');
            $this->view->searchUsername = $searchUsername;
            $this->view->users          = $session->getAllUsers();
            $this->view->logins         = $session->getLogins(
                $searchUsername, $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
            );
            $this->send();
        }
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        if ($this->request->isPost()) {
            $post    = $this->request->getPost();
            $session = new Model\Session();

            if (isset($post['clear_all_sessions']) && ((int)$post['clear_all_sessions'] == 1)) {
                $session->clearSessions($this->sess->user->sess_id);
                $this->sess->setRequestValue('removed', true);
            } else if (isset($post['clear_sessions_by']) && ($post['clear_sessions_by'] != '----')) {
                $session->clearSessions($this->sess->user->sess_id, $post['clear_sessions_by']);
                $this->sess->setRequestValue('removed', true);
            } else if (isset($post['rm_sessions'])) {
                $session->remove($post['rm_sessions']);
                $this->sess->setRequestValue('removed', true);
            }
        }
        $this->redirect('/sessions');
    }

}