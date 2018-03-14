<?php
namespace app\controller;

use app\model\Node;
use app\model\Link;

class Index {
    public function index() {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    public function api() {
        $data = ['name'=>'thinkphp','url'=>'thinkphp.cn'];
        return json_encode(['data'=>$data,'code'=>1,'message'=>'操作完成']);
    }

    public function getNode($id) {
        return Node::get($id);
    }


    public function listNode() {
        $list = Node::all();
        array_walk($list, function($node) {
           $index = $node['depth'];
            // $node['course'] = ($index==0) ? 0 : $node['id'];
           if ($index==0) {
               $node['course'] = "0";
               $node['fixed'] = true;
               $node['href'] = 'http://lib.csdn.net/my/structure/PHP';
               $node['x'] = 560;
               $node['y'] = 480;
           } else {
               $id = $node['id'];
               $node['course'] = $id;
               $node['href'] = 'http://lib.csdn.net/my/structure/PHP/Node/'.$id;  // fixme
           }
           $node['index'] = $index;
           unset($node['depth']);

           $name = $node['name'];
           unset($node['name']);
           $node['prop'] = array(
               'course' => $node['id'],
               'nText'  => 1,  // fixme
               'name'   => $name,
               'subject'=> ''
           );
        });
        return $list;
    }

    /**
     * node relative id relationship
     * @return false|static[]
     */
    public function listLink() {
        $nodes = Node::all();
        $nodeIdList = array();
        foreach ($nodes as $i => $node) {
            $nodeIdList[$i] = $node['id'];
        }
        unset($nodes);
        $nodeIdList = array_flip($nodeIdList);

        $links = Link::all();
        array_walk($links, function($link, $key, $map) {
            $link['desc'] = $link['des'];
            unset($link['des'], $link['id']);
            $link['source'] = $map[$link['source']];
            $link['target'] = $map[$link['target']];
        }, $nodeIdList);

        return $links;
    }
}
