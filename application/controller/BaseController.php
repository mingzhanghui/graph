<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 10/14/18
 * Time: 12:50 AM
 */

namespace app\controller;

use think\Controller;
use think\Request;
use think\Response;

class BaseController extends Controller {

    /** @var array */
    private $headers;

    public function __construct(Request $request) {
        parent::__construct($request);

        $this->headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'x-requested-with,content-type',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age' => 1728000
        ];

        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';

        $this->headers['Access-Control-Allow-Origin'] = $origin;
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
    }
}