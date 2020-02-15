<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22
 * Time: 22:46
 */

namespace app\api\model\v1;

use think\Db;
use think\Model;
use app\code\Api;
class CourseEvaluate  extends  Model
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
            if($this->dayByOne($data['uid'],$data['cid'])){
                return api_json(Api::PARAM_ERROR[0],'一天只能提交一次评价哦');
            }
            $res = $this->allowField(true)->save($data);//新增
            if ($res) {
                $c_where = ['id'=>$data['cid'],'status'=>1,'deleted'=>0];
                Db::name('course')->where($c_where)->setInc('evaluate_total',1);
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
    public function dayByOne($uid,$cid)
    {
        $day = strtotime(date('Y-m-d')); //今天时间
        $where = [['uid','=',$uid],['createtime','>',$day],['cid','=',$cid]];
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
        $where =  ['id'=>$id,'status'=>1,'deleted'=>0];
        $field = 'id,course_thumb,course_title';
        $info = Db::name('course')->where($where)->field($field)->find();
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'课程信息查询有误');
        }
        if($info){
            $info['course_thumb'] = unserialize(mb_unserialize($info['course_thumb']));
        }
        $evaluate['course'] = $info;
        $e_where = ['CourseEvaluate.cid'=>$id,'CourseEvaluate.status'=>1,'CourseEvaluate.deleted'=>0];
        $order = 'createtime desc';
        $limit = ceil($page-1)*$pagesize.','.$pagesize;
        $c_where = ['cid'=>$id,'status'=>1,'deleted'=>0];
        $evaluate['total'] = Db::name('CourseEvaluate')->where($c_where)->count(1);
        $evaluate['evaluate'] = Db::view('CourseEvaluate','content,createtime')
            ->view('User','avatar,nickname','CourseEvaluate.uid=User.id')
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