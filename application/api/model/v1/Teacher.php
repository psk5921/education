<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 16:44
 */

namespace app\api\model\v1;


use think\Db;
use think\Model;
use app\code\Api;
class Teacher  extends  Model
{

    /**
     * 获取教师列表
     * @param int $page
     * @param int $pagesize
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($page=1,$pagesize=10){
        $where = ['status'=>0,'deleted'=>0];
        $field = 'uid,teacher_name,teacher_img,id,teacher_short,zans';
        $order = 'zans desc,createtime desc';
        $limit = ceil($page-1)*$pagesize.','.$pagesize;
       $res =  $this->where($where)->field($field)->order($order)->limit($limit)->select();
       if($res){
           foreach ($res  as &$item){
               $item['img_height'] = !empty($item['teacher_img'])?getimagesize($item['teacher_img'])[1]:'';
           }
           unset($item);
       }
       return $res;
    }

    /**
     * 获取教师详情
     * @param $id
     * @return string
     */
    public function getInfoById($id){
        if(empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        $field = 'teacher_name,teacher_img,teacher_short,zans,teacher_subject,teacher_age,teacher_nation,teacher_introduce';
        $info = $this->where($where)->field($field)->find();
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'教师信息查询有误');
        }
        $r_where = ['deleted'=>0,'tid'=>$id];
        $r_field='weekday,time_start_h,time_start_m,time_end_h,time_end_m';
        $relax = Db::name('teacher_freetime')->where($r_where)->field($r_field)->select();
        $r = null;
        if(!empty($relax)){
            foreach ($relax as $k=>$re){
                $r[$k]['week'] = $this->week_convert($re['weekday']);
                $r[$k]['time'] = $re['time_start_h'].':'.$re['time_start_m'].'-'.$re['time_end_h'].':'.$re['time_end_m'];
            }
        }
        $info['relax'] = $r;
        $t_where = ['tid'=>$id,'deleted'=>0,'status'=>1];
        $eva_count = (int)Db::name('teacher_evaluate')->where($t_where)->count(1); //获取该老师评价总数量
        $info['evaluate_count'] = $eva_count;
        $e_where = ['TeacherEvaluate.tid'=>$id,'TeacherEvaluate.deleted'=>0,'TeacherEvaluate.status'=>1];
        $evaluate = Db::view('TeacherEvaluate','content,createtime')
            ->view('User','avatar,nickname','TeacherEvaluate.uid=User.id')
            ->where($e_where)
            ->order('createtime desc')
            ->limit(5)
            ->select();
        if($evaluate){
            foreach ($evaluate as &$item){
                $item['createtime'] = date('Y-m-d',$item['createtime']);
            }
            unset($item);
        }
        $info['evaluate'] = $evaluate;
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$info);
    }


    /**
     * 点赞数量增加
     * @param $id
     * @return string
     */
    public function likeInc($id){
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        if($this->where($where)->setInc('zans',1)){
            return api_json(Api::REQUEST_SUCCESS[0],'点赞成功');
        }else{
            return api_json(Api::SYSTEM_ERROR[0],'点赞失败,请重新尝试');
        }
    }

    /**
     * 获取指定教师的名称以及空闲时间
     */
    public function getRelaxById($id){
        if(empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        $field = 'teacher_name';
        $info = $this->where($where)->value($field);
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'教师信息查询有误');
        }
        $data['name'] = $info;
        $r_where = ['deleted'=>0,'tid'=>$id];
        $r_field='id,weekday,time_start_h,time_start_m,time_end_h,time_end_m';
        $relax = Db::name('teacher_freetime')->where($r_where)->field($r_field)->select();
        $r = null;
        if(!empty($relax)){
            foreach ($relax as $k=>$re){
                $r[$k]['free_id'] = $re['id'];
                $r[$k]['time_desc'] = $this->week_convert($re['weekday'])." ".$re['time_start_h'].':'.$re['time_start_m'].'-'.$re['time_end_h'].':'.$re['time_end_m'];
            }
        }
        $data['relax'] = $r;
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$data);
    }


    /**
     * 星期转换
     * @param $num
     * @return bool|string
     */
    final function week_convert($num){
        if(!$num) return false;
        switch ($num){
            case 1:
                $str = "周一";
                break;
            case 2:
                $str = "周二";
                break;
            case 3:
                $str = "周三";
                break;
            case 4:
                $str = "周四";
                break;
            case 5:
                $str = "周五";
                break;
            case 6:
                $str = "周六";
                break;
            case 7:
                $str = "周天";
                break;
            default:
                $str = '';
        }
        return $str;
    }
}