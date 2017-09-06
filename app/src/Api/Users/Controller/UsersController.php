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
namespace App\Api\Users\Controller;

use App\Api\Controller\AbstractController;
use App\Auth\Model;
use Pop\Http\Response;

/**
 * Users controller class
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
     * @param  int $id
     * @return void
     */
    public function index($id = null)
    {
        if (null === $id) {
            $users = (new Model\AuthUser())->getAll();
            $json  = [
                'users' => []
            ];

            foreach ($users as $i => $user) {
                $json['users'][] = [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'active'   => $user->active,
                    'attempts' => $user->attempts
                ];
            }
            $this->send(200, $json);
        } else {
            $user = (new Model\AuthUser())->getById($id);
            if (isset($user->id)) {
                $u = $user->toArray();
                unset($u['password']);
                $this->send(200, $u);
            } else {
                $this->send(404, ['code' => 404, 'message' => Response::getMessageFromCode(404)]);
            }
        }
    }

    /**
     * Users add action method
     *
     * @return void
     */
    public function add()
    {
        $data = $this->request->getParsedData();

        if (empty($data['username']) || empty($data['password'])) {
            $this->send(400, ['code' => 400, 'message' => Response::getMessageFromCode(400)]);
        } else {
            $user = new Model\AuthUser();
            $user->save($data);

            if (!empty($user->id)) {
                $this->send(201, ['code' => 201, 'message' => Response::getMessageFromCode(201)]);
            } else {
                $this->send(400, ['code' => 400, 'message' => Response::getMessageFromCode(400)]);
            }
        }
    }

    /**
     * Users edit action method
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        $user = (new Model\AuthUser())->getById($id);
        if (isset($user->id)) {
            $user = new Model\AuthUser();
            $user->update($id, $this->request->getParsedData());
            $this->send(200, ['code' => 200, 'message' => Response::getMessageFromCode(200)]);
        } else {
            $this->send(404, ['code' => 404, 'message' => Response::getMessageFromCode(404)]);
        }
    }

}