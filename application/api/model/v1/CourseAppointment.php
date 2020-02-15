<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22
 * Time: 23:05
 */

namespace app\api\model\v1;


use think\Model;
use app\code\Api;
class CourseAppointment extends  Model
{
    protected $insert = ['createtime'];


    protected function setCreatetimeAttr()
    {
        return time();
    }

    /**
     * 新增预约上门
     * @param $data
     * @return string
     */
    public function _insert($data)
    {
        try {
            if(isset($data['uid']) && $this->dayByOne($data['uid'])){
                return api_json(Api::PARAM_ERROR[0],'一天只能提交一次预约哦');
            }
            $res = $this->allowField(true)->save($data);//新增
            if ($res) {
                return api_json(Api::REQUEST_SUCCESS[0], '预约成功');
            } else {
                return api_json(Api::SYSTEM_ERROR[0], Api::SYSTEM_ERROR[1]);
            }
        } catch (Exception $e) {
            return api_json(Api::SYSTEM_ERROR[0], $e->getMessage());
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