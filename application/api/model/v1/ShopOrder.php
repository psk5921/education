<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 19:44
 */

namespace app\api\model\v1;


use think\Model;
use think\Db;
use app\code\Api;
class ShopOrder extends Model
{
    /**
     * 增加一条订单记录
     * @param $data
     * @return string
     */
    public function _insert($data, $ordersn)
    {
        try {
            $res = $this->allowField(true)->save($data);//新增
            if ($res) {
                return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], $ordersn);
            } else {
                return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
            }
        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }

    /**
     * 创建订单号
     * @param int $length
     * @return string
     */
    public function createOrdersn($length = 22)
    {
        $time = date('YmdHi');
        $ordersn = '211';
        $len = $length - strlen($time) - strlen($ordersn);
        for ($i = 0; $i <= $len; $i++) {
            if ($i == 3) {
                $ordersn .= $time;
            } else {
                $ordersn .= rand(0, 9);
            }
        }
        $o_where = ['ordersn' => $ordersn];
        $cnt = Db::name('shop_order')->where($o_where)->count();
        if ($cnt > 0) {
            $ordersn = $this->createOrdersn();
        }
        return $ordersn;
    }


    /**
     * 通过订单状态筛选过滤我的订单
     * @param $uid
     * @param $status
     * @param int $page
     * @param int $pagesize
     * @return array|null|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderListByStatus($uid, $status, $page = 1, $pagesize = 10)
    {
        if (empty($uid) || !in_array($status, [-1, 0, 1, 2, 3])) {
            return null;
        }
        if ($status == -1) {
            $where = [['status', '<', 4], ['uid', '=', $uid]];
            $order = 'createtime desc';
        } else {
            $where = [['status', '=', $status], ['uid', '=', $uid]];
            if ($status == 0) {
                $order = 'createtime desc';
            } elseif ($status == 1) {
                $order = 'paytime desc';
            } elseif ($status == 2) {
                $order = 'deliver_time desc';
            } elseif ($status == 3) {
                $order = 'finish_time desc';
            } else {
                $order = 'cancel_time desc';
            }
        }
        $limit = ceil($page - 1) * $pagesize . ',' . $pagesize;
        $field = "id,ordersn,total,price,status";
        $order = $this->where($where)->field($field)->order($order)->limit($limit)->select();
        if($order){
            foreach ($order as &$item){
                $o_where = ['oid'=>$item['id']];
                $o_field = 'gid,title,short_title,img,price,num';
                $ordergoods = Db::name('shop_order_goods')->where($o_where)->field($o_field)->select();
                $item['status_desc'] = $this->statusToText($item['status']);
                $item['goods'] = $ordergoods;
                $item['operation'] = $this->optionForStatus($item['status']);
            }
            unset($item);
        }else{
            $order = null;
        }
        return $order;
    }

    /**
     * 订单详情
     * @param $uid
     * @param $orderid
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function orderDetail($uid,$orderid){
        if (empty($uid) || empty($orderid)) {
            return  api_json(Api::SYSTEM_ERROR[0],'缺少必要参数');
        }
        $field = "id,ordersn,total,price,status,address_id,express_no,express_name,is_invite,paytime,createtime,deliver_time,finish_time";
        $order = $this->getOrderOne($uid,$orderid,$field);
        if(empty($order)){
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
       /*
        $where = ['id'=>$orderid];
        $order = $this->where($where)->field($field)->find();*/
        if($order){
            $o_where = ['oid'=>$order['id']];
            $o_field = 'gid,title,short_title,img,price,num';
            $ordergoods = Db::name('shop_order_goods')->where($o_where)->field($o_field)->select();
            $order['status_desc'] = $this->statusToText($order['status']);
            $order['goods'] = $ordergoods;
            $a_where = ['id'=>$order['address_id']];
            $a_field = 'delivery_name,delivery_mobile,delivery_area,delivery_address';
            $order['address'] = Db::name('user_deliver')->where($a_where)->field($a_field)->find();
            if($order['status']<2){
                unset($order['express_no']);
                unset($order['express_name']);
            }
            if($order['status'] == 0 ){
                unset($order['paytime']);
                unset($order['deliver_time']);
                unset($order['finish_time']);
            }elseif($order['status'] == 1){
                unset($order['deliver_time']);
                unset($order['finish_time']);
            }elseif($order['status'] == 2){
                unset($order['deliver_time']);
            }elseif($order['status'] == 3){

            }else{
                unset($order['paytime']);
                unset($order['deliver_time']);
                unset($order['finish_time']);
                unset($order['createtime']);
            }
            $order['operation'] = $this->optionForStatus($order['status']);
        }else{
            $order = null;
        }
        return  api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$order);
    }


    /**
     * 订单支付成功
     * @param $uid
     * @param $ordersn
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function orderPaySuccess($uid,$ordersn){
        if (empty($uid) || empty($ordersn)) {
            return  api_json(Api::SYSTEM_ERROR[0],'缺少必要参数');
        }
        $field = "total,price,address_id,fee,is_invite";
        $order = $this->getOrdersnOne($uid,$ordersn,$field);
        if(empty($order)){
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
        if($order){
           /* $o_where = ['oid'=>$order['id']];
            $o_field = 'gid,title,short_title,img,price,num';
            $ordergoods = Db::name('shop_order_goods')->where($o_where)->field($o_field)->select();
            $order['status_desc'] = $this->statusToText($order['status']);
            $order['goods'] = $ordergoods;*/
            $a_where = ['id'=>$order['address_id']];
            $a_field = 'delivery_name,delivery_mobile,delivery_area,delivery_address';
            $order['address'] = Db::name('user_deliver')->where($a_where)->field($a_field)->find();
            unset($order['address_id']);
            $order['str'] = "订单支付成功";
        }else{
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
        return  api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$order);
    }


    /**
     * 功能显示
     * @param $status
     * @return array
     */
    final function optionForStatus($status){
        $map = [
          'cancel_order'  => false,
          'pay_order'  => false,
          'cuidan'  => false,
          'confirm_order'  => false,
          'delete_order'  => false,
        ];
        switch ($status){
            case 0:
                $map['cancel_order'] = true;
                $map['pay_order'] = true;
                break;
            case 1:
                $map['cuidan'] = true;
                break;
            case 2:
                $map['confirm_order'] = true;
                break;
            case 3:
                $map['delete_order'] = true;
                break;
        }
        return $map;
    }


    /**
     * 订单状态文字描述
     * @param $status
     * @return string
     */
    final function statusToText($status){
        $str = '';
        switch ($status){
            case  0:
                $str = "待支付";
                break;
            case  1:
                $str = "已支付等待发货";
                break;
            case  2:
                $str = "待收货";
                break;
            case  3:
                $str = "已完成";
                break;
            case  4:
                $str = "已取消";
                break;
        }
        return $str;
    }

    /**
     * 获取单个订单信息
     * @param $uid
     * @param $orderid
     * @param string $field
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderOne($uid,$orderid,$field='*'){
        $where = [['uid','=',$uid],['id','=',$orderid],['status','<',4]];
        return  $this->where($where)->field($field)->find();
    }

    /**
     * 获取单个订单信息
     * @param $uid
     * @param $orderid
     * @param string $field
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrdersnOne($uid,$ordersn,$field='*'){
        $where = [['uid','=',$uid],['ordersn','=',$ordersn],['status','=',1]];
        return  $this->where($where)->field($field)->find();
    }

    /**
     * 取消订单
     * @param $uid
     * @param $orderid
     * @return false|null|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cancelOrder($uid,$orderid){
        if (empty($uid) || empty($orderid)) {
            return  api_json(Api::SYSTEM_ERROR[0],'缺少必要参数');
        }
        $order = $this->getOrderOne($uid,$orderid);
        if(empty($order)){
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
        if($order['status'] != 0 ){
            return  api_json(Api::SYSTEM_ERROR[0],'该订单状态无法取消');
        }
        $data = ['status'=>4,'cancel_time'=>time()];
        $where = [['uid','=',$uid],['id','=',$orderid],['status','<',4]];
        $res = $this->isUpdate(true)->save($data,$where);
        if($res){
            return  api_json(Api::REQUEST_SUCCESS[0],'取消订单成功');
        }else{
            return  api_json(Api::SYSTEM_ERROR[0],'取消失败请重新尝试');
        }

    }


    /**
     * 催单
     * @param $uid
     * @param $orderid
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cuidan($uid,$orderid){
        if (empty($uid) || empty($orderid)) {
            return  api_json(Api::SYSTEM_ERROR[0],'缺少必要参数');
        }
        $order = $this->getOrderOne($uid,$orderid);
        if(empty($order)){
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
        if($order['status'] != 1 ){
            return  api_json(Api::SYSTEM_ERROR[0],'该订单状态无法催单');
        }
        if($order['is_reminder'] == 1 ){
            return  api_json(Api::SYSTEM_ERROR[0],'该订单状态已催单');
        }
        $data = ['is_reminder'=>1];
        $where = [['uid','=',$uid],['id','=',$orderid],['status','<',4]];
        $res = $this->isUpdate(true)->save($data,$where);
        if($res){
            return  api_json(Api::REQUEST_SUCCESS[0],'催单成功');
        }else{
            return  api_json(Api::SYSTEM_ERROR[0],'催单失败请重新尝试');
        }
    }


    /**
     * 确认收货
     * @param $uid
     * @param $orderid
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function confirmOrder($uid,$orderid){
        if (empty($uid) || empty($orderid)) {
            return  api_json(Api::SYSTEM_ERROR[0],'缺少必要参数');
        }
        $order = $this->getOrderOne($uid,$orderid);
        if(empty($order)){
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
        if($order['status'] != 2 ){
            return  api_json(Api::SYSTEM_ERROR[0],'该订单状态无法确认收货');
        }

        $data = ['finish_time'=>time(),'status'=>3];
        $where = [['uid','=',$uid],['id','=',$orderid],['status','<',4]];
        $res = $this->isUpdate(true)->save($data,$where);
        if($res){
            return  api_json(Api::REQUEST_SUCCESS[0],'确认收货成功');
        }else{
            return  api_json(Api::SYSTEM_ERROR[0],'确认收货失败请重新尝试');
        }
    }


    /**
     * 删除订单
     * @param $uid
     * @param $orderid
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delOrder($uid,$orderid){
        if (empty($uid) || empty($orderid)) {
            return  api_json(Api::SYSTEM_ERROR[0],'缺少必要参数');
        }
        $order = $this->getOrderOne($uid,$orderid);
        if(empty($order)){
            return  api_json(Api::SYSTEM_ERROR[0],'未查询到相关订单信息');
        }
        if($order['status'] != 3 ){
            return  api_json(Api::SYSTEM_ERROR[0],'该订单状态无法删除订单');
        }

        $data = ['deltime'=>time(),'status'=>5];
        $where = [['uid','=',$uid],['id','=',$orderid],['status','<',4]];
        $res = $this->isUpdate(true)->save($data,$where);
        if($res){
            return  api_json(Api::REQUEST_SUCCESS[0],'删除订单成功');
        }else{
            return  api_json(Api::SYSTEM_ERROR[0],'删除订单失败请重新尝试');
        }
    }
}