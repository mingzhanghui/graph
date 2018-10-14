<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 10/14/18
 * Time: 1:04 PM
 */

namespace app\controller;

use \app\model\User as UserModel;

class User extends BaseController {
    public function index() {
        // $user = new UserModel();
        echo UserModel::encryptPassword('admin');
    }
}