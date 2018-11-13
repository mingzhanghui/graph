<?php
namespace app\controller;

use app\model\Content;
use app\model\Node;
use app\model\Link;
use app\model\Structure;
use think\Controller;
use think\Cookie;
use think\Request;
use think\Response;

class Index extends BaseController {
    private $structid;  // 当前的知识图谱ID (structure id)

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function _initialize() {
        parent::_initialize();
        $this->structid = $this->request->param('structid');  // structure id
    }

    public function index() {
        \think\Config::set('default_return_type','html');
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    public function getNode($id) {
        return Node::get($id);
    }

    /**
     * list structure name & content count
     * @return false|static[]
     */
    public function listStructures(Request $request) {
        $userid = $request->get('userid');

        $list = Structure::all(function($query) use ($userid) {
            $query->where('userid', $userid)->order('id', 'desc');
        });
        foreach ($list as $i => $struct) {
            $structid = $struct->getAttr('id');
            $nodelist = Node::all(['structid' => $structid]);
            $nodeids = [];
            foreach ($nodelist as $node) {
                array_push($nodeids, $node->getAttr('id'));
            }
            $content = new Content();
            $cc = 0;
            foreach($nodeids as $nodeid) {
                $cc += $content->where('nodeid', $nodeid)->count();
            }
            $list[$i]->setAttr('count', $cc);
        }

        return $list;
    }

    /**
     * 用于图谱预览: 列出所有节点
     * @return false|static[]
     */
    public function listNode() {

        $list = Node::all(['structid'=>$this->structid]);
        array_walk($list, function($node, $key, $structid) {
            $index = $node['depth'];
            // $node['course'] = ($index==0) ? 0 : $node['id'];
            $id = $node['id'];
            if ($index==0) {
                $node['course'] = "0";
                $node['fixed'] = true;
                $node['href'] = 'structure.html?structid='.$structid;
                $node['x'] = 500;  // root node position x
                $node['y'] = 400;  // root node position y
            } else {
                $node['course'] = $id;
                $node['href'] = 'structure.html?structid='.$structid.'&node='.$id;  // fixme
            }
            $node['index'] = $index;
            unset($node['depth']);

            $name = $node['name'];
            unset($node['name']);

            // sum node contents count
            $content = new Content();
            $link = new Link();
            $childId = $link->listChildNodeId_r($id);
            $list = $content->getContentByNodeIdList($childId);
            $a = $content->getContentByNodeIdList([$id]);

            $node['prop'] = array(
                'course' => $node['id'],
                'nTxt'  => count($list)+count($a),
                'name'   => $name,
                'subject'=> ''
            );
        }, $this->structid);
        return $list;
    }

    /**
     * 用于图谱预览: 节点之间相对id
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
            $link['type'] = 'REL';
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

        $rootid = 0;
        foreach($nodes as $v) {
            $nodeid = $v->getAttr('id');
            $n += $content->where('nodeid', $nodeid)->count();

            $depth = $v->getAttr('depth');
            if (0==$depth) {
                $rootid = $nodeid;
            }
        }
        $struct = Structure::get($this->structid);
        return [
            'title' => $node->getAttr('name'),
            'url'   => $node->getAttr('href'),
            'info'  => $struct->getAttr('info'),
            'time'  => $struct->getAttr('update_time'),
            'n'     => $n,
            'rootid'=> $rootid
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
                'href'     => is_null($href = $node->getAttr('href')) ?
                    'structure.html?structid='.$this->structid.'&node='.$id : $href
            ]);
        }
        unset($nodes);

        $a = Link::buildTree($data);
        return $a[0];
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
     * 当前节点(包括下面2级子节点)下的内容计数
     * node id => count content
     */
    public function countContentByNodeIdList() {
        $sa = $this->request->post('a');
        $a = json_decode($sa);
        $content = new Content();
        $link = new Link();

        $ca = [];

        foreach ($a as $nodeid) {
            $childId = $link->listChildNodeId_r($nodeid);
            if (count($childId)<1) {
                array_push($ca, $content->countContentByNodeId($nodeid));
            } else {
                $n = 0;
                foreach($childId as $id) {
                    $n += $content->countContentByNodeId($id);
                }
                array_push($ca, $n);
            }
        }
        return $ca;
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

    /**
     * 创建知识图谱
     */
    public function createStructure() {
        $data = $this->request->param('treedata');
        $tree = json_decode($data, true);

        $rootname = $tree['title'];
        // \think\Config::set('default_return_type','html');
        $count = Structure::where('name', $rootname)->count();
        if (0 < $count) {
            return [
                'status' => 1,
                'id'    => 0,
                'title' => $rootname,
                'err'   => '图谱名称'.$rootname.'已存在',
                'url'   => ''
            ];
        }

        $struct = new Structure();
        $struct->name = $rootname;
        $struct->info = $tree['description'];
        $struct->save();
        $structid = $struct->id;

        $root = new Node();
        $url = 'structure.html?structid='.$structid.'&name='.$rootname;
        $root->data([
            'name' => $rootname,
            'href'=> $url,
            'depth'=>0,
            'structid'=>$structid
        ])->save();
        $rootid = $root->id;

        foreach ($tree['dom'] as $v1) {
            $node = new Node();
            $node->data([
                'name' => $v1['title'],
                'depth'=> 1,
                'structid' => $structid
            ])->save();
            $n1_id = $node->id;
            $link = new Link();
            $link->structid = $structid;
            $link->source = $rootid;
            $link->target = $n1_id;
            $link->save();
            if (array_key_exists('dom', $v1)) {
                foreach ($v1['dom'] as $v2) {
                    $node = new Node();
                    $node->data([
                        'name'    => $v2['title'],
                        'depth'   => 2,
                        'structid'=>$structid
                    ])->save();
                    $n2_id = $node->id;
                    $link = new Link();
                    $link->data([
                        'structid' => $structid,
                        'source'   => $n1_id,
                        'target'   => $n2_id
                    ])->save();
                    if (array_key_exists('dom', $v2)) {
                        foreach ($v2['dom'] as $v3) {
                            $node = new Node();
                            $node->data([
                                'name' => $v3['title'],
                                'depth'=> 3,
                                'structid' => $structid,
                            ])->save();
                            $n3_id = $node->id;
                            $link = new Link();
                            $link->data([
                                'structid' => $structid,
                                'source'   => $n2_id,
                                'target'   => $n3_id
                            ])->save();
                        }
                    }
                }
            }
        }
        return [
            'status' => 0,
            'id'    => $structid,
            'title' => $rootname,
            'err'   => '',
            'url'   => $url
        ];
    }

    /**
     * prepare 删除知识图谱
     * @return array
     */
    public function promptDeleteStructure() {
        $structid = $this->structid;
        $title = urldecode($this->request->get('title'));
        $struct = Structure::get($structid);

        $ret = ['code'=>1, 'msg'=>'图谱名称不符'];
        if ($struct->getAttr('name')==$title) {
            $a = $this->deleteStructure($structid);
            $ret['code'] = 0;
            $ret['msg'] = json_encode($a);
            return $ret;
        }
        return $ret;
    }
    /**
     * 删除知识图谱 返回每个数据表删除的条数
     * {"content":1,"link":9,"node":10,"structure":1}
     */
    private function deleteStructure($structid) {
        // content
        $nodes = Node::all(['structid' => $structid]);
        $getlistid = function() use (&$nodes) {
            $a = [];
            foreach ($nodes as $node) {
                array_push($a, $node->getAttr('id'));
            }
            return $a;
        };
        $nodeids = $getlistid();


        $n0 = 0; $n1 = 0;
        foreach ($nodeids as $nodeid) {
            // content
            $n0 += Content::destroy(['nodeid' => $nodeid]);
            // link
            $n1 += Link::destroy(['target' => $nodeid]);
        }
        // $n1 = Link::destroy(['structid' => $structid]);
        // node
        $n2 = 0;
        foreach ($nodeids as $id) {
            $node = Node::get($id);
            $n2 += $node->delete();
        }
        // structure
        $n3 = Structure::destroy($structid);

        return [
            'content' => $n0,
            'link' => $n1,
            'node' => $n2,
            'structure'=>$n3
        ];
    }

    /**
     * 编辑图谱
     * 新增node     {"title": "php",	"status": true} 该node没有"nid"字段表示是新增的node, 有status=true
     * 修改node名称 {"title": "linux111", "nid": 19046942,"status": true}, 带有nid字段，这个节点在数据库node表中的id，和status=true
     * node不变     {"title": "apache","nid": 19046943} 有nid字段，没有status字段
     */
    public function editStructure() {
        $d = $this->request->post('treedata');
        $treedata = json_decode($d, true);
        $structid = $this->structid;

        $rootid = $treedata['nid'];
        $title = $treedata['title'];

        // Structure
        $struct = Structure::get($structid);
        // structure name has changed
        if (strcmp($struct->name, $title)!==0) {
            $s = Structure::get(['name'=>$title]);
            if (is_null($s)) {
                $struct->name = $title;  // update structure name
            } else {
                return ['code'=>1, 'msg'=>'图谱名称已存在#'.$s->id];
            }
        }
        $struct->info = $treedata['description'];
        $struct->save();

        // Node
        $root = new Node();
        $root->save(['name'=>$title], ['id' => $rootid]);

        $dom1s = $treedata['dom'];
        // depth=1
        foreach ($dom1s as $dom1) {
            // node changed?
            if (array_key_exists('status', $dom1) && $dom1['status']=="true") {
                if (array_key_exists('nid', $dom1)) {
                    // update node
                    $nid1 = $dom1['nid'];
                    $node1 = Node::get($nid1);
                    $node1->name = $dom1['title'];
                    $node1->save();
                } else {
                    // insert node
                    $node1 = Node::create([
                        'name'  => $dom1['title'],
                        'depth' => 1,
                        'structid' => $structid
                    ]);
                    $nid1 = $node1->id;   // last insert id
                    // insert link
                    Link::create([
                        'source' => $rootid,
                        'target' => $nid1,
                        'structid' => $structid
                    ]);
                }
            } else {
                $nid1 = $dom1['nid'];
            }
            // depth = 2
            if (array_key_exists('dom', $dom1)) foreach ($dom1['dom'] as $dom2) {
                // node changed?
                if (array_key_exists('status', $dom2) && $dom2['status']=="true") {
                    if (array_key_exists('nid', $dom2)) {
                        // update node
                        $nid2 = $dom2['nid'];
                        $node2 = Node::get($nid2);
                        $node2->name = $dom2['title'];
                        $node2->save();
                    } else {
                        // insert Node
                        $node2 = Node::create([
                            'name' => $dom2['title'],
                            'depth' => 2,
                            'structid' => $structid
                        ]);
                        $nid2 = $node2->id;
                        Link::create([
                            'source' => $nid1,
                            'target' => $nid2,
                            'structid' => $structid
                        ]);
                    }
                } else {
                    $nid2 = $dom2['nid'];
                }
                // depth = 3
                if (array_key_exists('dom', $dom2)) foreach ($dom2['dom'] as $dom3) {
                    // node changed?
                    if (array_key_exists('status', $dom3) && $dom3['status']=="true") {
                       if (array_key_exists('nid', $dom3)) {
                           $nid3 = $dom3['nid'];
                           $node3 = Node::get($nid3);
                           $node3->name = $dom3['title'];
                           $node3->save();
                       } else {
                           $node3 = Node::create([
                               'name' => $dom3['title'],
                               'depth' => 3,
                               'structid' => $structid
                           ]);
                           $nid3 = $node3->id;
                           Link::create([
                               'source' => $nid2,
                               'target' => $nid3,
                               'structid' => $structid
                           ]);
                       }
                    }
                    // else {$nid3 = $dom3['nid'];}
                }
            }
        }
        return ['code'=>0, 'msg'=>'success'];
    }

    /**
     * prepare delete node  这个node以及child nodes关联有知识内容content ? 不能删除 : 直接删除node，不等到按提交按钮
     */
    public function deleteNode() {
        $ret = ['code'=>0, 'count'=>0, 'contents' => []];

        $nodeid = $this->request->get("nodeid");

        $content = new Content();
        $a = $content->getContentByNodeId($nodeid);

        $link = new Link();
        $childId = $link->listChildNodeId_r($nodeid);
        $list = $content->getContentByNodeIdList($childId);

        $contents = array_merge($a, $list);
        // 该节点下仍保存有相关知识内容, 削除不可.
        if (0<count($contents)) {
            $ret['code'] = 1;  // operation
            $ret['contents'] = $list;
            return $ret;
        }
        array_push($childId, intval($nodeid));
        $ret['nodeIdList'] = $childId;

        return $ret;
    }

    /**
     * do delete nodes
     */
    public function doDelNodes() {
        $c = $this->request->post('nodeIdList');
        $ret = ['code'=>0, 'count'=>0];

        $childId = json_decode($c);
        if (is_array($childId)) {
            foreach ($childId as $id) {
                // delete links
                Link::destroy(['target' => $id]);
                // delete nodes
                $ret['count'] += Node::destroy($id);
            }
        } else {
            $ret['code'] = 1;  // Unexpected nodeid list
        }
        return $ret;
    }
}
