<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 11:03
 */

namespace app\api\model\v1;


use think\Exception;
use think\Model;
use app\code\Api;
use app\api\model\v1\User;
class UserDeliver  extends  Model
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
    /**
     * 新增/修改地址
     * @param $data
     * @return false|string
     */
    public function _insert($data)
    {
        try {
            $m_User = new User;
            $data['uid'] = $m_User->getIdByOpenid($data['openid']);
            unset($data['openid']);
            if(empty($data['uid'])){
                throw new Exception('用户信息查询有误');
            }
            if(isset($data['id']) &&  !empty($data['id']) ){
                if(!$this->findById($data['uid'],$data['id'])){
                    throw new Exception('收货地址信息有误');
                }
                $where = ['id'=>$data['id'],'uid'=>$data['uid']];
                unset($data['id']);
                //halt($data);
                if($data['is_default'] == 1){
                    $o_where = ['uid'=>$data['uid']];
                    $this->allowField(true)->isUpdate(true)->save(['is_default'=>0],$o_where);//其他的重置为非默认
                }
                $res =  $this->allowField(true)->isUpdate(true)->save($data,$where);//修改
            }else{
                if($data['is_default'] == 1){
                    $o_where = ['uid'=>$data['uid']];
                    $this->allowField(true)->isUpdate(true)->save(['is_default'=>0],$o_where);//其他的重置为非默认
                }
                unset($data['id']);
               // halt($data);
                $res =  $this->allowField(true)->isUpdate(false)->save($data);//新增
            }
            if ($res) {
                return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1]);
            } else {
                return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
            }
        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }

    /**
     * 更改地址为默认
     * @param $openid
     * @param $id
     * @return false|string
     */
    public function setDefault($openid,$id){
        try {
            $m_User = new User;
            $uid = $m_User->getIdByOpenid($openid);
            if(empty($uid)){
                throw new Exception('用户信息查询有误');
            }
            if(isset($id) &&  !empty($id) ){
                if(!$this->findById($uid,$id)){
                    throw new Exception('收货地址信息有误');
                }
                $where = ['id'=>$id,'uid'=>$uid];
                $o_where = ['uid'=>$uid];
                $this->allowField(true)->isUpdate(true)->save(['is_default'=>0],$o_where);//其他的重置为非默认
                $data = ['is_default'=>1];
                $res =  $this->allowField(true)->isUpdate(true)->save($data,$where);//修改
                if ($res) {
                    return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1]);
                } else {
                    return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
                }
            }else{
                return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
            }

        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }

    /**
     * 删除地址
     * @param $openid
     * @param $id
     * @return false|string
     */
    public function removeAddress($openid,$id){
        try {
            $m_User = new User;
            $uid = $m_User->getIdByOpenid($openid);
            if(empty($uid)){
                throw new Exception('用户信息查询有误');
            }
            if(isset($id) &&  !empty($id) ){
                if(!($ids=$this->findByInId($uid,str_replace('|',',',$id)))){
                    throw new Exception('收货地址信息有误');
                }
                //echo $this->findByInId($uid,str_replace('|',',',$id));die;
                $where = [['id','in',$ids],['uid','=',$uid]];
                $data = ['deleted'=>1];
                $res =  $this->allowField(true)->isUpdate(true)->save($data,$where);//修改
                if ($res) {
                    return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1]);
                } else {
                    return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
                }
            }else{
                return api_json(Api::PARAM_ERROR[0], '缺少必要参数');
            }

        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
        }
    }


    /**
     * 查看指定用户的地址信息
     * @param $openid
     * @param $id
     * @return false|string
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function viewAddress($openid,$id){
        $m_User = new User;
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            throw new Exception('用户信息查询有误');
        }
        $where = ['uid'=>$uid,'id'=>$id,'deleted'=>0];
        $field= 'delivery_name,delivery_mobile,delivery_area,delivery_address,is_default,province_id,city_id,country_id';
        $res =  $this->where($where)->field($field)->find();//修改
        if ($res) {
            return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1],$res);
        } else {
            return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
        }
    }


    /**
     * 查看指定用户的地址列表
     * @param $openid
     * @return false|string
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addressList($openid){
        $m_User = new User;
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            throw new Exception('用户信息查询有误');
        }
        $where = ['uid'=>$uid,'deleted'=>0];
        $field= 'id,delivery_name,delivery_mobile,delivery_area,delivery_address,is_default';
        $order='createtime desc';
        $res =  $this->where($where)->field($field)->order($order)->select();//修改
        if ($res) {
            return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1],$res);
        } else {
            return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
        }
    }


    /**
     * 查找地址信息
     */
    public function findById($uid,$id){
        $where = ['uid'=>$uid,'id'=>$id,'deleted'=>0];
        $res = $this->where($where)->find();
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * IN方式查询
     * @param $uid
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findByInId($uid,$id){
        $where = [['uid','=',$uid],['id','in',$id],['deleted','=',0]];
        $res = $this->where($where)->field('id')->select()->toArray();
        if(count($res)>0){
            return implode(',',array_column($res,'id'));
        }else{
            return false;
        }
    }

    /**
     * 获取用户的默认地址
     * @param $uid
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDefaultAddress($uid){
        if(empty($uid)){
            return false;
        }
        $where = [['uid','=',$uid],['deleted','=',0],['is_default','=',1]];
        $field = 'id,delivery_name,delivery_mobile,delivery_area,delivery_address';
        $res = $this->where($where)->field($field)->find();
        return $res;
    }

    /**
     * 判断是否是用户默认地址
     */
    public function isDefault($uid,$id){
        if(empty($uid) || empty($id)){
            return false;
        }
        $where = [['uid','=',$uid],['deleted','=',0],['id','=',$id]];
        $res = $this->where($where)->count();
        if($res == 1){
            return true;
        }
        return false;
    }
}