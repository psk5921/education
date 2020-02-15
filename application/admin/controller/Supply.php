<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/11/8
 * Time: 14:02
 */

namespace app\admin\controller;

use think\Db;
class Supply  extends  Base
{
    //列表数据
    public function lists(){
        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            $start = input('start');
            $end = input('end');
            $where = ['a.deleted' => 0];
            $search = [];
            if ($name) {
                $search[] = array('u.nickname', 'like', "%" . RemoveXSS($name) . "%");
            }
            if ($start){
                $start = strtotime($start);
                $search[] =['a.createtime','>',$start];
            }
            if ($end){
                $end = strtotime($end);
                $search[] =['a.createtime','<',$end];
            }
            $order = "a.id desc";
            $sel = Db::name('teacher_supply')
                ->alias('a')
                ->join('user u', 'u.id = a.uid', 'left')
                ->field('a.*,u.nickname')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if($sel){
                foreach ($sel as $key => &$v) {
                    $v['createtime'] = date('Y-m-d', $v['createtime']);
                    $v['uid'] = $v['nickname'];
                    if($v['work_type'] == 1){
                        $v['work_type'] = '兼职 part time';
                    }elseif($v['work_type'] == 2){
                        $v['work_type'] = '全职 full time';
                    }else{
                        $v['work_type'] = '未知 unknown';
                    }
                    if($v['contact_way'] == 1){
                        $v['contact_way'] = '微信 Wechat';
                    }elseif($v['contact_way'] == 2){
                        $v['contact_way'] = '手机号 Telephone';
                    }elseif($v['contact_way'] == 3){
                        $v['contact_way'] = '邮箱 Mail';
                    }else{
                        $v['contact_way'] = '未知 unknown';
                    }
                    if($v['avaliable_time'] == 1){
                        $v['avaliable_time'] = '早上 Morning';
                    }elseif($v['avaliable_time'] == 2){
                        $v['avaliable_time'] = '晚上 Evening';
                    }elseif($v['avaliable_time'] == 3){
                        $v['avaliable_time'] = '中午 Noon';
                    }else{
                        $v['avaliable_time'] = '未知 unknown';
                    }
                }
            }
            $count = Db::name('teacher_supply')
                ->alias('a')
                ->join('user u', 'u.id = a.uid', 'left')
                ->field('a.*,u.nickname')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        return $this->fetch();
    }
    //查看申请信息
    public function preview(){
        $id = (int)input('id');
        $where = ['id'=>$id,'deleted'=>0];
        if(Db::name('teacher_supply')->where($where)->count(1) == 0 ){
            $this->error('教师申请信息不存在',url('lists'));
        }
        $where = ['a.id'=>$id,'a.deleted'=>0];
        $res = $sel = Db::name('teacher_supply')
            ->alias('a')
            ->join('user u', 'u.id = a.uid', 'left')
            ->field('a.*,u.nickname')
            ->where($where)
            ->find();;
        if($res){
            $res['uid'] = $res['nickname'];
            if($res['work_type'] == 1){
                $res['work_type'] = '兼职 part time';
            }elseif($res['work_type'] == 2){
                $res['work_type'] = '全职 full time';
            }else{
                $res['work_type'] = '未知 unknown';
            }
            if($res['contact_way'] == 1){
                $res['contact_way'] = '微信 Wechat';
            }elseif($res['contact_way'] == 2){
                $res['contact_way'] = '手机号 Telephone';
            }elseif($res['contact_way'] == 3){
                $res['contact_way'] = '邮箱 Mail';
            }else{
                $res['contact_way'] = '未知 unknown';
            }
            if($res['avaliable_time'] == 1){
                $res['avaliable_time'] = '早上 Morning';
            }elseif($res['avaliable_time'] == 2){
                $res['avaliable_time'] = '晚上 Evening';
            }elseif($res['avaliable_time'] == 3){
                $res['avaliable_time'] = '中午 Noon';
            }else{
                $res['avaliable_time'] = '未知 unknown';
            }
            if($res['images']){
                $res['images'] = explode('|',trim($res['images'],'|'));
            }
        }
        $this->assign('view',$res);
        return   $this->fetch();
    }



    //删除申请信息
    public function deletes(){
        if(request()->isPost()){
            $id = (int)input('id');
            $where = ['id'=>$id,'deleted'=>0];
            if(Db::name('teacher_supply')->where($where)->count(1) == 0 ){
                return diy_json('',-1,'教师申请信息不存在');
            }
            $update = ['deleted'=>1];
            $del = Db::name('teacher_supply')->where($where)->update($update);
            if ($del) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }

    }
}