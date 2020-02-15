<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22
 * Time: 21:45
 */

namespace app\api\model\v1;


use think\Db;
use think\Model;
use app\code\Api;
class TeacherEvaluate   extends  Model
{
    protected $insert = ['createtime'];


    protected function setCreatetimeAttr()
    {
        return time();
    }


    /**
     * 新增评价
     * @param $data
     * @return string
     */
    public function _insert($data)
    {
        try {
            if($this->dayByOne($data['uid'],$data['tid'])){
                return api_json(Api::PARAM_ERROR[0],'一天只能提交一次评价哦');
            }
            $res = $this->allowField(true)->save($data);//新增
            if ($res) {
                return api_json(Api::REQUEST_SUCCESS[0], '评价成功');
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
    public function dayByOne($uid,$tid)
    {
        $day = strtotime(date('Y-m-d')); //今天时间
        $where = [['uid','=',$uid],['createtime','>',$day],['tid','=',$tid]];
        $count = $this->where($where)->count(1);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取评价列表
     * @param $id
     * @param int $page
     * @param int $pagesize
     * @return mixed
     */
    public function getList($id,$page=1,$pagesize=10){
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        $field = 'teacher_name,teacher_img,teacher_short';
        $info = Db::name('teacher')->where($where)->field($field)->find();
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'教师信息查询有误');
        }
        $evaluate['teacher'] = $info;
        $e_where = ['TeacherEvaluate.tid'=>$id,'TeacherEvaluate.status'=>1,'TeacherEvaluate.deleted'=>0];
        $order = 'createtime desc';
        $limit = ceil($page-1)*$pagesize.','.$pagesize;
        $c_where = ['tid'=>$id,'status'=>1,'deleted'=>0];
        $evaluate['total'] = Db::name('teacher_evaluate')->where($c_where)->count(1);
        $evaluate['evaluate'] = Db::view('TeacherEvaluate','content,createtime')
            ->view('User','avatar,nickname','TeacherEvaluate.uid=User.id')
            ->where($e_where)
            ->order($order)
            ->limit($limit)
            ->select();
        if($evaluate['evaluate']){
            foreach ($evaluate['evaluate'] as &$item){
                $item['createtime'] = date('Y-m-d',$item['createtime']);
            }
            unset($item);
        }
        return $evaluate;
    }
}