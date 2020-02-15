<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/23
 * Time: 19:35
 */

namespace app\admin\controller;

use think\Db;
use app\admin\model\VipModel;
use app\admin\validate\Good as Goodscheck;

class Good extends Base
{
    //商品分类列表
    public function type_ls()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            /* $start = input('start');
             $end = input('end');*/
            $where = ['deleted' => 0];
            $search = [];
            if ($name) {
                $search[] = ['shop_category_name', 'like', "%".$name."%"];
            }
            /*  if ($start){
                  $start = strtotime($start);
                  $where[] =['createtime','>',$start];
              }
              if ($end){
                  $end = strtotime($end);
                  $where[] =['createtime','<',$end];
              }*/
            $order = 'displayorder desc,id desc';
            $sel = Db::name('shop_category')
                ->where($where)
                ->where($search)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->order($order)
                ->select();
            foreach ($sel as $key => &$v) {
                $v['createtime'] = empty($v['createtime']) ? '' : date('Y-m-d H:i:s', $v['createtime']);
                if ($v['status'] == 0) {
                    $v['status'] = "<span style='color:orange'>等待上架</span>";
                } elseif ($v['status'] == 1) {
                    $v['status'] = "<span style='color:green'>上架</span>";
                } elseif ($v['status'] == 2) {
                    $v['status'] = "<span style='color:red'>下架</span>";
                }

            }
            $count = Db::name('shop_category')->where($where)->where($search)->count();
            return layui_json(0, '请求成功', $count, $sel);


        }

        return $this->fetch();

    }


    //添加商品分类
    public function add_type()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if (!isset($data['shop_category_name']) || empty($data['shop_category_name'])) {
                return diy_json('', -1, '请输入分类名称');
            }
            if (!isset($data['status'])) {
                return diy_json('', -1, '缺少必要参数');
            }
            if (!in_array($data['status'], [0, 1, 2])) {
                return diy_json('', -1, '参数有误');
            }
            $data['createtime'] = time();
            $data['displayorder'] = (int)$data['displayorder'];
            $add = Db::name('shop_category')->insert($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        return $this->fetch();

    }

    //编辑商品分类
    public function update_type()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $id = $data['id'];
            $data['updatetime'] = time();
            $where = ['id' => $id, 'deleted' => 0];
            if (!($res = Db::name('shop_category')
                ->where($where)
                ->count(1))) {
                return diy_json('', -1, '分类信息不存在');
            }
            if (!isset($data['shop_category_name']) || empty($data['shop_category_name'])) {
                return diy_json('', -1, '请输入分类名称');
            }
            if (!isset($data['status'])) {
                return diy_json('', -1, '缺少必要参数');
            }
            if (!in_array($data['status'], [0, 1, 2])) {
                return diy_json('', -1, '参数有误');
            }
            $data['displayorder'] = (int)$data['displayorder'];
            $result = Db::name('shop_category')->where($where)->update($data);
            if ($result) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $id = input('id');
        $sel = Db::name('shop_category')
            ->where('id', $id)
            ->find();
        $this->assign('sel', $sel);
        return $this->fetch();

    }


    //删除商品分类
    public function delete()
    {
        if (request()->isAjax()) {

            $id = input('id');
            $where = ['id' => $id, 'deleted' => 0];
            if (!($res = Db::name('shop_category')
                ->where($where)
                ->count(1))) {
                return diy_json('', -1, '分类信息不存在');
            }
            $data = ['updatetime'=>time(),'deleted'=>1];
            $result = Db::name('shop_category')->where($where)->update($data);
            if ($result) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }


    }
    #########################################################################################
    //商品列表
    public function ls()
    {
        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            $start = input('start');
            $end = input('end');
            $where[] =['a.deleted','=',0];
            $search = [];
            if ($name) {
                $search[] = ['a.title', 'like', "%".$name."%"];
            }
            if ($start) {
                $start = strtotime($start);
                $search[] = ['a.createtime', '>', $start];
            }
            if ($end) {
                $end = strtotime($end);
                $search[] = ['a.createtime', '<', $end];
            }
            $order = ['a.displayorder','a.id'=>'desc'];
            $sel = Db::name('shop_goods')
                ->alias('a')
                ->join('shop_category t', 'a.cid =t.id', 'left')
                ->field('a.*,t.shop_category_name,t.status as st')
                ->where('a.deleted','=',0)
                ->where($search)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if($sel){
                foreach ($sel as &$v) {
                    $v['createtime'] = empty($v['createtime']) ? '' : date('Y-m-d H:i:s', $v['createtime']);
                    if ($v['status'] == 0) {
                        $v['status'] = "<span style='color:orange'>等待上架</span>";
                    } elseif ($v['status'] == 1) {
                        $v['status'] = "<span style='color:green'>上架</span>";
                    } elseif ($v['status'] == 2) {
                        $v['status'] = "<span style='color:red'>下架</span>";
                    }
                }
            }

            $count =  Db::name('shop_goods')
                ->alias('a')
                ->join('shop_category t', 'a.cid =t.id', 'left')
                ->field('a.*,t.shop_category_name,t.status as st')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);

        }

        return $this->fetch();


    }


    //添加商品
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['title','cid','img','thumb','price'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['thumb'] = serialize(explode('|',trim($data['thumb'],'|')));
            $data['img'] = trim($data['img'],'|');
            $data['price'] = floatval($data['price']);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            $data['createtime'] = time();
            unset($data['file']);
            $add = Db::name('shop_goods')->insert($data);
            if ($add) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $where = ['deleted'=>0,'status'=>1];
        $types = Db::name('shop_category')->where($where)->select();
        $this->assign('types', $types);
        return $this->fetch();

    }


    //删除商品
    public function del()
    {

        if (request()->isAjax()) {
            $id = (int)input('id');
            $where= ['id'=>$id,'deleted'=>0];
            if(Db::name('shop_goods')->where($where)->count() == 0){
                return diy_json('',-1,'商品信息不存在');
            }
            $update = ['deleted'=>1];
            $del = Db::name('shop_goods')->where($where)->update($update);
            if ($del) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }


        }


    }


    //商品编辑
    public function update()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $id = $data['id'];
            $where = ['id'=>$id,'deleted'=>0];
            if( false == ($good = Db::name('shop_goods')
                ->where($where)
                ->find())){
                return diy_json('',-1,'商品信息不存在');
            }
            $map = ['title','cid','img','thumb','price'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['thumb'] = serialize(explode('|',trim($data['thumb'],'|')));
            $data['img'] = trim($data['img'],'|');
            $data['price'] = floatval($data['price']);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            $data['updatetime'] = time();
            unset($data['file']);
            $update = Db::name('shop_goods')->where($where)->update($data);
            if ($update) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $id = input('id');
        $where = ['id'=>$id,'deleted'=>0];
        $sel = Db::name('shop_goods')
            ->where($where)
            ->find();
        if(!$sel){
            $this->error('商品信息不存在',url('ls'));
        }
        if($sel){
            $sel['thumb'] = unserialize(mb_unserialize( $sel['thumb']));
            $sel['thumbs'] = implode('|',$sel['thumb']);
        }
        $where = ['deleted'=>0,'status'=>1];
        $types = Db::name('shop_category')->where($where)->select();
        $this->assign('types', $types);
        $this->assign('sel', $sel);
        return $this->fetch();


    }



    //商品图片内容上传
    public function uploadGood(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'goods/layedit');
            if($info){
                $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/goods/layedit/'.$info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' =>[
                     'src' => $image_name,
                     'title' => '',
                    ]
                ];
                return  json($json);
            }else{
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' =>[
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return  json($json);
            }
        }
    }


    //商品图片单图上传
    public function simple(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'goods/simple');
            if($info){
                $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/goods/simple/'.$info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' =>[
                        'src' => $image_name,
                        'title' => '',
                    ]
                ];
                return  json($json);
            }else{
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' =>[
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return  json($json);
            }
        }
    }


    //商品图片多图上传
    public function multiple(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $files = request()->file('file');
            $success = [];
            foreach($files as $file){
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'goods/multiple');
                if($info){
                    $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/goods/multiple/'.$info->getSaveName();
                    $success[] = $image_name;
                }
            }
            $json = [
                'code' => 0,
                'msg' => '',
                'data' =>[
                    'src' => empty($success)?'':$success,
                    'title' => '',
                ]
            ];
            return  json($json);
        }
    }
}