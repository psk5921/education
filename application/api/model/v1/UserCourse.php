<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 22:22
 */

namespace app\api\model\v1;


use think\Model;
use app\code\Api;
use app\api\model\v1\Course;
use app\api\model\v1\UserVerificationLog;
class UserCourse   extends  Model
{
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime =  false;
    protected $updateTime = 'updatetime';

    /**
     * 根据uid获取用户的课程
     * @param $uid
     * @param int $page
     * @param int $pagesize
     * @return false|null|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOrderByOpenid($uid,$page=1,$pagesize=10){
        if(empty($uid)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $map = ['uid'=>$uid,'status'=>0];
        $order = 'createtime desc';
        $limit = ceil($page-1)*$pagesize.','.$pagesize;
        $field = 'id,course_id,class_id,time_id,count,end_time';
        $res = $this->where($map)->field($field)->order($order)->limit($limit)->select();
        $data = null;
        if($res){
            foreach ($res as $k=>$item){
                $Course = new Course;
                $data[$k] =  $Course->getCourseAssign($item['course_id'],$item['class_id'],$item['time_id']);
                $data[$k]['course_id'] = $item['course_id'];
                $data[$k]['id'] = $item['id'];
                $data[$k]['count'] = $item['count'];
                $data[$k]['end_time'] = !empty($item['end_time'])?date('Y-m-d', $item['end_time']):'';
            }
        }
        return $data;
    }


    /**
     * 查找课程相关数据
     * @param string $where
     * @param string $field
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findByWhere($where='',$field='*'){
        if(empty($where)) return false;
        return $this->where($where)->field($field)->find();
    }

    /**
     * 处理课程核销
     * @param $staff_id
     * @param $uid
     * @param $id
     * @param bool $first
     * @param string $month
     * @return false|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function verifyCourse($staff_id,$uid,$id,$first=false,$month=''){
        if(empty($staff_id) || empty($uid) || empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $where = ['id'=>$id];
        if($first){
            $start_time = strtotime(date('Y-m-d'));
            $endtime = strtotime("+{$month} month",$start_time);
            $update = ['start_time'=>$start_time,'end_time'=>$endtime];
            $res = $this->where($where)->dec('count',1)->update($update);
        }else{
            $res = $this->where($where)->setDec('count',1);
        }

        if($res){
            $UserVerificationLog = new UserVerificationLog;
            $data = [
              'uid' =>$uid,
              'staff_id' =>$staff_id,
              'ucourse_id' =>$id,
            ];
            $UserVerificationLog->_insert($data);
            //todo 写入消息记录
            $openid = Db::name('user')->where(['id'=>$uid])->value('openid');
            $courser_id = $this->where($where)->value('course_id');
            $title = Db::name('course')->where(['id'=>$courser_id])->value('course_title');
            write_msg($openid,'上课提醒','课程名称：'.$title.',消耗课时1.00,剩余课时：'. $data['count'].'.00');
            return api_json(Api::REQUEST_SUCCESS[0],'核销成功');
        }else{
            return api_json(Api::SYSTEM_ERROR[0],'核销失败,请重新尝试');
        }
    }
}