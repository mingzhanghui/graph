<?php
namespace app\model;

use think\Model;

class Link extends Model {
    protected $pk = 'id';
    protected $field = ['des', 'source', 'target', 'type'];

}