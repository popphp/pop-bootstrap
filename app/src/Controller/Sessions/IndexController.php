<?php

namespace App\Controller\Sessions;

use App\Controller\AbstractController;
use App\Form;
use App\Model;
use Pop\Paginator\Paginator;

class IndexController extends AbstractController
{

    public function index()
    {
        $user    = new Model\User();
        $session = new Model\Session();

        $searchUsername = $this->request->getQuery('search_username');

        if ($session->hasPages($this->application->config()['pagination'], $searchUsername)) {
            $limit = $this->application->config()['pagination'];
            $pages = new Paginator($session->getCount($searchUsername), $limit);
            $pages->useInput(true);
        } else {
            $limit = null;
            $pages = null;
        }

        $this->prepareView('sessions/index.phtml');
        $this->view->title          = 'Sessions';
        $this->view->pages          = $pages;
        $this->view->queryString    = $this->getQueryString('sort');
        $this->view->searchUsername = $searchUsername;
        $this->view->users          = $user->getAll();
        $this->view->sessions       = $session->getAll(
            $searchUsername, $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
        );
        $this->send();
    }

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
            $user = new Model\User();

            $searchUsername = $this->request->getQuery('search_username');

            if ($session->hasLoginPages($this->application->config()['pagination'], $searchUsername)) {
                $limit = $this->application->config()['pagination'];
                $pages = new Paginator($session->getLoginCount($searchUsername), $limit);
                $pages->useInput(true);
            } else {
                $limit = null;
                $pages = null;
            }

            $this->prepareView('sessions/logins.phtml');
            $this->view->title          = 'Sessions : Logins';
            $this->view->pages          = $pages;
            $this->view->queryString    = $this->getQueryString('sort');
            $this->view->searchUsername = $searchUsername;
            $this->view->users          = $user->getAll();
            $this->view->logins         = $session->getLogins(
                $searchUsername, $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
            );
            $this->send();
        }
    }

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