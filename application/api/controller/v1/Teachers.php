<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/22
 * Time: 15:45
 */

namespace app\api\controller\v1;

use app\api\validate\v1\TeacherSupply as v_TeacherSupply;
use app\api\validate\v1\CourseAppointment;
use app\api\model\v1\TeacherSupply;
use app\api\model\v1\User as m_User;
use app\api\model\v1\Teacher;
use app\api\model\v1\TeacherEvaluate;
use app\api\model\v1\CourseAppointment as m_CourseAppointment;
use app\code\Api;
use think\Db;

class Teachers  extends Base
{
    /**
     * 用户申请成为老师 填写一些基本信息
     */
    public function join_us(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        $nationality = isset($this->_input['nationality']) ? $this->_input['nationality'] : null;
        $work_type = isset($this->_input['work_type']) ? $this->_input['work_type'] : null;
        $subject = isset($this->_input['subject']) ? $this->_input['subject'] : null;
        $contact_way = isset($this->_input['contact_way']) ? $this->_input['contact_way'] : null;
        $contact = isset($this->_input['contact']) ? $this->_input['contact'] : null;
        $avaliable_time = isset($this->_input['avaliable_time']) ? $this->_input['avaliable_time'] : null;
        $images = isset($this->_input['images']) ? $this->_input['images'] : null;
        $introduce = isset($this->_input['introduce']) ? $this->_input['introduce'] : null;
        if(!empty($introduce) && mb_strlen($introduce) > 255){
            return api_json(Api::PARAM_ERROR[0],'自我介绍字符超限');
        }
        $m_User = new m_User();
        $TeacherSupply = new TeacherSupply();
        $v_TeacherSupply = new v_TeacherSupply();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $map = compact('uid','nationality','work_type','subject','contact_way','contact','avaliable_time','images','introduce');
        //新增
        if (!$v_TeacherSupply->check($map)) {
            return api_json(Api::PARAM_ERROR[0],$v_TeacherSupply->getError());
        }
        $res = $TeacherSupply->_insert($map);
        return $res;
    }

    /**
     * 获取教师列表
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function teachers_list(){
        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $Teacher = new Teacher;
        $list = $Teacher->getList($page,$pagesize);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],empty($list)?null:$list);
    }


    /**
     * 获取教师详情
     * @return string
     */
    public function teachers_detail(){
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : null;
        $Teacher = new Teacher;
        $res = $Teacher->getInfoById($id);
        return $res;
    }


    /**
     * 获取指定教师全部评价带分页
     * @return string
     */
    public function getAllTeacherEvaluate(){
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : null;
        if(empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        $info = Db::name('teacher')->where($where)->count(1);
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'教师信息查询有误');
        }
        $TeacherEvaluate = new TeacherEvaluate;
        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $list = $TeacherEvaluate->getList($id,$page,$pagesize);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],empty($list)?null:$list);
    }


    /**
     * 给老师点赞
     * @return string
     */
    public function giveTeacherLike(){
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : null;
        $openid = isset($this->_input['openid']) ? (int)$this->_input['openid'] : null;
        if(empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        $info = Db::name('teacher')->where($where)->count(1);
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'教师信息查询有误');
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $e_where = ['UserCourse.uid'=>$uid];
        $evaluate = Db::view('UserCourse','id')
            ->view('Course','teacher_id','UserCourse.course_id=Course.id')
            ->where($e_where)
            ->find();
        if($evaluate['teacher_id'] != $id ){
            return api_json(Api::PARAM_ERROR[0],'只有购买该老师的课程才能点赞');
        }
        //todo 该老师点赞数加1  暂时不加点赞记录
        $Teacher = new Teacher;
        $res = $Teacher->likeInc($id);
        return $res;
    }

    /**
     * 给老师评价
     * @return string
     */
    public function giveTeacherEvaluate(){
        $id = isset($this->_input['id']) ? (int)$this->_input['id'] : null;
        $openid = isset($this->_input['openid']) ? (int)$this->_input['openid'] : null;
        $content = isset($this->_input['content']) ? (int)$this->_input['content'] : null;
        if(empty($id)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        if(empty($content)){
            return api_json(Api::PARAM_ERROR[0],'请输入评价内容');
        }
        if(mb_strlen($content)>500){
            return api_json(Api::PARAM_ERROR[0],'评价内容超限');
        }
        $where =  ['id'=>$id,'status'=>0,'deleted'=>0];
        $info = Db::name('teacher')->where($where)->count(1);
        if(empty($info)){
            return api_json(Api::SYSTEM_ERROR[0],'教师信息查询有误');
        }

        $e_where = ['UserCourse.uid'=>$uid];
        $evaluate = Db::view('UserCourse','id')
            ->view('Course','teacher_id','UserCourse.course_id=Course.id')
            ->where($e_where)
            ->find();
        if($evaluate['teacher_id'] != $id ){
            return api_json(Api::PARAM_ERROR[0],'只有购买该老师的课程才能评价');
        }

        //todo 该老师增加一条评价记录  现在默认审核通过
        $TeacherEvaluate = new TeacherEvaluate;
        $tid = $id;
        $status = 1;
        $map = compact('tid','uid','content','status');
        $res = $TeacherEvaluate->_insert($map);
        return $res;
    }


    /**
     * 预约上门（包含在教师详情页面预约以及个人中心预约）
     * @return false|string
     */
    public function homeBook(){
        $type = isset($this->_input['type']) ? $this->_input['type'] : null;
        if(!in_array($type,[1,2])){
            return api_json(Api::PARAM_ERROR[0],'参数类型有误');
        }
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $hope_time = isset($this->_input['hope_time']) ? $this->_input['hope_time'] : null;
        $baby_sex = isset($this->_input['baby_sex']) ? $this->_input['baby_sex'] : null;
        $baby_name = isset($this->_input['baby_name']) ? $this->_input['baby_name'] : null;
        $mobile = isset($this->_input['mobile']) ? $this->_input['mobile'] : null;
        $baby_en_name = isset($this->_input['baby_en_name']) ? $this->_input['baby_en_name'] : null;
        $birth = isset($this->_input['birth']) ? $this->_input['birth'] : null;
        if($birth && count(explode('-',$birth)) !=3 ){
            return api_json(Api::PARAM_ERROR[0],'宝宝的出生日期信息有误');
        }
        $baby_birth_year = $birth[0];
        $baby_birth_month = $birth[1];
        $baby_birth_day = $birth[2];
        $baby_school = isset($this->_input['baby_school']) ? $this->_input['baby_school'] : null;
        $address = isset($this->_input['address']) ? $this->_input['address'] : null;
        $remarks = isset($this->_input['remarks']) ? $this->_input['remarks'] : null;
        if($type == 1){
         //todo 选中老师的上门预约
            $tid = isset($this->_input['tid']) ? $this->_input['tid'] : null;
            if(empty($tid)){
                return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
            }
            $where =  ['id'=>$tid,'status'=>0,'deleted'=>0];
            $info = Db::name('teacher')->where($where)->count(1);
            if(empty($info)){
                return api_json(Api::PARAM_ERROR[0],'教师信息查询有误');
            }
            $free_id = isset($this->_input['free_id']) ? $this->_input['free_id'] : null;
            if(empty($free_id)){
                return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
            }
            $f_where =  ['id'=>$free_id,'deleted'=>0,'tid'=>$tid];
            $info = Db::name('teacher_freetime')->where($f_where)->count(1);
            if(empty($info)){
                return api_json(Api::PARAM_ERROR[0],'信息有误');
            }
            $map = compact('uid','hope_time','mobile','baby_sex','baby_name','baby_en_name','baby_birth_year','baby_birth_month','baby_birth_day','baby_school','address','remarks','tid','free_id');
        }else{
            $map = compact('uid','hope_time','mobile','baby_sex','baby_name','baby_en_name','baby_birth_year','baby_birth_month','baby_birth_day','baby_school','address','remarks');
        }
        //新增
        $CourseAppointment = new CourseAppointment();
        if (!$CourseAppointment->check($map)) {
            return api_json(Api::PARAM_ERROR[0],$CourseAppointment->getError());
        }
        $m_CourseAppointment = new m_CourseAppointment;
        $res = $m_CourseAppointment->_insert($map);
        return $res;
    }

    /**
     * 预约上门（教师详情页面预约）
     * @return false|string
     */
    public function getHomeBook(){
        //todo 获取教师的名称以及空闲时间
        $tid = isset($this->_input['id']) ? $this->_input['id'] : null;
        if(empty($tid)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $where =  ['id'=>$tid,'status'=>0,'deleted'=>0];
        $info = Db::name('teacher')->where($where)->count(1);
        if(empty($info)){
            return api_json(Api::PARAM_ERROR[0],'教师信息查询有误');
        }
        $Teacher = new Teacher;
        $res = $Teacher->getRelaxById($tid);
        return  $res;
    }







    /**
     * 文件本地上传 暂不考虑第三方上传
     * @return false|string
     */
    public  function imageUpload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/uploads/ 目录下 1M
        $info = $file->validate(['size'=>106496,'ext'=>'jpg,png'])->rule('md5')->move( 'teacher');
        if($info){
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/teacher/'.$info->getSaveName());
        }else{
            // 上传失败获取错误信息
            return api_json(Api::PARAM_ERROR[0],$file->getError());
        }
    }
}