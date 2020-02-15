<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 10:39
 */

namespace app\api\controller\v1;

use app\api\model\v1\ShopCategory;
use app\api\model\v1\ShopGoods;
use app\code\Api;
use app\api\model\v1\User as m_User;
use app\api\model\v1\UserDeliver;
use app\api\model\v1\ShopCart;
use app\api\model\v1\ShopOrder;
use think\Db;
use think\Exception;
use app\api\controller\v1\Weixin;

class Shops  extends  Base
{
    const DEBUG = true;
    /**
     * 商城首页
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shopIndex(){

        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $cate_id = isset($this->_input['cate_id']) ?(int) $this->_input['cate_id'] : 0;
        $ShopCategory = new ShopCategory;
        $list = $ShopCategory->getShopCategory($cate_id,$page,$pagesize);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],empty($list)?null:$list);
    }


    /**
     * 商品详情
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shopDetail(){
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : '';
        if(empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $ShopGoods = new ShopGoods;
        if(!$ShopGoods->goodsIsUseful($id)){
            return api_json(Api::SYSTEM_ERROR[0],'商品信息有误');
        }
        $field = 'thumb,price,title,short_title,content';
        $res = $ShopGoods->getFieldById($id,$field);
        if(!empty($res['thumb'])){
            $res['thumb'] = unserialize(mb_unserialize($res['thumb']));
        }
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],empty($res)?null:$res);
    }


    //todo  创建订单 订单列表切换 订单详情 取消订单 确认收货 催单 删除

    /**
     * 商品详情点击购买
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCreatePreOrderForOnce(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : null;
        $ShopGoods = new ShopGoods();
        if(!$ShopGoods->goodsIsUseful($id)){
            return api_json(Api::SYSTEM_ERROR[0],'商品信息有误');
        }
        $UserDeliver = new UserDeliver;
        $address = $UserDeliver->getDefaultAddress($uid);
        $goods = $ShopGoods->getOrderTotalPrice([$id=>1]);
        $data= $goods;
        $data['address'] = $address;
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$data);
    }

    /**
     * 商品详情确认购买
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function postCreatePreOrderForOnce(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : null;
        $ShopGoods = new ShopGoods();
        $UserDeliver = new UserDeliver();
        if(!$ShopGoods->goodsIsUseful($id)){
            return api_json(Api::SYSTEM_ERROR[0],'商品信息有误');
        }
        $is_invite = isset($this->_input['is_invite']) ? $this->_input['is_invite'] : 0;
        $address_id = isset($this->_input['address_id']) ? $this->_input['address_id'] : 0;
        if(!$UserDeliver->isDefault($uid,$address_id)){
            return api_json(Api::SYSTEM_ERROR[0],'地址信息有误');
        }
        $ShopOrder = new ShopOrder;
        $ordersn = $ShopOrder->createOrdersn();
        $goods = $ShopGoods->getOrderTotalPrice([$id=>1]);
        if(empty($goods['goods'])){
            return api_json(Api::SYSTEM_ERROR[0],'商品信息有误');
        }
        //todo 生成一条预支付订单记录 返回订单号 暂时未接入微信支付 后期要弹框支付 生成订单记录产品数据
        try{
            Db::startTrans();
            $order = [
                'ordersn' => $ordersn,
                'uid' => $uid,
                'total' => $goods['gtotal'],
                'price' => $goods['total'],
                'address_id' => $address_id,
                'fee' => 0.00,
                'is_invite' => $is_invite,
                'createtime' => time(),
            ];
            $orderid = Db::name('shop_order')->insertGetId($order);

            foreach ($goods['goods'] as $v){
                $ordergoods[] = [
                    'oid' =>$orderid,
                    'gid' =>$v['id'],
                    'title' =>$v['title'],
                    'short_title' =>$v['short_title'],
                    'img' =>$v['img'],
                    'price' =>$v['price'],
                    'num' =>$v['num'],
                    'createtime' =>time(),
                ];
            }
            Db::name('shop_order_goods')->insertAll($ordergoods);
            Db::commit();
            /*//TODO 微信预下单 返回小程序唤醒微信支付的相关数据
            $Weixin   = new Weixin;
            $body = '商品订单';
            if(self::DEBUG){
                $total_fee = 0.01;
            }else{
                $total_fee = $goods['total'];
            }
            $notify_url = "https://education.kedaweilai.com/api/notify/notifyOrderCallback";
            $preorder = $Weixin->getAppletPayParams($ordersn,$body,$total_fee,$notify_url,$openid);*/
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$ordersn);
        }catch (Exception $e){
            Db::rollback();
            return api_json(Api::SYSTEM_ERROR[0],$e->getMessage());
        }
    }

    /**
     * 购物车点击购买
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCreatePreOrderForCart(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        //todo 获取当前购物车选中的商品数据
        $ShopCart = new ShopCart;
        $cart = $ShopCart->getCartFromUid($openid);
        if(empty($cart)){
            return api_json(Api::SYSTEM_ERROR[0],'请现在购物车选中你想购买的商品');
        }
        $ShopGoods = new ShopGoods();
        $UserDeliver = new UserDeliver;
        $address = $UserDeliver->getDefaultAddress($uid);
        $goods = $ShopGoods->getOrderTotalPrice($cart);
        $data= $goods;
        $data['address'] = $address;
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$data);
    }


    /**
     * 通过订单号获取微信支付参数
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function payShopOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $ordersn= isset($this->_input['ordersn']) ? $this->_input['ordersn'] : null;
        if(empty($ordersn)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $o_where = ['ordersn'=>$ordersn,'status'=>0];
        if(!($order = Db::name('shop_order')->where($o_where)->find())){
            return api_json(Api::SYSTEM_ERROR[0],'订单信息查询有误');
        }
        //TODO 微信预下单 返回小程序唤醒微信支付的相关数据
        $Weixin   = new Weixin;
        $body = '商品订单';
        if(self::DEBUG){
            $total_fee = 0.01;
        }else{
            $total_fee = $order['price'];
        }
        $notify_url = "https://education.kedaweilai.com/api/notify/notifyOrderCallback";
        $preorder = $Weixin->getAppletPayParams($ordersn,$body,$total_fee,$notify_url,$openid);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$preorder);
    }

    /**
     *  购物车点击购买进来以后确认购买
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function postCreatePreOrderForCart(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        //todo 获取当前购物车选中的商品数据
        $ShopCart = new ShopCart;
        $cart = $ShopCart->getCartFromUid($openid);
        if(empty($cart)){
            return api_json(Api::SYSTEM_ERROR[0],'请现在购物车选中你想购买的商品');
        }
        $ShopGoods = new ShopGoods();
        $UserDeliver = new UserDeliver;
        $is_invite = isset($this->_input['is_invite']) ? $this->_input['is_invite'] : 0;
        $address_id = isset($this->_input['address_id']) ? $this->_input['address_id'] : 0;
        if(!$UserDeliver->isDefault($uid,$address_id)){
            return api_json(Api::SYSTEM_ERROR[0],'地址信息有误');
        }
        $goods = $ShopGoods->getOrderTotalPrice($cart);
        if(empty($goods['goods'])){
            return api_json(Api::SYSTEM_ERROR[0],'商品信息有误');
        }
        $ShopOrder = new ShopOrder;
        $ordersn = $ShopOrder->createOrdersn();
        //todo 生成一条预支付订单记录 返回订单号 暂时未接入微信支付 后期要弹框支付 生成订单记录产品数据
        try{
            Db::startTrans();
            $order = [
                'ordersn' => $ordersn,
                'uid' => $uid,
                'total' => $goods['gtotal'],
                'price' => $goods['total'],
                'address_id' => $address_id,
                'fee' => 0.00,
                'is_invite' => $is_invite,
                'createtime' => time(),
            ];
            $orderid = Db::name('shop_order')->insertGetId($order);

            foreach ($goods['goods'] as $v){
                $ordergoods[] = [
                    'oid' =>$orderid,
                    'gid' =>$v['id'],
                    'title' =>$v['title'],
                    'short_title' =>$v['short_title'],
                    'img' =>$v['img'],
                    'price' =>$v['price'],
                    'num' =>$v['num'],
                    'createtime' =>time(),
                ];
            }
            Db::name('shop_order_goods')->insertAll($ordergoods);
            //清除购物车数据
            $where = ['uid'=>$uid,'deleted' => 0,'selected'=>1];
            $update = ['deleted' => 1];
            Db::name('shop_cart')->where($where)->update($update);//修改状态为删除
            Db::commit();
            /*//TODO 微信预下单 返回小程序唤醒微信支付的相关数据
            $Weixin   = new Weixin;
            $body = '商品订单';
            if(self::DEBUG){
                $total_fee = 0.01;
            }else{
                $total_fee = $goods['total'];
            }
            $notify_url = "https://education.kedaweilai.com/api/notify/notifyOrderCallback";
            $preorder = $Weixin->getAppletPayParams($ordersn,$body,$total_fee,$notify_url,$openid);
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$preorder);*/
           return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$ordersn);
        }catch (Exception $e){
            Db::rollback();
            return api_json(Api::SYSTEM_ERROR[0],$e->getMessage());
        }
    }


}