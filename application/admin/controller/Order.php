<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24
 * Time: 18:25
 */

namespace app\admin\controller;

use think\Db;
use think\Exception;
class Order extends Base
{


    /**
     * 商城订单
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_order_ls()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name'); //订单编号
            $start = input('start');
            $end = input('end');
            $status = input('status');
            $where = [];
            $date = [];
            if (!empty($name)) {
                $where[] = ['a.ordersn', 'like', '%' . $name . '%'];
            }
            if ($status == -1) {
                $where[] = ['a.status', '=', 0];
                // $where[] = ['status','<',5];
            } elseif (empty($status)) {
                $where[] = ['a.status', '<', 5];
            } else {
                $where[] = ['a.status', '=', $status];
            }
            $today = strtotime(date('Y-m-d')); //今天
            if (!empty($start) && !empty($end)) {
                if (strtotime($start) > strtotime($end) && strtotime($start) < strtotime($today)) {
                    $date[] = ['a.createtime', 'between', [strtotime($start), strtotime($today)]];
                } elseif (strtotime($start) > strtotime($end) && strtotime($start) > strtotime($today)) {
                    $date[] = ['a.createtime', 'between', [strtotime($today), strtotime($start)]];
                } elseif (strtotime($start) < strtotime($end)) {
                    $date[] = ['a.createtime', 'between', [strtotime($start), strtotime($end)]];
                }
            }
            // $order = ['id'=>'desc'];
            $sel = Db::name('shop_order')
                ->alias('a')
                ->join('user u', 'a.uid=u.id', 'left')
                ->field('a.id,a.ordersn,u.nickname,a.total,a.price,a.fee,a.is_invite,a.status,a.createtime')
                ->where($where)
                ->where($date)
                ->order('a.id', 'desc')
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if ($sel) {
                foreach ($sel as &$v) {
                    $v['createtime'] = date('Y-m-d', $v['createtime']);
                    /*$v['is_invite'] == 0 && $v['is_invite']='配送';
                    $v['is_invite'] == 1 && $v['is_invite']='自取';*/
                    $v['is_invite'] = '配送';
                    $id = $v['id'];
                    switch ($v['status']) {
                        case  0:
                            $v['status_desc'] = '<span style="color:#ed5565">待支付</span> <a class="layui-btn layui-btn-normal layui-btn-xs"  onclick="operation(\'payment\',' . $id . ')">确认付款</a>';
                            break;
                        case  1:
                            $v['status_desc'] = '<span style="color:#23c6c8">待发货</span> <a class="layui-btn layui-btn-danger layui-btn-xs"  onclick="operation(\'deliver\',' . $id . ')">确认发货</a>';
                            break;
                        case  2:
                            $v['status_desc'] = '<span style="color:#f8ac59">待收货</span> <a class="layui-btn layui-btn-warm layui-btn-xs"  onclick="operation(\'received\',' . $id . ')">确认收货</a>';
                            break;
                        case  3:
                            $v['status_desc'] = '<span style="color:#1ab394">已完成</span>';
                            break;
                        case  4:
                            $v['status_desc'] = '<span style="color:#000">已取消</span>';
                            break;
                    }
                }
            }
            $count = Db::name('shop_order')
                ->alias('a')
                ->join('user u', 'a.uid=u.id', 'left')
                ->field('a.id,a.ordersn,u.nickname,a.total,a.price,u.nickname,a.fee,a.is_invite,a.status,a.createtime')
                ->where($where)
                ->where($date)
                ->count(1);
            return layui_json(0, '请求成功', $count, $sel);

        }

        return $this->fetch();
    }

    /**
     * 后台商城确认付款
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function payment()
    {
        if (request()->isAjax()) {
            $id = (int)input("id");
            $where = ['status' => 0, 'id' => $id];
            $find = Db::name('shop_order')->where($where)->find();
            if (!$find) {
                return diy_json('', -1, '未查询到该订单记录信息');
            }
            try {
                //开启事务
                Db::startTrans();
                //修改订单状态
                $order_map = [
                    'paytime' => time(),
                    'status' => 1,
                ];
                Db::name('shop_order')->where($where)->update($order_map);
                //todo 用户积分变动 增加积分记录表待做
                $l_insert = [
                    'uid' => $find['uid'],
                    'intergral' => $find['price'],
                    'createtime' => time(),
                ];
                Db::name('user_intergral_log')->insert($l_insert);
                $u_where = ['id' => $find['uid']];
                Db::name('user')->where($u_where)->setInc('credit', $find['price']);
                Db::commit();
                return diy_json('', 1, '操作成功');
            } catch (Exception $e) {
                Db::rollback();
                return diy_json('', -1, $e->getMessage());
            }
        }
    }

    /**
     * 后台商城确认发货
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function deliver()
    {
        $id = (int)input('id');
        if (request()->isAjax()) {
            $id = (int)input("id");
            $express_name = RemoveXSS(input('express_name'));//物流公司
            $express_no = RemoveXSS(input('express_no')); //物流单号
            $where = ['status' => 1, 'id' => $id];
            if (empty($express_name) || empty($express_no)) {
                return diy_json('', -1, '缺少必要参数');
            }
            $find = Db::name('shop_order')->where($where)->find();
            if (!$find) {
                return diy_json('', -1, '未查询到该订单记录信息');
            }
            try {
                //开启事务
                Db::startTrans();
                //修改订单状态
                $order_map = [
                    'deliver_time' => time(),
                    'status' => 2,
                    'express_no' => $express_no,
                    'express_name' => $express_name,
                ];
                Db::name('shop_order')->where($where)->update($order_map);
                //todo 写入消息记录
                $openid = Db::name('user')->where(['id'=>$find['uid']])->value('openid');
                write_msg($openid,'订单发货提醒','订单号为'.$find['ordersn'].'的商品已发出,快递单号:'.$express_no.'，请注意查收，谢谢');
                Db::commit();
                return diy_json('', 1, '操作成功');
            } catch (Exception $e) {
                Db::rollback();
                return diy_json('', -1, $e->getMessage());
            }
        }
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     * 后台商城确认收货
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function received()
    {
        if (request()->isAjax()) {
            $id = (int)input("id");
            $where = ['status' => 2, 'id' => $id];
            $find = Db::name('shop_order')->where($where)->find();
            if (!$find) {
                return diy_json('', -1, '未查询到该订单记录信息');
            }
            try {
                //开启事务
                Db::startTrans();
                //修改订单状态
                $order_map = [
                    'finish_time' => time(),
                    'status' => 3,
                ];
                Db::name('shop_order')->where($where)->update($order_map);
                Db::commit();
                return diy_json('', 1, '操作成功');
            } catch (Exception $e) {
                Db::rollback();
                return diy_json('', -1, $e->getMessage());
            }
        }
    }

    /**
     * 商城订单详情
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_detail()
    {
        $id = (int)input("id");
        $where = [['id', '=', $id], ['status', '<', 5]];
        $order = Db::name('shop_order')->where($where)->find();
        if (!$order) {
            $this->error('未查询到该订单记录信息');
        }
        $field = 'title,short_title,img,price,num';
        $where = ['oid' => $id];
        $order_goods = Db::name('shop_order_goods')->where($where)->field($field)->select();
        $u_where = ['id' => $order['uid']];
        $nickname = Db::name('user')->where($u_where)->value('nickname');
        $a_where = ['deleted' => 0, 'id' => $order['address_id']];
        $a_field = 'delivery_area,delivery_address,delivery_name,delivery_mobile';
        $address = Db::name('user_deliver')->where($a_where)->field($a_field)->find();
        $this->assign('order', $order);
        $this->assign('order_goods', $order_goods);
        $this->assign('nickname', $nickname);
        $this->assign('address', $address);
        return $this->fetch();
    }

    //添加课程订单(添加线下支付订单)
    public function good_order_add()
    {
        //获取目前有效的课程
        $where = ['status' => 1, 'deleted' => 0];
        $field = 'id,course_title';
        $course = Db::name('course')->where($where)->field($field)->select();
        $user_field = 'id,nickname,mobile';
        $user = Db::name('user')->field($user_field)->select();
        if($user){
            foreach ($user as &$item){
                $item['info'] = (empty($item['nickname'])?'匿名':$item['nickname']) . ' ' .(empty($item['mobile'])?'手机号未绑定':$item['mobile']);
                unset($item['nickname']);
                unset($item['mobile']);
            }
            unset($item);
        }
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['uid','course','class','time','package','price','class_address'];
            if(empty($data)){
                return diy_json('',-1,'缺少必要参数');
            }
            foreach ($map as $k){
                if(empty($data[$k])){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            $uid = $data['uid'];
            //判断用户是否存在
            $user_where = ['id'=>$uid];
            if(!($user = Db::name('user')->where($user_where)->find()) ){
                return diy_json('',-1,'用户不存在');
            }
            $course_id = $data['course'];
            $course_where = ['id'=>$course_id,'status'=>1,'deleted'=>0];
            if(Db::name('course')->where($course_where)->count() == 0 ){
                return diy_json('',-1,'课程信息不存在');
            }
            $class_id = $data['class'];
            $class_where = ['id'=>$class_id,'status'=>1,'deleted'=>0,'cid'=>$course_id];
            if(Db::name('course_class')->where($class_where)->count() == 0 ){
                return diy_json('',-1,'班级信息不存在');
            }
            $time_id = $data['time'];
            $time_where = ['id'=>$time_id,'status'=>1,'deleted'=>0,'class_id'=>$class_id];
            if(Db::name('course_class_time')->where($time_where)->count() == 0 ){
                return diy_json('',-1,'上课信息不存在');
            }
            $package_id = $data['package'];
            $package_where = ['id'=>$package_id,'status'=>1,'deleted'=>0,'class_id'=>$class_id];
            if(!($packages=Db::name('course_class_package')->where($package_where)->find())){
                return diy_json('',-1,'套餐信息不存在');
            }
            $price = (floatval($data['price'])) < 1 ? 1.00 : floatval($data['price']);
            $ordersn = $this->createOrdersn();
            $pay_type = 1;
            $pay_desc = '线下支付';
            $pay_time = time();
            $status = 1;
            $createtime = time();
            $package_time = $packages['package_time'];
            $package_month = $packages['package_month'];
            try{
                //开启事务
                Db::startTrans();
                //生成课程订单  并在用户课程信息增加课程记录 并更改用户身份为付费粉丝
                Db::name('course_order')->insert(compact('uid','course_id','class_id','time_id','package_id','price','ordersn','pay_type','pay_desc','pay_time','status','createtime','package_time','package_month'));
                //增加课程
                $user_course_map = [
                    'uid' => $uid,
                    'course_id' => $course_id,
                    'class_id' => $class_id,
                    'time_id' => $time_id,
                    'package_id' => $package_id,
                    'count' => $packages['package_time'],
                    'createtime' => time(),
                    'month' => $packages['package_month'],
                    'is_underline' => 1,
                    'class_address' => $data['class_address'],
                ];
                Db::name('user_course')->insert($user_course_map);
                if($user['identity'] == 0 ){
                    $user_update = ['identity'=>1];
                    Db::name('user')->where($user_where)->update($user_update); //更新用户身份为付费粉丝
                }
                Db::commit();
                return diy_json('',1,'操作成功');
            }catch (Exception $e){
                Db::rollback();
                return diy_json('',-1,$e->getMessage());
            }
        }
        $this->assign('course', $course);
        $this->assign('user', $user);
        return $this->fetch();
    }

    /**
     * 生成订单编号
     * @param int $length
     * @return string
     */
    final  function  createOrdersn($length=22){
        $time = date('YmdHi');
        $ordersn='112';
        $len = $length-strlen($time)-strlen($ordersn);
        for($i=0;$i<=$len;$i++){
            if($i==3){
                $ordersn .= $time;
            }else{
                $ordersn .= rand(0,9);
            }
        }
        $o_where = ['ordersn'=>$ordersn];
        $cnt = Db::name('course_order')->where($o_where)->count();
        if($cnt>0){
            $ordersn = $this->createOrdersn();
        }
        return $ordersn;
    }

    //课程订单确认付款
    public function course_payment(){
        if(request()->isPost()){
            $id = (int)input('id');
            if(empty($id)){
                return diy_json('',-1,'缺少必要参数');
            }
            $where = ['id'=>$id,'status'=>0];
            if(!($course_order = Db::name('course_order')->where($where)->find())){
                return diy_json('',-1,'订单信息不存在');
            }
            try{
                //开启事务
                Db::startTrans();
                //修改订单状态
                $order_map = [
                    'pay_time' =>time(),
                    'status' =>1,
                ];
                Db::name('course_order')->where($where)->update($order_map);
                //增加课程
                $user_course_map = [
                    'uid' => $course_order['uid'],
                    'course_id' => $course_order['course_id'],
                    'class_id' => $course_order['class_id'],
                    'time_id' => $course_order['time_id'],
                    'package_id' => $course_order['package_id'],
                    'count' => $course_order['package_time'],
                    'createtime' => time(),
                    'month' => $course_order['package_month'],
                ];
                Db::name('user_course')->insert($user_course_map);
                $user_where = ['id'=>$course_order['uid']];
                if($user = Db::name('user')->where($user_where)->find()){
                    if($user['identity'] == 0 ){
                        $user_update = ['identity'=>1];
                        Db::name('user')->where($user_where)->update($user_update); //更新用户身份为付费粉丝
                    }
                }
                Db::commit();
                return diy_json('',1,'操作成功');
            }catch (Exception $e){
                Db::rollback();
                return diy_json('',-1,$e->getMessage());
            }
        }
    }


    //根据课程筛选相关的班级 课程 套餐信息
    public function getInfoFromCourse()
    {
        if(request()->isAjax()){
            $id = (int)input('id');
            $type = (int)input('type');
            if(!in_array($type,[1,2])){
                return diy_json('', -1, '系统错误');
            }
            if($type == 1){
                //获取班级信息
                $class_where = ['status'=>1,'deleted'=>0,'cid'=>$id];
                $class_field = 'id,class_name';
                $class = Db::name('course_class')->where($class_where)->field($class_field)->select();
                $select_class = "<option value=''>请选择班级</option>";
                if($class){
                    foreach ($class  as $item){
                        $select_class .= "<option value='".$item['id']."'>".$item['class_name']."</option>";
                    }
                    unset($class);
                    unset($item);
                }
                $data = $select_class;
                return diy_json($data,1,'请求成功');
            }
            if($type == 2){
                $select_time = "<option value=''>请选择上课时间</option>";
                $select_package = "<option value=''>请选择套餐</option>";
                //获取上课信息
                $time_where = ['status'=>1,'deleted'=>0,'class_id'=>$id];
                $time_field = 'id,time';
                $time = Db::name('course_class_time')->where($time_where)->field($time_field)->select();
                if ($time) {
                    foreach ($time as &$v) {
                        /*if ($v['week_type'] == 1) {
                            $week_str = $this->week_convert($v['week_start']);
                            $time_str = $v['time_start_h'] . ':' . $v['time_start_m'] . '-' . $v['time_end_h'] . ':' . $v['time_end_m'];
                        } elseif ($v['week_type'] == 2) {
                            $week_str = $this->week_convert($v['week_start']) . '至' . $this->week_convert($v['week_end']);
                            $time_str = $v['time_start_h'] . ':' . $v['time_start_m'] . '-' . $v['time_end_h'] . ':' . $v['time_end_m'];
                        } else {
                            $week_str = '';
                            $time_str = '';
                        }*/
                        $select_time .= "<option value='".$v['id']."'>".$v['time']."</option>";
                    }
                    unset($time);
                    unset($v);
                }
                //获取套餐信息
                $package_where = ['status'=>1,'deleted'=>0,'class_id'=>$id];
                $package_field = 'id,package_name,package_time,package_month,package_price';
                $package = Db::name('course_class_package')->where($package_where)->field($package_field)->select();
                if ($package) {
                    foreach ($package as &$v) {
                        $select_package .= "<option value='".$v['id']."'>".$v['package_time'] . '次 ' . $v['package_time'] . '个月套餐 价格：'.$v['package_price']."</option>";
                    }
                    unset($v);
                    unset($package);
                }
                $data = [
                    'time' =>  $select_time,
                    'package' =>  $select_package,
                ];
                return diy_json($data,1,'请求成功');
            }

        }

    }

############################################################

    /**
     * 课程订单
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function good_order_ls()
    {

        if (request()->isAjax()) {

            $limit = input("limit");
            $page = input('page');
            $name = input('name'); //订单编号
            $start = input('start');
            $end = input('end');
            $status = input('status');
            $where = [];
            $date = [];
            if (!empty($name)) {
                $where[] = ['a.ordersn', 'like', '%' . $name . '%'];
            }
            if ($status == -1) {
                $where[] = ['a.status', '=', 0];
                // $where[] = ['status','<',5];
            } elseif (!empty($status)) {
                $where[] = ['a.status', '=', $status];
            } else {

            }
            $today = strtotime(date('Y-m-d')); //今天
            if (!empty($start) && !empty($end)) {
                if (strtotime($start) > strtotime($end) && strtotime($start) < strtotime($today)) {
                    $date[] = ['a.createtime', 'between', [strtotime($start), strtotime($today)]];
                } elseif (strtotime($start) > strtotime($end) && strtotime($start) > strtotime($today)) {
                    $date[] = ['a.createtime', 'between', [strtotime($today), strtotime($start)]];
                } elseif (strtotime($start) < strtotime($end)) {
                    $date[] = ['a.createtime', 'between', [strtotime($start), strtotime($end)]];
                }

            }
            $field = "a.id,a.ordersn,u.nickname,u.mobile,a.price,a.package_time,a.package_month,a.pay_type,a.status,a.createtime,b.course_title,c.class_name,e.time,u.id as uid";
            $order = 'a.id desc';
            $sel = Db::name('course_order')
                ->alias('a')
                ->join('user u', 'a.uid=u.id', 'left')
                ->join('course b', 'a.course_id=b.id')
                ->join('course_class c', 'a.class_id=c.id', 'left')
                ->join('course_class_time e', 'a.time_id=e.id', 'left')
                ->field($field)
                ->where($where)
                ->where($date)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->order($order)
                ->select();
            // print_r($sel);
            if ($sel) {
                foreach ($sel as &$v) {
                    $v['createtime'] = date('Y-m-d', $v['createtime']);
                    /*if ($v['week_type'] == 1) {
                        $week_str = $this->week_convert($v['week_start']);
                        $time_str = $v['time_start_h'] . ':' . $v['time_start_m'] . '-' . $v['time_end_h'] . ':' . $v['time_end_m'];
                    } elseif ($v['week_type'] == 2) {
                        $week_str = $this->week_convert($v['week_start']) . '至' . $this->week_convert($v['week_end']);
                        $time_str = $v['time_start_h'] . ':' . $v['time_start_m'] . '-' . $v['time_end_h'] . ':' . $v['time_end_m'];
                    } else {
                        $week_str = '';
                        $time_str = '';
                    }
                    $v['time'] = $week_str .' '. $time_str;*/
                    $v['package'] = $v['package_time'] . '次 ' . $v['package_time'] . '个月套餐';
                 /*   unset($v['week_type']);
                    unset($v['week_start']);
                    unset($v['week_end']);
                    unset($v['time_start_h']);
                    unset($v['time_start_m']);
                    unset($v['time_end_h']);
                    unset($v['time_end_m']);*/
                    unset($v['package_month']);
                    unset($v['package_time']);
                    switch ($v['status']) {
                        case  0:
                            /* $v['status_desc'] = '<span style="color:#ed5565">待支付</span> <a class="layui-btn layui-btn-normal layui-btn-xs"  onclick="operation(\'payment\','.$id.')">确认支付</a>';*/
                            $v['status_desc'] = '<span style="color:#ed5565">待支付</span> ';
                            break;
                        case  1:
                            $v['status_desc'] = '<span style="color:#23c6c8">已支付</span>';
                            break;
                    }
                    $v['pay_type'] == 0 && $v['pay_type'] = '<span style="color:#ed5565">线上支付</span>';
                    $v['pay_type'] == 1 && $v['pay_type'] = '<span style="color:#23c6c8">线下支付</span>';
                    $b_where= ['uid'=>$v['uid'],'deleted'=>0];
                    $v['baby_name'] = Db::name('user_baby')->where($b_where)->value('baby_name');
                }
                unset($v);
            }
            $count = Db::name('course_order')
                ->alias('a')
                ->join('user u', 'a.uid=u.id', 'left')
                ->join('course b', 'a.course_id=b.id')
                ->join('course_class c', 'a.class_id=c.id', 'left')
                ->join('course_class_time e', 'a.time_id=e.id', 'left')
                ->field($field)
                ->where($where)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }

        return $this->fetch();

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


    /**
     * 预览合同
     */
    public function edit_contract(){
        if(request()->isPost()){
            $id = input('id',0);
            if(empty($id)){
                return diy_json('',-1,'数据显示异常');
            }
            $where = ['id'=>$id,'status'=>1];
            $order = Db::name('course_order')->where($where)->find();
            if(empty($order)){
                return diy_json('',-1,'数据显示异常');
            }
            $father = input('father','');
            $mother = input('mother','');
            $full_name_father = input('full_name_father','');
            $full_name_mother = input('full_name_mother','');
            $mobile_father = input('mobile_father','');
            $mobile_mother = input('mobile_mother','');
            $child_name = input('child_name','');
            $school_name = input('school_name','');
            $gender = input('gender','');
            $year = input('year','');
            $month = input('month','');
            $day = input('day','');
            $address = input('address','');
            $home_phone = input('home_phone','');
            $contract_json = json_encode(compact('father','mother','full_name_father','full_name_mother','mobile_father','mobile_mother','child_name','school_name','gender','year','month','day','address','home_phone'),JSON_UNESCAPED_UNICODE);
            Db::name('course_order')->where($where)->update(compact('contract_json'));
             return diy_json('',1,'请求成功');
        }
        $id = input('id',0);
        if(empty($id)){
            $this->error('数据显示异常');
        }
        $where = ['id'=>$id,'status'=>1];
        $order = Db::name('course_order')->where($where)->find();
        if(empty($order)){
            $this->error('数据显示异常');
        }

        $json = json_decode($order['contract_json'],true);
        //合同需要显示的信息
        $show = [
            'father' => isset($json['father'])?$json['father']:'',
            'mother' => isset($json['mother'])?$json['mother']:'',
            'full_name_father' => isset($json['full_name_father'])?$json['full_name_father']:'',
            'full_name_mother' => isset($json['full_name_mother'])?$json['full_name_mother']:'',
            'mobile_father' => isset($json['mobile_father'])?$json['mobile_father']:'',
            'mobile_mother' => isset($json['mobile_mother'])?$json['mobile_mother']:'',
            'child_name' => isset($json['child_name'])?$json['child_name']:'',
            'school_name' => isset($json['school_name'])?$json['school_name']:'',
            'gender' => isset($json['gender'])?$json['gender']:'',
            'year' => isset($json['year'])?$json['year']:'',
            'month' => isset($json['month'])?$json['month']:'',
            'day' => isset($json['day'])?$json['day']:'',
            'address' => isset($json['address'])?$json['address']:'',
            'home_phone' => isset($json['home_phone'])?$json['home_phone']:'',
            'course_type' => Db::name('course')->where('id',$order['course_id'])->value('course_title'),
            'max_course' => Db::name('course_class_package')->where('id',$order['package_id'])->value('package_time'),
            'time' => Db::name('course_class_time')->where('id',$order['time_id'])->value('time'),
            'total_fee' => $order['price'],
            'fee' => "0.00",
        ];
        $this->assign('show',$show);
        $this->assign('id',$id);
        return $this->fetch();
    }



}









