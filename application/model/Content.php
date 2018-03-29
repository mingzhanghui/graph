<?php
namespace app\model;

use think\Model;

class Content extends Model {
    protected $pk = 'id';
    protected $field = ['nodeid', 'name', 'url'];

    /**
     * 取得nodeid对应的一个或多个content记录
     */
    public function getContentByNodeId($nodeid) {
        $where = ['nodeid' => $nodeid];
        $l = $this->where($where)->select();
        $list = [];
        foreach ($l as $c) {
            $kv = [];
            $kv['id'] = $c->getAttr('id');
            foreach ($this->field as $field) {
                $kv[$field] = $c->getAttr($field);
            }
            array_push( $list, $kv);
        }
        return $list;
    }

    /**
     * 取得nodeid数组对应的content记录
     */
    public function getContentByNodeIdList($a) {
        $list = [];
        foreach ($a as $nodeid) {
            $where = ['nodeid' => $nodeid];
            $l = $this->where($where)->select();
            if (is_array($l)) {
                foreach ($l as $c) {
                    array_push($list, [
                        'id'     => $c->getAttr('id'),
                        'nodeid' => $c->getAttr('nodeid'),
                        'name'   => $c->getAttr('name'),
                        'url'    => $c->getAttr('url')
                    ]);
                }
            }
        }
        return $list;
    }
}