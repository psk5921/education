<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/22
 * Time: 15:49
 */

namespace app\api\model\v1;


use think\Model;
use app\code\Api;
class TeacherSupply  extends  Model
{
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    public function _insert($data){
        try{
            if(isset($data['uid']) && $this->dayByOne($data['uid'])){
                return api_json(Api::PARAM_ERROR[0],'一天只能提交一次信息');
            }
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

    /**
     * 当天是否留过言
     * @param $uid
     * @return bool
     */
    public function dayByOne($uid)
    {
        $day = strtotime(date('Y-m-d')); //今天时间
        $where = [['uid','=',$uid],['createtime','>',$day]];
        $count = $this->where($where)->count(1);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }
}