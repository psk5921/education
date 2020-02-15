<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22
 * Time: 9:16
 */
namespace app\admin\controller;
use think\Db;
use app\admin\validate\Package as Packagecheck;
class Package extends Base{
    //套餐列表
    public function ls(){

        if(request()->isAjax()){

            $limit = input("limit");
            $page = input('page');
            $name = input('name');
           /* $start = input('start');
            $end = input('end');*/
            $where = ['a.deleted'=>0];
            if($name){
                $where[] = array('a.package_name','like',"%".RemoveXSS($name)."%");
            }
            /*if ($start){
                $start = strtotime($start);
                $where[] =['a.createtime','>',$start];
            }
            if ($end){
                $end = strtotime($end);
                $where[] =['a.createtime','<',$end];
            }*/
            $order ='a.displayorder desc,a.id desc';
            $sel = Db::name('course_class_package')
                ->alias('a')
                ->join('course_class c','a.class_id = c.id','left')
                ->field('a.*,c.class_name')
                ->where($where)
                ->limit(ceil($page-1)*$limit,$limit)
                ->order($order)
                ->select();
            foreach ($sel as $key=>&$v){
                $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
                $v['package_month'] = $v['package_month'].'个月';
                $v['package_time'] = $v['package_time'].'次';
                if($v['status'] == 0){
                    $v['status'] =  "<span style='color:orange'>等待上架</span>";
                }elseif ($v['status'] == 1){
                    $v['status'] =  "<span style='color:green'>上架</span>";
                }elseif($v['status'] == 2){
                    $v['status'] = "<span style='color:red'>下架</span>";
                }


            }
            $count = Db::name('course_class_package')
                ->alias('a')
                ->join('course_class c','a.class_id = c.id','left')
                ->where($where)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }

        return $this->fetch();
    }





    //编辑套餐
    public function package_update(){
        if(request()->isPost()){
            $data = input('post.');
            $where = ['id'=>$data['id'],'deleted'=>0];
            if(Db::name('course_class_package')->where($where)->count(1)==0){
                return diy_json('',-1,'套餐信息不存在');
            }
            $map = ['class_id','package_name','package_time','package_month','package_price',];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['package_time'] = (int)$data['package_time'];
            $data['package_month'] = (int)$data['package_month'];
            $data['package_price'] = floatval($data['package_price']);
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            $data['updatetime'] = time();
            $add = Db::name('course_class_package')->where($where)->update($data);
            if($add){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }

        }
        $id = input('id');
        $sel = Db::name('course_class_package')->where(['id'=>$id])->find();
        $class = Db::name('course_class')->select();
        $this->assign('class',$class);
        $this->assign('sel',$sel);
        return $this->fetch();
    }


    //添加套餐
    public function  package_add(){
        if(request()->isPost()){
            $data = input('post.');
            $map = ['class_id','package_name','package_time','package_month','package_price',];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['package_time'] = (int)$data['package_time'];
            $data['package_month'] = (int)$data['package_month'];
            $data['package_price'] = floatval($data['package_price']);
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            $data['createtime'] = time();
            $add = Db::name('course_class_package')->insert($data);
            if($add){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $class = Db::name('course_class')->select();
        $this->assign('class',$class);
        return $this->fetch();
    }

    //删除套餐
    public function package_delete(){
        if(request()->isAjax()){
            $id = input('id');
            $where = ['id'=>$id,'deleted'=>0];
            if(Db::name('course_class_package')->where($where)->count(1)==0){
                return diy_json('',-1,'套餐信息不存在');
            }
            $update = ['deleted'=>1];
            $del = Db::name('course_class_package')->where($where)->update($update);
            if($del){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }

        }



    }


}