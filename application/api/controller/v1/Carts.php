<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 15:44
 */

namespace app\api\controller\v1;

use app\code\Api;
use app\api\model\v1\ShopCart;
class Carts   extends  Base
{

    /**
     * 加入购物车
     * @return false|string
     */
    public  function addCart(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $gid= isset($this->_input['id']) ? $this->_input['id'] : 0;
        if(empty($gid)){
            return api_json(Api::OPENID_ERROR[0],"缺少必要参数");
        }
        $ShopCart = new ShopCart;
        $res = $ShopCart->addCart($openid,$gid);
        return $res;
    }

    /**
     * 购物车列表
     * @return false|string
     */
    public function cartList(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $ShopCart = new ShopCart;
        $res = $ShopCart->cartList($openid);
        return $res;
    }

    /**
     * 购物车选中/不选中
     * @return false|string
     */
    public function setOptionsSelected(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $cart_id= isset($this->_input['id']) ? $this->_input['id'] : '';
        if(empty($cart_id) || strpos($cart_id,'|') == false){
            return api_json(Api::OPENID_ERROR[0],"缺少必要参数");
        }
        $select= isset($this->_input['select']) ? $this->_input['select'] :'';
        if(!in_array($select,[0,1])){
            return api_json(Api::OPENID_ERROR[0],"缺少必要参数或者参数类型有误");
        }
        $ShopCart = new ShopCart;
        $res = $ShopCart->setOptionsSelected($openid,$cart_id,$select);
        return $res;
    }

    /**
     *  购物车数量增加/减少
     * @return false|string
     * @throws \think\Exception
     */
    public function setOptionsNum(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $cart_id= isset($this->_input['id']) ? $this->_input['id'] : '';
        if(empty($cart_id)){
            return api_json(Api::OPENID_ERROR[0],"缺少必要参数");
        }
        $type= isset($this->_input['type']) ? $this->_input['type'] :'';
        if(empty($type) || !in_array($type,[1,2])){
            return api_json(Api::OPENID_ERROR[0],"缺少必要参数或者参数类型有误");
        }
        $ShopCart = new ShopCart;
        $res = $ShopCart->setOptionsNum($openid,$cart_id,$type);
        return $res;
    }

    /**
     * 购物车批量删除
     * @return false|string
     * @throws \think\Exception
     */
    public function removeCart(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $cart_id= isset($this->_input['id']) ? $this->_input['id'] : '';
        if(empty($cart_id) || strpos($cart_id,'|') == false){
            return api_json(Api::OPENID_ERROR[0],"缺少必要参数");
        }
        $ShopCart = new ShopCart;
        $res = $ShopCart->removeCart($openid,$cart_id);
        return $res;
    }
}