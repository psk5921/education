<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/22
 * Time: 14:03
 */

namespace app\api\model\v1;


use think\Exception;
use think\Model;
use app\code\Api;
class UserHomeAddress  extends Model
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
     * 用户的家庭住址 新增或修改
     * @param $uid
     * @param $data
     * @return false|string
     */
    public function _insert($uid,$data){
        try{
            if(isset($uid) && $this->findByUid($uid) == 1){
                $where = ['uid'=>$uid];
                $res =  $this->allowField(true)->isUpdate(true)->save($data,$where);//修改
            }else{
                $res =  $this->allowField(true)->save($data);//新增
            }
            if($res){
                return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1]);
            }else{
                return api_json(Api::SYSTEM_ERROR[0],Api::SYSTEM_ERROR[1]);
            }
        }catch (Exception $e){
            return api_json(Api::SYSTEM_ERROR[0],$e->getMessage());
        }
    }

    /**
     * 查找用户信息是否存在
     * @param $uid
     * @return int
     */
    public function findByUid($uid){
        return (int)$this->where(['uid'=>$uid])->count();
    }
}