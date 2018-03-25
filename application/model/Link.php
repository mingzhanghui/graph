<?php
namespace app\model;

use think\Model;

class Link extends Model {
    protected $pk = 'id';
    protected $field = ['des', 'source', 'target', 'type'];


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
}