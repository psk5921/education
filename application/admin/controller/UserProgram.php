<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/23
 * Time: 17:49
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\VipModel;
use think\Exception;

class UserProgram extends Base{


    //用户课程列表
    public function program_list(){

    if(request()->isAjax()){

        $limit = input("limit");
        $page = input('page');
        $name = input('name');
        $start = input('start');
        $end = input('end');
        $where = array();
        $search = array();
        if(!empty($name)){
            //$where[] = ['nickname','like','%'.$name.'%'];
            $search[] = array('u.nickname','like','%'.RemoveXSS($name).'%');
        }
        if ($start){
            $start = strtotime($start);
            $search[] =['a.createtime','>',$start];
        }
        if ($end){
            $end = strtotime($end);
            $search[] =['a.createtime','<',$end];
        }
        $order = 'a.id desc';
        $sel = Db::name('user_course')
            ->alias('a')
            ->join('user u','u.id=a.uid')
            ->join('course_class c','c.id=a.class_id')
            ->join('course_class_package g','a.package_id=g.id')
            ->join('course k','k.id=a.course_id','left')
            ->field('a.*,u.nickname,k.course_title,g.package_name,g.package_time,g.package_month,g.package_price,c.class_name')
            ->where($where)
            ->where($search)
            ->order($order)
            ->limit(ceil($page-1)*$limit,$limit)
            ->select();
        foreach($sel as &$v){
           /* $v['createtime'] = date('Y-m-d h:i:s',$v['createtime']);*/
            $v['end_time'] =  !empty($v['end_time'])?date('Y-m-d',$v['end_time']):'';
            $v['money'] =  round((($v['count']/$v['package_time'])*$v['package_price']),2);
            if($v['status'] == 0){
                $v['status'] = "<span style='color:green'>正常</span>";
            }elseif( $v['status'] = 1){
                $v['status'] = "<span style='color:red'>异常</span>";
            }
            $b_where= ['uid'=>$v['uid'],'deleted'=>0];
            $v['baby_name'] = Db::name('user_baby')->where($b_where)->value('baby_name');
        }
        $count = Db::name('user_course')
            ->alias('a')
            ->join('user u','u.id=a.uid')
            ->join('course_class c','c.id=a.class_id')
            ->join('course_class_package g','a.package_id=g.id')
            ->join('course k','k.id=a.course_id','left')
            ->where($where)
            ->where($search)
            ->count();
        return layui_json(0, '请求成功', $count, $sel);
    }
       return $this->fetch();
    }


    /**
     * 获取相关信息
     */
    public function get_data(){
        if(request()->isPost()){
            $id = (int)input('id');
            $type = (int)input('type');
            if(empty($id) || !in_array($type,[1,2,3])){
                return diy_json('',-1,'参数有误');
            }
            if($type == 1){
                //获取班级
                $where = ['cid'=>$id,'deleted'=>0,'status'=>1];
                $field = 'id,class_name';
                $res = Db::name('course_class')->field($field)->where($where)->select();
                $option = '<option value="0">请选择班级</option>';
                if($res){
                    foreach ($res as $v){
                        $option .= "<option value='".$v['id']."'>".$v['class_name']."</option>";
                    }
                }
                return diy_json($option,1,'请求成功');
            }
            if($type == 2){
                //获取上课信息
                $where = ['class_id'=>$id,'deleted'=>0,'status'=>1];
                $field = 'id,time';
                $res = Db::name('course_class_time')->field($field)->where($where)->select();
                $data = [];
                $option = '';
                if($res){
                    foreach ($res as $v){
                        $r['time_str'] = $v['time'];
                        $r['id'] = $v['id'];
                        $data[] = $r ;
                    }
                }
                if($data){
                    foreach ($data as $v){
                        $option .= "<option value='".$v['id']."'>".$v['time_str']."</option>";
                    }
                }
                return diy_json($option,1,'请求成功');
            }
            if($type == 3){
                //获取套餐信息
                $where = ['class_id'=>$id,'deleted'=>0,'status'=>1];
                $field = 'id,package_name,package_time,package_month,package_price';
                $res = Db::name('course_class_package')->field($field)->where($where)->select();
                $data = [];
                $option = '';
                if($res){
                    foreach ($res as $v){
                        $r['package_str'] = $v['package_name'] . ' ' . $v['package_time'].' 次 '.$v['package_month'].' 月 '.$v['package_price'] .' 套餐';
                        $r['id'] = $v['id'];
                        $data[] = $r;
                    }
                }
                if($data){
                    foreach ($data as $v){
                        $option .= "<option value='".$v['id']."'>".$v['package_str']."</option>";
                    }
                }
                return diy_json($option,1,'请求成功');
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

    //添加用户课程
    public  function add(){

        if(request()->isPost()){

            $data = input('post.');
            $map = ['uid', 'course_id', 'class_id', 'time_id', 'package_id'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            if (!in_array($data['status'], [0, 1])) {
                return diy_json('', -1, '参数有误');
            }
            $where = ['id'=>$data['package_id'],'status'=>1,'deleted'=>0];
            if(!($package = Db::name('course_class_package')->where($where)->find())){
                return diy_json('', -1, '套餐信息有误');
            }
            $data['createtime'] = time();
            $data['count'] = $package['package_time'];
            $data['month'] = $package['package_month'];
            $add = Db::name('user_course')->insert($data);
            //更改会员身份为付费粉丝
            if( Db::name('user')->where(['id'=>$data['uid']])->value('identity') == 0){
                Db::name('user')->where(['id'=>$data['uid']])->update(['identity'=>1]);
            }
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $user_field = 'id,nickname,mobile';
        $user_where = [];
        $user = Db::name('user')->field($user_field)->where($user_where)->select();
        if($user){
            foreach ($user as &$item){
                $item['info'] = (empty($item['nickname'])?'匿名':$item['nickname']) . ' ' .(empty($item['mobile'])?'手机号未绑定':$item['mobile']);
                unset($item['nickname']);
                unset($item['mobile']);
            }
            unset($item);
        }
        $this->assign('users',$user);
        $where = ['status'=>1,'deleted'=>0];
        $course = Db::name('course')->where($where)->select();
        $this->assign('course',$course);

        return $this->fetch();

    }

    //编辑用户课程
    public function update(){
        if(request()->isPost()){
            $data = input('post.');
            $map = ['id'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            if (!in_array($data['status'], [0, 1])) {
                return diy_json('', -1, '参数有误');
            }
            $where = ['id'=>$data['id']];
            if(!($package = Db::name('user_course')->where($where)->find())){
                return diy_json('', -1, '会员课程信息有误');
            }
            unset($data['id']);
            $data['updatetime'] = time();
            $res = Db::name('user_course')->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $id = (int)input('id');
        $where = ['id'=>$id];
        $sel = Db::name('user_course')->where($where)->find();
        if(!$sel){
            $this->error('会员课程信息不存在',url('program_list'));
        }
        $where = ['a.id'=>$id];
        $field = 'a.*,u.nickname,u.mobile,c.class_name,t.time,k.course_title,g.package_name,g.package_time,g.package_month,g.package_price';
        $sels = Db::name('user_course')
            ->alias('a')
            ->join('user u','u.id=a.uid')
            ->join('course_class c','c.id=a.class_id')
            ->join('course_class_package g','a.package_id=g.id')
            ->join('course k','k.id=a.course_id','left')
            ->join('course_class_time t','t.id=a.time_id','left')
            ->where($where)
            ->field($field)
            ->find();
        if($sels){
            $sels['info'] =  (empty($item['nickname'])?'匿名':$item['nickname']) . ' ' .(empty($item['mobile'])?'手机号未绑定':$item['mobile']);
            $sels['package_str'] = $sels['package_name'] . ' ' . $sels['package_time'].' 次 '.$sels['package_month'].' 月 '.$sels['package_price'] .' 套餐';
            /*if ($sels['week_type'] == 1) {
                $week_str = $this->week_convert($sels['week_start']);
                $time_str = $sels['time_start_h'] . ':' . $sels['time_start_m'] . '-' . $sels['time_end_h'] . ':' . $sels['time_end_m'];
            } elseif ($sels['week_type'] == 2) {
                $week_str = $this->week_convert($sels['week_start']) . '至' . $this->week_convert($sels['week_end']);
                $time_str = $sels['time_start_h'] . ':' . $sels['time_start_m'] . '-' . $sels['time_end_h'] . ':' . $sels['time_end_m'];
            } else {
                $week_str = '';
                $time_str = '';
            }*/
            $sels['time_str'] = $sels['time'];
            unset($sels['nickname']);
            unset($sels['mobile']);
            unset($sels['package_name']);
            unset($sels['package_time']);
            unset($sels['package_month']);
            unset($sels['package_price']);
/*            unset($sels['week_start']);
            unset($sels['week_end']);
            unset($sels['time_start_h']);
            unset($sels['time_start_m']);
            unset($sels['time_end_h']);
            unset($sels['time_end_m']);*/
            unset($sels['week_type']);
        }
        $this->assign('sel',$sels);
        return $this->fetch();
    }


    //核销课程
    public function hexiao(){
        if(request()->isPost()){
            //状态正常 课程次数减1 如果是第一次 更改开始时间  结束时间 增加一条核销记录
            try{
                $id = (int)input('id');
                $where = ['id'=>$id,'status'=>0];
                $sel = Db::name('user_course')->where($where)->find();
                if(!$sel){
                    return diy_json('',-1,'会员课程信息不存在');
                }
                if(!empty($sel['end_time']) && $sel['end_time']< time()){
                    return diy_json('',-1,'会员课程已过期,核销失败');
                }
                if($sel['count'] ==0 ){
                    return diy_json('',-1,'会员课程剩余次数为0,核销失败');
                }
                Db::startTrans();
                if(empty($sel['start_time'])){
                    $data['start_time'] = time();
                    $data['end_time'] = strtotime('+ '.$sel['month'].'month');
                }
                $data['count'] = $sel['count'] -1 ;
                Db::name('user_course')->where($where)->update($data);
                $insert = [
                    'uid' => $sel['uid'],
                    'admin_id' => session('uid','','login'),
                    'ucourse_id' => $sel['course_id'],
                    'createtime' => time(),
                    'type' => 1,
                ];
                Db::name('user_verification_log')->insert($insert);
                //todo 写入消息记录
                $openid = Db::name('user')->where(['id'=>$sel['uid']])->value('openid');
                $title = Db::name('course')->where(['id'=>$sel['course_id']])->value('course_title');
                write_msg($openid,'上课提醒','课程名称：'.$title.',消耗课时1.00,剩余课时：'. $data['count'].'.00');
                Db::commit();
                return diy_json('',1,'操作成功');
            }catch (Exception $e){
                Db::rollback();
             return diy_json('',-1,$e->getMessage());
            }

        }
    }
    //删除用户课程
    /*public function delete(){
        if(request()->isajax()){
            $id = (int)input('id');
            $where = ['id'=>$id];
            if(!($package = Db::name('user_course')->where($where)->find())){
                return diy_json('', -1, '会员课程信息有误');
            }
            $res = Db::name('user_course')->delete($id);
            if($res){
                return json(['code'=>1,'msg'=>'删除用户课程成功']);
            }


        }


    }*/


}