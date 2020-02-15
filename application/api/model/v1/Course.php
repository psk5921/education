<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 16:42
 */

namespace app\api\model\v1;


use think\Model;
use think\Db;
use app\code\Api;
use app\api\model\v1\User;
class Course   extends  Model
{
   //获取指定分类下的课程信息
    public function getCourseByFilter($course_id,$page=1,$pagesize=10){

        if(empty($page)) $page=1;
        if(empty($pagesize)) $pagesize=10;
        $res =  Db::view('Course', 'id,course_title,course_short,course_img,evaluate_total,course_total')
            ->view('Teacher', 'teacher_name,teacher_img', 'Course.teacher_id=Teacher.id')
            ->where('Course.course_id', $course_id)
            ->where('Course.status', 1)
            ->where('Course.deleted', 0)
            ->order('Course.displayorder desc,Course.createtime desc')
            ->limit(ceil($page-1)*$pagesize,$pagesize)
            ->select();
        return $res;
    }

    //获取指定课程信息
    public function  getCourseById($course_id){
        if(empty($course_id)) return  false;
        $where = ['id'=>$course_id,'status'=>1,'deleted'=>0];
        $res =  $this->where($where)->field('course_title,course_short,course_thumb,course_description,course_class,course_total')->find();
        if($res){
            $c_where = ['cid'=>$course_id,'status'=>1,'deleted'=>0];
            $class = Db::name('course_class')->where($c_where)->field('id as class_id,class_name')->order('displayorder desc,createtime desc')->select();
            if($class){
                foreach ($class as $k=>$pack){
                    $p_where = ['class_id'=>$pack['class_id'],'status'=>1,'deleted'=>0];
                    $package = Db::name('course_class_package')->where($p_where)->field('id as package_id,package_name,package_time,package_month,package_price')->order('displayorder desc,createtime desc')->select();
                    $class[$k]['package'] = !empty($package) ? $package : null;
                }
                $res['other'] = $class;
            }else{
                $res['other'] = null;
            }
        }else{
            $res['other'] = null;
        }
        return $res;
    }

    //指定课程的弹出层内容
    public function  getCourseByIdToAlert($course_id){
        if(empty($course_id)) return  false;
        $c_where = ['cid'=>$course_id,'status'=>1,'deleted'=>0];
        $class = Db::name('course_class')->where($c_where)->field('id as class_id,class_name')->order('displayorder desc,createtime desc')->select();
        if($class){
            foreach ($class as $k=>$pack){
                $p_where = ['class_id'=>$pack['class_id'],'status'=>1,'deleted'=>0];
                $package = Db::name('course_class_package')->where($p_where)->field('id as package_id,package_name,package_time,package_month,package_price')->order('displayorder desc,createtime desc')->select();
                $t_where = ['class_id'=>$pack['class_id'],'status'=>1,'deleted'=>0];
               /* $time = Db::name('course_class_time')->where($t_where)->field('id as time_id,week_start,week_end,time_start_h,time_start_m,time_end_h,time_end_m,week_type')->order('displayorder desc,createtime desc')->select();*/
                $time = Db::name('course_class_time')->where($t_where)->field('id as time_id,time')->order('displayorder desc,createtime desc')->select();
                $times =  null;
                if($time){
                    foreach ($time  as &$t){
                        /*if($t['week_type'] == 1){
                            $week_str = $this->week_convert($t['week_start']);
                            $time_str = $t['time_start_h'].':'. $t['time_start_m'].'-'.$t['time_end_h'].':'. $t['time_end_m'];
                        }elseif($t['week_type'] == 2){
                            $week_str = $this->week_convert($t['week_start']).'至'.$this->week_convert($t['week_end']);
                            $time_str = $t['time_start_h'].':'. $t['time_start_m'].'-'.$t['time_end_h'].':'. $t['time_end_m'];
                        }else{
                            $week_str = '';
                            $time_str = '';
                        }*/
                        $t['desc'] = $t['time'];
                        $t['id'] = $t['time_id'];
                      /*  unset( $t['week_start']);
                        unset( $t['week_end']);
                        unset( $t['time_start_h']);
                        unset( $t['time_start_m']);
                        unset( $t['time_end_h']);
                        unset( $t['time_end_m']);
                        unset( $t['week_type']);*/
                        unset( $t['time_id']);
                    }
                    unset($t);

                }
                $class[$k]['package'] = !empty($package) ? $package : null;
                $class[$k]['time'] = !empty($time) ? $time : null;
            }
        }
        return $class;
    }


    //指定课程的指定信息
    public function  getCourseAssign($course_id,$class_id,$time_id){
        if(empty($course_id) || empty($class_id) || empty($time_id)) return  null;
        $c_where = ['id'=>$course_id,'status'=>1,'deleted'=>0];
        $course= $this->where($c_where)->field('course_title,course_short')->find();
        $data['course'] = $course;
        $c_where = ['id'=>$class_id];
        $class = Db::name('course_class')->where($c_where)->value('class_name');
        $data['class'] = $class;
        $t_where = ['id'=>$time_id];
       /* $time = Db::name('course_class_time')->where($t_where)->field('week_start,week_end,time_start_h,time_start_m,time_end_h,time_end_m,week_type')->find();*/
        $time = Db::name('course_class_time')->where($t_where)->field('time')->find();
        /*if($time['week_type'] == 1){
            $week_str = $this->week_convert($time['week_start']);
            $time_str = $time['time_start_h'].':'. $time['time_start_m'].'-'.$time['time_end_h'].':'. $time['time_end_m'];
        }elseif($time['week_type'] == 2){
            $week_str = $this->week_convert($time['week_start']).'至'.$this->week_convert($time['week_end']);
            $time_str = $time['time_start_h'].':'. $time['time_start_m'].'-'.$time['time_end_h'].':'. $time['time_end_m'];
        }else{
            $week_str = '';
            $time_str = '';
        }*/
        $data['time'] = $time['time'];
        return $data;
    }

    /**
     * 线上课程预下单验证
     * @param $openid
     * @param $course_id
     * @param $class_id
     * @param $time_id
     * @param $package_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function validateCourseOrder($openid,$course_id,$class_id,$time_id,$package_id){
        if(empty($openid) || empty($course_id) || empty($class_id) || empty($time_id) || empty($package_id) ){
            return false;
        }
        $u_where = ['openid'=>$openid];
        /*$is_login = Db::name('user')->where($u_where)->value('mobile');
        if(!$is_login){
            return false;
        }*/
        $cc_where = ['id'=>$course_id,'status'=>1,'deleted'=>0];
        $res =  $this->where($cc_where)->count();
        if(!$res){
            return false;
        }
        $c_where = ['cid'=>$course_id,'status'=>1,'deleted'=>0];
        $class = Db::name('course_class')->where($c_where)->field('id')->select();
        if(!$class || !in_array($class_id,array_column($class,'id'))){
            return false;
        }
        $t_where = ['status'=>1,'deleted'=>0];
        $time = Db::name('course_class_time')->whereIn('class_id',implode(',',array_column($class,'id')))->where($t_where)->field('id')->select();
        if(!$time || !in_array($time_id,array_column($time,'id'))){
            return false;
        }
        $p_where = ['status'=>1,'deleted'=>0];
        $package = Db::name('course_class_package')->whereIn('class_id',implode(',',array_column($class,'id')))->where($p_where)->field('id')->select();
        if(!$package || !in_array($package_id,array_column($package,'id'))){
            return false;
        }
        return true;
    }

    public function  createCourseOrder($openid,$course_id,$class_id,$time_id,$package_id){
        if(!$this->validateCourseOrder($openid,$course_id,$class_id,$time_id,$package_id)){
            return api_json(Api::PARAM_ERROR[0],'创建订单失败,系统错误');
        }
        $User = new User;
        if(!($package=$this->getPackage($package_id))){
            return api_json(Api::PARAM_ERROR[0],'参数请求有误');
        }
        $ordersn =  $this->createOrdersn();
        $order_map = [
            'uid'  => $User->getIdByOpenid($openid),
            'ordersn'  => $ordersn,
            'course_id'  => $course_id,
            'class_id'  => $class_id,
            'time_id'  => $time_id,
            'package_id'  => $package_id,
            'package_time'  => $package['package_time'],
            'package_month'  => $package['package_month'],
            'price'  => $package['package_price'],
            'pay_desc'  => '线上微信支付',
            'createtime'  => time(),
        ];
        $res = Db::name('course_order')->insert($order_map);
        if($res){
            $response_map = [
              'ordersn' => $ordersn,
              'course' => $this->getCourse($course_id),
              'class' => $this->getClass($class_id),
              'time' => $this->getTime($time_id),
              'package' =>$package,
            ];
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$response_map);
        }else{
            return api_json(Api::SYSTEM_ERROR[0],Api::SYSTEM_ERROR[1]);
        }
    }

    /**
     * 获取套餐参数
     * @param $packageid
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final function getPackage($packageid){
        if(empty($packageid)){
            return false;
        }
        $p_where = ['id'=>$packageid,'status'=>1,'deleted'=>0];
        $price = Db::name('course_class_package')->where($p_where)->field('package_price,package_time,package_month,package_name')->find();
        if(empty($price) || !$price){
            return false;
        }
        return $price;
    }


    /**
     * 获取课程参数
     * @param $course_id
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final function getCourse($course_id){
        if(empty($course_id)){
            return false;
        }
        $p_where = ['id'=>$course_id,'status'=>1,'deleted'=>0];
        $course = Db::name('course')->where($p_where)->field('course_title,course_short,course_img,agreement')->find();
        if(empty($course) || !$course){
            return false;
        }
        return $course;
    }

    /**
     * 获取班级参数
     * @param $class_id
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final function getClass($class_id){
        if(empty($class_id)){
            return false;
        }
        $p_where = ['id'=>$class_id,'status'=>1,'deleted'=>0];
        $course_class = Db::name('course_class')->where($p_where)->field('class_name')->find();
        if(empty($course_class) || !$course_class){
            return false;
        }
        return $course_class;
    }

    /**
     * 获取时间参数
     * @param $time_id
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final function getTime($time_id){
        if(empty($time_id)){
            return false;
        }
        $p_where = ['id'=>$time_id,'status'=>1,'deleted'=>0];
        /*$time = Db::name('course_class_time')->where($p_where)->field('week_start,week_end,time_start_h,time_start_m,time_end_h,time_end_m,week_type')->find();*/
        $time = Db::name('course_class_time')->where($p_where)->field('time')->find();
        if(empty($time) || !$time){
            return false;
        }
        /*if($time['week_type'] == 1){
            $week_str = $this->week_convert($time['week_start']);
            $time_str = $time['time_start_h'].':'. $time['time_start_m'].'-'.$time['time_end_h'].':'. $time['time_end_m'];
        }elseif($time['week_type'] == 2){
            $week_str = $this->week_convert($time['week_start']).'至'.$this->week_convert($time['week_end']);
            $time_str = $time['time_start_h'].':'. $time['time_start_m'].'-'.$time['time_end_h'].':'. $time['time_end_m'];
        }else{
            $week_str = '';
            $time_str = '';
        }
        $desc = $week_str.$time_str;*/
        $desc = $time['time'];
        return $desc;
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

    /**
     * 星期转换
     * @param $num
     * @return bool|string
     */
    final function week_convert($num){
        if(!$num) return false;
        switch ($num){
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