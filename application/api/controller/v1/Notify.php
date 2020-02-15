<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 20:30
 */

namespace app\api\controller\v1;


use think\Db;

class Notify
{
    const  DEBUG = true;
    //支付成功异步回调的结果处理 商城订单
    public function notifyOrderCallback(){
        //todo 等待微信回调结果的验证
        $result=json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"),'SimpleXMLElement', LIBXML_NOCDATA)),true);
        if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
            $ordersn = $result['out_trade_no']; //订单编号
            $transaction_id = $result['transaction_id'];
            $total = $result['total_fee'];
            $o_where = ['ordersn'=>$ordersn];
            $order = Db::name('shop_order')->where($o_where)->find();
            if($order['status'] == 1){
                exit($this->returnWx('SUCCESS'));
            }
            if(self::DEBUG){
                $order['price'] = 1 ;
            }
            if( $total != $order['price'] ){
                exit($this->returnWx('FAIL'));
            }
            try{
                //开启事务
                Db::startTrans();
                //修改订单状态
                $order_map = [
                    'paytime' =>time(),
                    'status' =>1,
                    'transaction_id' =>$transaction_id,
                    'back' =>serialize($result),
                ];
                Db::name('shop_order')->where($o_where)->update($order_map);
                //todo 用户积分变动 增加积分记录表待做
                $l_insert = [
                    'uid' => $order['uid'],
                    'intergral' => $order['price'],
                    'createtime' => time(),
                ];
                Db::name('user_intergral_log')->insert($l_insert);
                $u_where = ['id'=>$order['uid']];
                if($user = Db::name('user')->where($u_where)->find()){
                    if($user['identity'] == 0 ){
                        $user_update = ['identity'=>1];
                        Db::name('user')->where($u_where)->update($user_update); //更新用户身份为付费粉丝
                    }
                }
                Db::name('user')->where($u_where)->setInc('credit',$order['price']);
                Db::commit();
                exit($this->returnWx('SUCCESS'));
            }catch (Exception $e){
                Db::rollback();
                $str = "########".date('Y-m-d H:i:s')." ,商城购买回调发生错误,订单编号：{$ordersn}，需手动触发逻辑修改 ########";
                file_put_contents('error_shop.log',$str.PHP_EOL,FILE_APPEND);
                exit($this->returnWx('FAIL'));
            }
        }else{
            exit($this->returnWx('FAIL'));
        }
    }


    //支付成功异步回调的结果处理 课程
    public function notifyCourseCallback(){
        //todo 等待微信回调结果的验证
        $result=json_decode(json_encode(simplexml_load_string(file_get_contents("php://input"),'SimpleXMLElement', LIBXML_NOCDATA)),true);
        if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
            $ordersn = $result['out_trade_no']; //订单编号
            $transaction_id = $result['transaction_id'];
            $total = $result['total_fee'];
            $o_where = ['ordersn'=>$ordersn];
            $order = Db::name('course_order')->where($o_where)->find();
            if($order['status'] == 1){
                exit($this->returnWx('SUCCESS'));
            }
            if(self::DEBUG){
                $order['price'] = 1 ;
            }
            if( $total != $order['price'] ){
                exit($this->returnWx('FAIL'));
            }
            try{
                //开启事务
                Db::startTrans();
                //修改订单状态
                $order_map = [
                    'pay_time' =>time(),
                    'status' =>1,
                    'transaction_id' =>$transaction_id,
                    'back' =>serialize($result),
                ];
                Db::name('course_order')->where($o_where)->update($order_map);
                //增加课程
                $user_course_map = [
                    'uid' => $order['uid'],
                    'course_id' => $order['course_id'],
                    'class_id' => $order['class_id'],
                    'time_id' => $order['time_id'],
                    'package_id' => $order['package_id'],
                    'count' => $order['package_time'],
                    'createtime' => time(),
                    'month' => $order['package_month'],
                ];
                Db::name('user_course')->insert($user_course_map);
                $user_where = ['id'=>$order['uid']];
                if($user = Db::name('user')->where($user_where)->find()){
                    if($user['identity'] == 0 ){
                        $user_update = ['identity'=>1];
                        Db::name('user')->where($user_where)->update($user_update); //更新用户身份为付费粉丝
                    }
                }
                Db::commit();
                exit($this->returnWx('SUCCESS'));
            }catch (Exception $e){
                Db::rollback();
                $str = "########".date('Y-m-d H:i:s')." ,课程购买回调发生错误,订单编号：{$ordersn}，需手动触发逻辑修改 ########";
                file_put_contents('error_course.log',$str.PHP_EOL,FILE_APPEND);
                exit($this->returnWx('FAIL'));
            }
        }else{
            exit($this->returnWx('FAIL'));
        }
    }


    function returnWx($str){
        if($str == 'SUCCESS'){
           return  '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        }
        if($str == 'FAIL'){
            return '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        }
    }




    /**
     * 处理发货超时订单 自动完成
     */
    public function dealOrder(){
        $where = [['status','=','2'],['deliver_time','<',time()-7*3600*24]];
        $order = Db::name('shop_order')->where($where)->field('id')->select();
        if($order){
            foreach ($order as $o){
                $where = ['id'=>$o['id']];
                $update = ['status'=>3,'finish_time'=>time()];
                Db::name('shop_order')->where($where)->update($update);
            }
        }
    }
}