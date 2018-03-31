<?php
namespace app\controller;

use app\model\Content;
use app\model\Node;
use app\model\Link;
use think\Controller;

class Index extends Controller {
    private $structid;  // 当前的知识图谱ID (structure id)

    public function _initialize() {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->structid = $this->request->param('structid');  // structure id
    }

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

        $list = Node::all(['structid'=>$this->structid]);
        array_walk($list, function($node, $key, $structid) {
           $index = $node['depth'];
            // $node['course'] = ($index==0) ? 0 : $node['id'];
           if ($index==0) {
               $node['course'] = "0";
               $node['fixed'] = true;
               $node['href'] = 'structure.html?structid='.$structid;
               $node['x'] = 560;
               $node['y'] = 480;
           } else {
               $id = $node['id'];
               $node['course'] = $id;
               $node['href'] = 'structure.html?structid='.$structid.'&node='.$id;  // fixme
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
        }, $this->structid);
        return $list;
    }

    /**
     * node relative id relationship
     * @return false|static[]
     */
    public function listLink() {
        $nodes = Node::all(['structid' => $this->structid]);
        $nodeIdList = array();
        foreach ($nodes as $i => $node) {
            $nodeIdList[$i] = $node['id'];
        }
        unset($nodes);
        $nodeIdList = array_flip($nodeIdList);

        $links = Link::all(['structid' => $this->structid]);
        array_walk($links, function($link, $key, $map) {
            $link['desc'] = $link['des'];
            unset($link['des'], $link['id']);
            $link['source'] = $map[$link['source']];
            $link['target'] = $map[$link['target']];
        }, $nodeIdList);

        return $links;
    }

    /**
     * 图谱名称, root node url, 内容条数
     */
    public function structInfo() {
        $node = Node::get([
            'depth' => 0,
            'structid' => $this->structid,
        ]);

        $nodes = Node::all(['structid' => $this->structid]);
        $n = 0;
        $content = new Content();
        foreach($nodes as $v) {
            $nodeid = $v->getAttr('id');
            $n += $content->where('nodeid', $nodeid)->count();
        }
        return [
            'title' => $node->getAttr('name'),
            'url'   => $node->getAttr('href'),
            'n'     => $n
        ];
    }

    /**
     * http://lib.csdn.net/my/structure/PHP
     * node树形结构列表
     * http://localhost/d3.js/graph/public/index.php/Index/nodeTree?structid=1
     */
    public function nodeTree() {
        $where = ['structid' => $this->structid];
        $nodes = Node::all($where);

        $links = Link::all($where);
        $map = [];
        foreach ($links as $link) {
            $map[$link->getAttr('target')] = $link->getAttr('source');
        }
        unset($links);

        $data = [];
        foreach ($nodes as $node) {
            $id = $node->getAttr('id');
            $href = NULL;
            array_push($data, [
                'id'       => $id,
                'name'     => $node->getAttr('name'),
                'parentid' => array_key_exists($id, $map) ? $map[$id] : 0,
                'depth'    => $node->getAttr('depth'),
                'href'      => is_null($href = $node->getAttr('href')) ?
                    'structure.html?structid='.$this->structid.'&node='.$id : $href
            ]);
        }
        unset($nodes);

        return Link::buildTree($data);
    }

    /**
     * 当前节点，以及所有子节点(2层)对应的知识点内容
     * http://localhost:8000/d3.js/graph/public/index.php/Index/getContentByNodeId?nodeid=3
     */
    public function getContentByNodeId() {
        $nodeid = $this->request->param('nodeid');
        $content = new Content();
        $a = $content->getContentByNodeId($nodeid);

        $link = new Link();
        $childId = $link->listChildNodeId_r($nodeid);
        $list = $content->getContentByNodeIdList($childId);

        return array_merge($a, $list);
    }

    /**
     * 在nodeid节点下添加内容
     * @return array
     */
    public function contentAdd() {
        $content = new Content;
        $content->nodeid = $this->request->param('nodeid');
        $content->name = $this->request->param('name');
        $content->url = $this->request->param('url');
        $content->save();
        return ['id' => $content->id];  // get last insert id
    }

    /**
     * 删除一条知识内容
     * @param $id  int
     * @return array
     */
    public function contentDel($id) {
        // $id = $this->request->param('id');
        return [
            'id'   => $id,
            'count'=> Content::destroy($id)
        ];
    }

    /**
     * 编辑知识内容
     */
    public function contentEdit() {
        $id = $this->request->param('id');
        $name = $this->request->param('name');
        $url = $this->request->param('url');

        $content = Content::get($id);
        $content->name = $name;
        $content->url = $url;
        return ['c' => $content->save()];
    }

    /**
     * 移动知识内容
     */
    public function contentMove() {
        $id = $this->request->param('id');
        $nodeid = $this->request->param('nodeid');
        $content = new Content();
        $c = $content->save([
            'nodeid' => $nodeid
        ], ['id' => $id]);
        return ['c' => $c];
    }

    public function test() {
        \think\Config::set('default_return_type','html');
    }

}
