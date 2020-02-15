<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 14:56
 */

namespace app\api\model\v1;


use think\Db;
use think\Exception;
use think\Model;
use app\code\Api;
use app\api\model\v1\ShopGoods;
use app\api\validate\v1\User as v_User;

class ShopCart extends Model
{
    protected $insert = ['createtime'];
    protected $update = ['updatetime'];


    protected function setCreatetimeAttr()
    {
        return time();
    }

    protected function setUpdatetimeAttr()
    {
        return time();
    }
    //todo 加入购物车  购物车列表 购物车信息修改（选中 不选中 数量增加减少） 购物车信息批量删除 购物车合计

    /**
     * 加入购物车
     * @param $openid
     * @param $gid
     * @return false|string
     */
    public function addCart($openid, $gid)
    {
        try {
            $m_User = new User;
            $ShopGoods = new ShopGoods;
            $uid = $m_User->getIdByOpenid($openid);
            if (empty($uid)) {
                throw new Exception('用户信息查询有误');
            }
            if (!$ShopGoods->goodsIsUseful($gid)) {
                throw new Exception('商品信息有误');
            }
            //todo 判断加入购物车的商品是否有记录  没有记录就新增 有记录就在原有基础上商品数量加1
            $where = ['uid' => $uid, 'gid' => $gid, 'deleted' => 0];
            if ($this->where($where)->count() >= 1) {
                //防止出错
                $res = $this->where($where)->setInc('num', 1);
            } else {
                $data = [
                    'uid' => $uid,
                    'gid' => $gid,
                    'num' => 1,
                ];
                $res = $this->allowField(true)->isUpdate(false)->save($data);
            }
            if ($res) {
                return api_json(Api::REQUEST_SUCCESS[0], '添加购物车成功');
            } else {
                return api_json(Api::SYSTEM_ERROR[0], '添加购物车失败,请重新尝试');
            }
        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }


    /**
     * 购物车列表
     * @param $openid
     * @return false|string
     */
    public function cartList($openid)
    {
        try {
            $m_User = new User;
            $ShopGoods = new ShopGoods;
            $uid = $m_User->getIdByOpenid($openid);
            if (empty($uid)) {
                throw new Exception('用户信息查询有误');
            }
            //todo 判断当前用户购物车是否有数据 无数据返回空 有数据判断数据的有效性 如果商品下架 购物车直接系统删除
            $where = ['uid' => $uid, 'deleted' => 0];
            $order = 'createtime desc';
            $cart = $this->where($where)->field('id,gid,selected,num')->order($order)->select();
            $map = [
                'cart' => null,
                'total' => 0,
            ];
            if (!empty($cart)) {
                foreach ($cart as &$item) {
                    if (!$ShopGoods->goodsIsUseful($item['gid'])) {
                        $this->operation_del($item['id']);
                        break;
                    }
                    if (!($goods = $ShopGoods->getFieldById($item['gid']))) {
                        $this->operation_del($item['id']);
                        break;
                    }
                    $item['title'] = $goods['title'];
                    $item['short_title'] = $goods['short_title'];
                    $item['price'] = $goods['price'];
                    $item['img'] = $goods['img'];
                }
                unset($item);
                $map['cart'] = $cart;
                $map['total'] = $this->calculate($openid);
            }
            return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], $map);
        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }

    /**
     * 购物车选中/不选中
     * @param $openid
     * @param $id
     * @param $select
     * @return false|string
     */
    public function setOptionsSelected($openid,$id,$select)
    {
        try {
            $m_User = new User;
            $uid = $m_User->getIdByOpenid($openid);
            if (empty($uid)) {
                throw new Exception('用户信息查询有误');
            }
            //todo 更改数据库选中的状态 更新数据库 更新合计
            $where = [['id','in',trim(str_replace('|',',',$id),',')],['uid','=',$uid] ,['deleted','=',0]];
            $cart = $this->where($where)->count();
            if(!$cart){
                throw new Exception('购物车信息有误');
            }
            if(!in_array($select,[0,1])){
                throw new Exception('参数不存在或是参数类型有误');
            }
            $data =['selected'=>$select];
            $this->isUpdate(true)->save($data,$where);
            $total = $this->calculate($openid);
            return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], $total);
        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }

    /**
     * 购物车数量增加/减少
     * @param $openid
     * @param $id
     * @param $type
     * @return false|string
     * @throws Exception
     */
    public function setOptionsNum($openid,$id,$type)
    {
        $m_User = new User;
        $uid = $m_User->getIdByOpenid($openid);
        if (empty($uid)) {
            throw new Exception('用户信息查询有误');
        }
        //todo 更改数据库商品的数量 更新数据库 更新合计
        $where = ['id'=>$id,'uid' => $uid, 'deleted' => 0];
        $cart = $this->where($where)->count(1);
        if(!$cart){
            throw new Exception('购物车信息有误');
        }
        if(!in_array($type,[1,2])){
            throw new Exception('参数不存在或是参数类型有误');
        }
        if( $type == 1){
            //增加
            $this->where($where)->setInc('num',1);
            $total = $this->calculate($openid);
        }

        if( $type == 2){
            //减少
            $get_num = $this->where($where)->value('num');
            if($get_num == 1){
                throw new Exception('已经是最小值了');
            }else{
                $this->where($where)->setDec('num',1);
            }
            $total = $this->calculate($openid);
        }
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1], $total);
    }

    /**
     * 购物车批量删除
     * @param $openid
     * @param $id
     * @return false|string
     * @throws Exception
     */
    public function removeCart($openid,$id)
    {
        $m_User = new User;
        $uid = $m_User->getIdByOpenid($openid);
        if (empty($uid)) {
            throw new Exception('用户信息查询有误');
        }
        //todo 批量删除数据 返回合计
        $where = [['id','in',trim(str_replace('|',',',$id),',')],['uid','=', $uid]];
        $data =['deleted' => 1];
        $cart = $this->isUpdate(true)->save($data,$where);
        if(!$cart){
            return api_json(Api::SYSTEM_ERROR[0], '系统有误,请重新尝试');
        }
        $total = $this->calculate($openid);
        return api_json(Api::REQUEST_SUCCESS[0], '删除成功',$total);
    }


    /**
     * 计算合计
     * @param $openid
     * @return float|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    final function calculate($openid)
    {
        $m_User = new User;
        $ShopGoods = new ShopGoods;
        $uid = $m_User->getIdByOpenid($openid);
        $total = 0.00;
        if (empty($uid)) {
            return $total;
        }
        //todo 只计算选中的当前用户选中的商品数据
        $where = ['uid' => $uid, 'deleted' => 0];
        $order = 'createtime desc';
        $cart = $this->where($where)->field('gid,selected,num')->order($order)->select();
        if (!empty($cart)) {
            foreach ($cart as $item) {
                if (!$ShopGoods->goodsIsUseful($item['gid'])) {
                    $this->operation_del($item['id']);
                    break;
                }
                if (!($goods = $ShopGoods->getFieldById($item['gid'], 'price'))) {
                    $this->operation_del($item['id']);
                    break;
                }
                if ($item['selected'] == 1) {
                    $mul = bcmul($item['num'], $goods['price'], 2);
                    $total = bcadd($total, $mul, 2);
                }
            }
        }
        return $total;
    }

    /**
     * 执行购物车删除操作
     * @param $id
     * @return bool
     */
    final function operation_del($id)
    {
        $data = ['deleted' => 1];
        $where = ['id' => $id];
        return $this->isUpdate(true)->save($data, $where);
    }


    /**
     * 获取当前选中的购物车商品
     * @param $openid
     * @return null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCartFromUid($openid){
        //todo  返回购物车符合条件的 数据 格式为  商品id=>数量
        $m_User = new User;
        $uid = $m_User->getIdByOpenid($openid);
        if (empty($uid)) {
            return null;
        }
        $where = ['uid'=>$uid,'deleted' => 0,'selected'=>1];
        $field = 'gid,num';
        $order = 'createtime desc';
        $res = $this->where($where)->field($field)->order($order)->select();
        if($res){
            $data = null;
            foreach ($res as $v){
                $data[$v['gid']] = $v['num'];
            }
            return $data;
        }else{
            return null;
        }
    }
}