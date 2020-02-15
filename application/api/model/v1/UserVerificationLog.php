<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 23:40
 */

namespace app\api\model\v1;


use think\Model;
use app\code\Api;
class UserVerificationLog   extends Model
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
            $res =  $this->allowField(true)->save($data);//新增
            if($res){
                return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1]);
            }else{
                return api_json(Api::SYSTEM_ERROR[0],Api::SYSTEM_ERROR[1]);
            }
        }catch (Exception $e){
            return api_json(Api::SYSTEM_ERROR[0],$e->getMessage());
        }
    }
}