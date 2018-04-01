<?php
/**
 * Created by PhpStorm.
 * User: mzh
 * Date: 3/11/18
 * Time: 10:15 PM
 */
namespace app\model;

use think\Model;

class Node extends Model {
    protected $pk = 'id';
    protected $field = ['name', 'href', 'depth', 'structid'];

}