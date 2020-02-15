<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 20:31
 */

namespace app\api\controller\v1;

use app\api\model\v1\ShopGoods;
use app\api\model\v1\ShopOrder;
use think\Db;
use think\Exception;
use app\code\Api;
use app\api\model\v1\User as m_User;
class Orders      extends   Base
{
    /**
     * 通过订单状态筛选过滤我的订单
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public  function myOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        //根据不同订单状态显示数据 -1代表全部的意思
        $status = isset($this->_input['status']) ? $this->_input['status'] : -1;
        $page = isset($this->_input['page']) ? $this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? $this->_input['pagesize'] :10;
        $ShopOrder = new ShopOrder;
        $list = $ShopOrder->getOrderListByStatus($uid,$status,$page,$pagesize);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$list);
    }

    /**
     * 取消订单
     * @return false|null|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cancelOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : '';
        $ShopOrder = new ShopOrder;
        $res = $ShopOrder->cancelOrder($uid,$id);
        return $res;
    }

    /**
     * 催单
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cuidan(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : '';
        $ShopOrder = new ShopOrder;
        $res = $ShopOrder->cuidan($uid,$id);
        return $res;
    }

    /**
     * 确认收货订单
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function confirmOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : '';
        $ShopOrder = new ShopOrder;
        $res = $ShopOrder->confirmOrder($uid,$id);
        return $res;
    }

    /**
     * 删除订单
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : '';
        $ShopOrder = new ShopOrder;
        $res = $ShopOrder->delOrder($uid,$id);
        return $res;
    }


    /**
     * 订单详情
     * @return string
     */
    public function orderDetail(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $id = isset($this->_input['id']) ? $this->_input['id'] : '';
        $ShopOrder = new ShopOrder;
        $res = $ShopOrder->orderDetail($uid,$id);
        return $res;
    }


    /**
     * 订单支付成功
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function orderPaySuccessed(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $ordersn = isset($this->_input['ordersn']) ? $this->_input['ordersn'] : null;
        $ShopOrder = new ShopOrder;
        $res = $ShopOrder->orderPaySuccess($uid,$ordersn);
        return $res;
    }
}