<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 14:42
 */

namespace app\api\model\v1;


use think\Exception;
use think\Model;
use app\code\Api;
class User extends Model
{
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    /**
     * 新增会员数据
     */
    public function _insert($data){
        try{
            if(isset($data['openid']) && $this->findByOpenid($data['openid'])){
                $where = ['openid'=>$data['openid']];
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
     * 查找会员数据
     */
    public function findByOpenid($openid='null',$field='*'){
        if(empty($openid)) return false;
        $where = ['openid'=>$openid];
        $res = $this->where($where)->field($field)->find();
        if($res){
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 根据openid 返回ID
     */
    public function getIdByOpenid($openid){
        $where = ['openid'=>$openid];
        return $this->where($where)->value('id');
    }
}