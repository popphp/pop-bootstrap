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
use App\Auth\Table;
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
            $json  = [
                'users' => (new Model\AuthUser())->getAll()->toArray()
            ];
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
     * Users create action method
     *
     * @return void
     */
    public function create()
    {
        $data = $this->request->getParsedData();

        if (empty($data['username']) || empty($data['password'])) {
            $this->send(400, ['code' => 400, 'message' => Response::getMessageFromCode(400)]);
        } else {
            $dupeUser = (new Model\AuthUser())->getByUsername($data['username']);
            if (isset($dupeUser->id)) {
                $this->send(409, ['code' => 409, 'message' => Response::getMessageFromCode(409)]);
            } else {
                $user = new Model\AuthUser();
                $user->save($data);

                if (!empty($user->id)) {
                    $this->send(201, $user->toArray());
                } else {
                    $this->send(400, ['code' => 400, 'message' => Response::getMessageFromCode(400)]);
                }
            }
        }
    }

    /**
     * Users update action method
     *
     * @param  int $id
     * @return void
     */
    public function update($id)
    {
        $data = $this->request->getParsedData();
        $user = (new Model\AuthUser())->getById($id);

        $dupe = Table\AuthUsers::findOne(['username' => $data['username'], 'id!=' => $id]);
        if (isset($dupe->id)) {
            $this->send(409, ['code' => 409, 'message' => Response::getMessageFromCode(409)]);
        } else if (isset($user->id)) {
            $user = new Model\AuthUser();
            $user->update($id, $data);
            $this->send(200, $user->toArray());
        } else {
            $this->send(404, ['code' => 404, 'message' => Response::getMessageFromCode(404)]);
        }
    }

    /**
     * Users delete action method
     *
     * @param  int $id
     * @return void
     */
    public function delete($id = null)
    {
        $data = $this->request->getParsedData();
        $user = new Model\AuthUser();

        if (null !== $id) {
            $user = (new Model\AuthUser())->getById($id);
            if (isset($user->id)) {
                $u = new Model\AuthUser();
                $u->delete($id);
                $code = 204;
            } else {
                $code = 404;
            }
        } else if (isset($data['rm_users']) && is_array($data['rm_users'])) {
            $user->remove($data['rm_users']);
            $code = 204;
        } else {
            $code = 400;
        }

        $this->send($code, ['code' => $code, 'message' => Response::getMessageFromCode($code)]);
    }

}