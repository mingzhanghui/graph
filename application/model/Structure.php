<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 4/1/18
 * Time: 17:48
 */
namespace app\model;

use think\Model;

class Structure extends Model {
    protected $pk = 'id';
    protected $field = ['id', 'name', 'info'];

}