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
use think\Cookie;
use think\Request;

class User extends BaseController {

    protected $seKey = 'ThinkPHP.CN';

    public function index() {
        // $user = new UserModel();
        echo UserModel::encryptPassword('admin');
    }

    /**
     * 用户注册
     * @param Request $request
     * @return array
     */
    public function register(Request $request) {
        $name = $request->post('name');
        $password = $request->post('password');
        $email = $request->post('email');

        $ret = ['code' => 0, 'data' => null, 'msg' => 'success'];

        // check name, email
        $user = new \app\model\User();
        if ($user->emailExists($email)) {
            $ret['code'] = 1;
            $ret['msg'] = '邮箱已经存在';
            return $ret;
        }
        if ($user->nameExists($name)) {
            $ret['code'] = 2;
            $ret['msg'] = '用户名已经存在';
            return $ret;
        }

        $user->name = $name;
        $user->password = $user->encryptPassword($password);
        $user->email = $email;
        $user->save();

        $ret['data'] = $user->getLastInsID();
        return $ret;
    }

    public function login(Request $request) {
        $name = $request->post('name');
        $password = $request->post('password');

        $user = UserModel::get(function($query) use ($name) {
            $query->where('name', $name);
        });
        if (strcmp($user->encryptPassword($password), $user->password) === 0) {
            Cookie::set('kg_username', $user->name);
            Cookie::set('kg_userid', $user->id);
            return ['code' => 0, 'data'=>$user->id, 'msg' => '登录成功'];
        }
        return ['code'=>1, 'data'=>0, 'msg'=> '用户名或密码错误'];
    }

    public function logout(Request $request) {
        Cookie::delete('kg_username');
        Cookie::delete('kg_userid');
        return ['code' => 0, 'msg' => '注销登录'];
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
        $user = new UserModel();
        $b = $user->emailExists("1335250574@qq.com");
        var_dump($b);
    }
}