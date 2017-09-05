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

/**
 * Web orders controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.0
 */
class OrdersController extends AbstractController
{

    /**
     * Orders index action method
     *
     * @return void
     */
    public function index()
    {
        $sess = $this->application->services['session'];

        $this->prepareView('orders/index.phtml');
        $this->view->title    = 'Orders';
        $this->view->username = $sess->user->username;

        $this->send();
    }

}