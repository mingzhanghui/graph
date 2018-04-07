<?php
namespace app\model;

use think\Model;

class Link extends Model {
    protected $pk = 'id';
    protected $field = ['des', 'source', 'target', 'structid'];


    /**
     * @param $items
     * $items = array(
     *            array('id' => 42, 'parentid' => 1),
     *            array('id' => 43, 'parentid' => 42),
     *            array('id' => 1,  'parentid' => 0));
     * @return mixed
     * Array (
     *   [0] => Array(
     *     [id] => 1
     *     [parentid] => 0
     *     [childs]   => Array(
     *       [0] => Array (
     *          [id] => 42
     *          [parentid] => 1
     *          [childs] => Array(
     *             [0] => Array(
     *                [id] => 43
     *                [parentid] => 42
     *             )
     *          )
     *       )
     *     )
     *   )
     * )
     */
    public static function buildTree($items) {
        $childs = array();

        foreach($items as &$item) {
            $childs[$item['parentid']][] = &$item;
            unset($item);
        }
        foreach($items as &$item) {
            if (isset($childs[$item['id']])) {
                $item['childs'] = $childs[$item['id']];
            }
            unset($item);
        }
        return $childs[0];
    }

    /**
     * 节点id下一级节点id数组
     */
    public function listChildNodeId_r($nodeid) {
        $a = $this->listChildNodeId($nodeid);
        $list = [];  // recursively
        foreach ($a as $it) {
            array_push($list, $it);
            $ca = $this->listChildNodeId($it);
            foreach ($ca as $ci) {
                array_push($list, $ci);
            }
        }
        return $list;
    }

    // 节点id下一级节点id数组
    private function listChildNodeId($parentid) {
        $where = ['source' => $parentid];
        $a = $this->field('target')->where($where)->select();
        $t = [];
        foreach ($a as $item) {
            array_push($t, $item['target']);
        }
        unset($a);
        return $t;
    }

}