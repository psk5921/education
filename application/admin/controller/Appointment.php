<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/11/8
 * Time: 9:28
 */

namespace app\admin\controller;

use think\Db;
class Appointment  extends  Base
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
            $sel = Db::name('course_appointment')
                ->alias('a')
                ->join('user u', 'u.id = a.uid', 'left')
                ->join('teacher t', 't.id = a.tid', 'left')
                ->join('teacher_freetime tf', 'tf.id = a.free_id', 'left')
                ->field('a.*,u.nickname,t.teacher_name,tf.weekday,tf.time_start_m,tf.time_start_h,tf.time_end_h,tf.time_end_m')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if($sel){
                foreach ($sel as $key => &$v) {
                    $v['createtime'] = date('Y-m-d', $v['createtime']);
                    $v['birth'] = $v['baby_birth_year'].'-'.$v['baby_birth_month'].'-'.$v['baby_birth_day'];
                    $v['tid'] = $v['teacher_name'];
                    $v['uid'] = $v['nickname'];
                    $v['free_id'] = $this->week_convert($v['weekday']).' '.$v['time_start_h'].':'.$v['time_start_m'].'-'.$v['time_end_h'].':'.$v['time_end_m'];
                    if($v['baby_sex'] == 1){
                        $v['baby_sex'] = '男';
                    }elseif($v['baby_sex'] == 2){
                        $v['baby_sex'] = '女';
                    }else{
                        $v['baby_sex'] = '未知';
                    }
                }
            }
            $count = Db::name('course_appointment')
                ->alias('a')
                ->join('user u', 'u.id = a.uid', 'left')
                ->join('teacher t', 't.id = a.tid', 'left')
                ->join('teacher_freetime tf', 'tf.id = a.free_id', 'left')
                ->field('a.*,u.nickname,t.teacher_name,tf.weekday,tf.time_start_m,tf.time_start_h,tf.time_end_h,tf.time_end_m')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        return $this->fetch();
    }
    //查看预约信息
    public function preview(){
        $id = (int)input('id');
        $where = ['id'=>$id,'deleted'=>0];
        if(Db::name('course_appointment')->where($where)->count(1) == 0 ){
            $this->error('预约信息不存在',url('lists'));
        }
        $where = ['a.id'=>$id,'a.deleted'=>0];
        $res = Db::name('course_appointment')
            ->alias('a')
            ->join('user u', 'u.id = a.uid', 'left')
            ->join('teacher t', 't.id = a.tid', 'left')
            ->join('teacher_freetime tf', 'tf.id = a.free_id', 'left')
            ->field('a.*,u.nickname,t.teacher_name,tf.weekday,tf.time_start_m,tf.time_start_h,tf.time_end_h,tf.time_end_m')
            ->where($where)
            ->find();
        if($res){
            $res['birth'] = $res['baby_birth_year'].'-'.$res['baby_birth_month'].'-'.$res['baby_birth_day'];
            $res['tid'] = $res['teacher_name'];
            $res['uid'] = $res['nickname'];
            $res['free_id'] = $this->week_convert($res['weekday']).' '.$res['time_start_h'].':'.$res['time_start_m'].'-'.$res['time_end_h'].':'.$res['time_end_m'];
            if($res['baby_sex'] == 1){
                $res['baby_sex'] = '男';
            }elseif($res['baby_sex'] == 2){
                $res['baby_sex'] = '女';
            }else{
                $res['baby_sex'] = '未知';
            }
        }
        $this->assign('view',$res);
        return   $this->fetch();
    }



    //删除预约信息
    public function deletes(){
        if(request()->isPost()){
            $id = (int)input('id');
            $where = ['id'=>$id,'deleted'=>0];
            if(Db::name('course_appointment')->where($where)->count(1) == 0 ){
                return diy_json('',-1,'预约信息不存在');
            }
            $update = ['deleted'=>1];
            $del = Db::name('course_appointment')->where($where)->update($update);
            if ($del) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
    }

    /**
     * 星期转换
     * @param $num
     * @return bool|string
     */
    final function week_convert($num)
    {
        if (!$num) return false;
        switch ($num) {
            case 1:
                $str = "周一";
                break;
            case 2:
                $str = "周二";
                break;
            case 3:
                $str = "周三";
                break;
            case 4:
                $str = "周四";
                break;
            case 5:
                $str = "周五";
                break;
            case 6:
                $str = "周六";
                break;
            case 7:
                $str = "周天";
                break;
            default:
                $str = '';
        }
        return $str;
    }
}