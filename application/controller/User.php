<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 10/14/18
 * Time: 1:04 PM
 */

namespace app\controller;

use \app\model\User as UserModel;
use think\captcha\Captcha;
use think\Request;
use think\Session;

class User extends BaseController {

    protected $seKey = 'ThinkPHP.CN';

    public function index() {
        // $user = new UserModel();
        echo UserModel::encryptPassword('admin');
    }

    public function register(Request $request) {
        $name = $request->post('name');
        $password = $request->post('password');
        $email = $request->post('email');

        // $user = User

    }

    public function captcha() {
        $config = [
            'length' => 6,
            'imageW' => 200,
            'imageH' => 60,
            'useCurve'=> 0,
            'fontSize' => 20
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    public function test(Request $request) {
        $code = $request->input('code');
        Session::boot();
        $b = (new Captcha())->check($code, '');
        var_dump($b);
    }
}