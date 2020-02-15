<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 15:10
 */

namespace app\admin\controller;

use think\Db;
use app\admin\model\VipModel;
use app\admin\validate\Teacher as Teachercheck;

class Teacher extends Base
{

    public function ls()
    {

        if (request()->isAjax()) {

            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            $start = input('start');
            $end = input('end');
            $status = input('status');
            $where = ['t.deleted' => 0];
            $search = array();
            if ($name) {
                $search[] = array('t.teacher_name', 'like', "%" . RemoveXSS($name) . "%");
            }
            if ($status && $status !== '') {
                $search[] = array('t.status', '=', $status);
            }
            if ($start) {
                $start = strtotime($start);
                $search[] = ['t.createtime', '>', $start];
            }
            if ($end) {
                $end = strtotime($end);
                $search[] = ['t.createtime', '<', $end];
            }
            $order = 't.id desc';
            $sel = Db::name('teacher')
                ->alias('t')
                ->join('course_category g', 't.category_id = g.id', 'left')
                ->field('t.*,g.edu_category_name')
                ->where($where)
                ->where($search)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->order($order)
                ->select();
            if ($sel) {
                foreach ($sel as $key => &$v) {
                    $v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);
                    if ($v['status'] == 0) {
                        $v['status'] = "<span style='color:green'>正常</span>";
                    } elseif ($v['status'] == 1) {
                        $v['status'] = "<span style='color:red'>禁用</span>";
                    }
                    switch ($v['teacher_subject']) {
                        case  1:
                            $v['teacher_subject'] = "English";
                            break;
                        case  2:
                            $v['teacher_subject'] = "Maths";
                            break;
                        case  3:
                            $v['teacher_subject'] = "History";
                            break;
                        case  4:
                            $v['teacher_subject'] = "Economics";
                            break;
                        case  5:
                            $v['teacher_subject'] = "ILETS";
                            break;
                        case  6:
                            $v['teacher_subject'] = "SAT";
                            break;
                        case  7:
                            $v['teacher_subject'] = "Physics";
                            break;
                        case  8:
                            $v['teacher_subject'] = "Art";
                            break;
                        case  9:
                            $v['teacher_subject'] = "Spanish";
                            break;
                        case  10:
                            $v['teacher_subject'] = "French";
                            break;
                        case  11:
                            $v['teacher_subject'] = "Business English";
                            break;
                        case  12:
                            $v['teacher_subject'] = "Travel English";
                            break;
                    }
                    if ($v['teacher_img'] == '') {
                        $v['teacher_img'] = "暂无头像";
                    }
                }
            }
            $count = Db::name('teacher')
                ->alias('t')
                ->join('course_category g', 't.category_id = g.id', 'left')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);

        }


        return $this->fetch();
    }


    //添加教师
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['teacher_name','uid', 'teacher_img', 'teacher_subject', 'category_id', 'teacher_short', 'teacher_age', 'teacher_nation', 'teacher_introduce',];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['teacher_img'] = trim($data['teacher_img'], '|');
            $data['zans'] = !isset($data['zans']) ? 0 : (int)$data['zans'];
            if (!in_array($data['status'], [0, 1])) {
                return diy_json('', -1, '参数有误');
            }
            $data['createtime'] = time();
            unset($data['file']);
            $add = Db::name('teacher')->insert($data);
            //更改会员身份为教师
            Db::name('user')->where(['id'=>$data['uid']])->update(['identity'=>2]);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $where = ['status' => 0, 'deleted' => 0];
        $types = Db::name('course_category')->where($where)->select();
        $user_field = 'id,nickname,mobile';
        $user_where[] = ['identity','<',2];
        $user = Db::name('user')->field($user_field)->where($user_where)->select();
        if($user){
            foreach ($user as &$item){
                $item['info'] = (empty($item['nickname'])?'匿名':$item['nickname']) . ' ' .(empty($item['mobile'])?'手机号未绑定':$item['mobile']);
                unset($item['nickname']);
                unset($item['mobile']);
            }
            unset($item);
        }
        $this->assign('types', $types);
        $this->assign('user', $user);
        return $this->fetch();
    }


    //编辑教师
    public function update()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id','teacher_name', 'teacher_img', 'teacher_subject', 'category_id', 'teacher_short', 'teacher_age', 'teacher_nation', 'teacher_introduce',];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            $id = (int)$data['id'];
            $where = ['id' => $id,'deleted'=>0];
            $sel = Db::name('teacher')->where($where)->find();
            if(!$sel){
                return diy_json('', -1, '教师信息不存在');
            }
            unset($item);
            unset($data['id']);
            $data['teacher_img'] = trim($data['teacher_img'], '|');
            $data['zans'] = !isset($data['zans']) ? 0 : (int)$data['zans'];
            if (!in_array($data['status'], [0, 1])) {
                return diy_json('', -1, '参数有误');
            }
            $data['updatetime'] = time();
            unset($data['file']);
            $res = Db::name('teacher')->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $id = input('id');
        $where = ['id' => $id,'deleted'=>0];
        $sel = Db::name('teacher')->where($where)->find();
        if(!$sel){
            $this->error('教师信息不存在',url('ls'));
        }
        $where = ['status' => 0, 'deleted' => 0];
        $types = Db::name('course_category')->where($where)->select();
        $this->assign('types', $types);
        $this->assign('sel', $sel);
        $user_field = 'id,nickname,mobile';
        $user_where = ['id'=>$sel['uid']];
        $user = Db::name('user')->field($user_field)->where($user_where)->find();
        if($user){
            $user['info'] = (empty($user['nickname'])?'匿名':$user['nickname']) . ' ' .(empty($user['mobile'])?'手机号未绑定':$user['mobile']);
            unset($user['nickname']);
            unset($user['mobile']);
        }
        $this->assign('user', $user);
        return $this->fetch();
    }


    //删除教师
    public function delete()
    {

        if (request()->isAjax()) {
            $id = (int)input('id');
            $where = ['id' => $id, 'deleted' => 0];
            if (Db::name('teacher')->where($where)->count(1) == 0) {
                return diy_json('', -1, '教师信息不存在');
            }
            $update = ['deleted' => 1];
            $res = Db::name('teacher')->where($where)->update($update);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }

    }


    //课程图片内容上传
    public function uploadTeacher()
    {
        if (request()->isPost()) {
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size' => 2097152, 'ext' => 'jpg,png,jpeg'])->rule('uniqid')->move('teacher_admin/layedit');
            if ($info) {
                $image_name = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/teacher_admin/layedit/' . $info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' => [
                        'src' => $image_name,
                        'title' => '',
                    ]
                ];
                return json($json);
            } else {
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' => [
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return json($json);
            }
        }
    }


    //课程图片单图上传
    public function simple()
    {
        if (request()->isPost()) {
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size' => 2097152, 'ext' => 'jpg,png,jpeg'])->rule('uniqid')->move('teacher_admin/simple');
            if ($info) {
                $image_name = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/teacher_admin/simple/' . $info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' => [
                        'src' => $image_name,
                        'title' => '',
                    ]
                ];
                return json($json);
            } else {
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' => [
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return json($json);
            }
        }
    }


    ##################################################################################
    //老师教师时间安排
    public function schedule()
    {
        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $teacher_id = input('teacher_id');
            $where =['tid'=>$teacher_id,'deleted'=>0];
            $order = 'id desc';
            $sel = Db::name('teacher_freetime')
                ->where($where)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if($sel){
                foreach ($sel as &$v) {
                    $v['starttime'] = $v['time_start_h'] . '时' . $v['time_start_m'] . '分';
                    $v['endtime'] = $v['time_end_h'] . '时' . $v['time_end_m'] . '分';
                    $v['weekday'] = $this->week_convert($v['weekday']);
                    $v['qujian_time'] = $v['weekday'].' '.$v['starttime'] . ' 至 ' . $v['endtime'];
                    $v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);
                }
            }
            $count = Db::name('teacher_freetime')
                ->where($where)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        $teacher_id = (int)input('teacher_id');
        $where = ['id'=>$teacher_id,'deleted'=>0];
        if(Db::name('teacher')->where($where)->count(1) == 0){
            $this->error('教师信息不存在',url('ls'));
        }
        $this->assign('teacher_id', $teacher_id);
        return $this->fetch();
    }

    /**
     * 星期转换
     * @param $num
     * @return bool|string
     */
    final function week_convert($num)
    {
        if (!$num) return false;
        switch ($num) {
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
    //编辑用户时间安排
    public function schedule_update()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id','kstime','weekday', 'jstime'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            if(!in_array($data['weekday'],range(1,7))){
                return diy_json('', -1, '参数有误');
            }
            $arrks = explode(":", $data['kstime']);
            $arrjs = explode(":", $data['jstime']);
            $data['time_start_h'] = $arrks['0'];
            $data['time_start_m'] = $arrks['1'];
            $data['time_end_h'] = $arrjs['0'];
            $data['time_end_m'] = $arrjs['1'];
            $where =['id'=>(int)$data['id'],'deleted'=>0];
            $sel = Db::name('teacher_freetime')->where($where)->find();
            if(!$sel){
                return diy_json('', -1, '教课时间安排信息不存在');
            }
            $data['createtime'] = time();
            unset($data['kstime']);
            unset($data['jstime']);
            unset($data['id']);
            $res = Db::name('teacher_freetime')->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $teacher_id = input('teacher_id');
        $where = ['id'=>(int)$teacher_id,'deleted'=>0];
        $teacher_name = Db::name('teacher')->where($where)->value('teacher_name');
        if(!$teacher_name){
            $this->error('教师信息不存在',url('ls'));
        }
        $id = input('id');
        $where =['id'=>(int)$id,'deleted'=>0];
        $sel = Db::name('teacher_freetime')->where($where)->find();
        if(!$sel){
            $this->error('教课时间安排信息不存在',url('ls'));
        }
        $sel['start'] =  $sel['time_start_h'].':'.$sel['time_start_m'].':00';
        $sel['end'] =  $sel['time_end_h'].':'.$sel['time_end_m'].':00';
        $this->assign('sel', $sel);
        $this->assign('teacher_name', $teacher_name);
        return $this->fetch();

    }


    //添加教师时间安排
    public function schedule_add()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $map = ['kstime','weekday', 'jstime', 'tid'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            if(!in_array($data['weekday'],range(1,7))){
                return diy_json('', -1, '参数有误');
            }
            $arrks = explode(":", $data['kstime']);
            $arrjs = explode(":", $data['jstime']);
            $data['time_start_h'] = $arrks['0'];
            $data['time_start_m'] = $arrks['1'];
            $data['time_end_h'] = $arrjs['0'];
            $data['time_end_m'] = $arrjs['1'];
            $data['createtime'] = time();
            unset($data['kstime']);
            unset($data['jstime']);
            $add = Db::name('teacher_freetime')->insert($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $teacher_id = input('teacher_id');
        $where = ['id'=>(int)$teacher_id,'deleted'=>0];
        $teacher_name = Db::name('teacher')->where($where)->value('teacher_name');
        if(!$teacher_name){
            $this->error('教师信息不存在',url('ls'));
        }
        $this->assign('teacher_name', $teacher_name);
        $this->assign('teacher_id', $teacher_id);
        return $this->fetch();

    }


    //删除教师时间安排
    public function schedule_del()
    {

        if (request()->isAjax()) {
            $id = input('id');
            $where =['id'=>(int)$id,'deleted'=>0];
            $sel = Db::name('teacher_freetime')->where($where)->find();
            if(!$sel){
                return diy_json('', -1, '教课时间安排信息不存在');
            }
            $update =['deleted'=>1];
            $res = Db::name('teacher_freetime')->where($where)->update($update);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }


    }














####################################################################################################
    //用户评论教师列表
    public function talk_teacher()
    {
        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $teacher_id = (int)input('teacher_id');
            $where = ['a.tid'=>$teacher_id,'a.deleted'=>0];
            $order = 'a.id desc';
            $sel = Db::name('teacher_evaluate')
                ->alias('a')
                ->join('teacher t', 'a.tid=t.id')
                ->join('user u', 'a.uid=u.id', 'left')
                ->field('a.*,t.teacher_name,u.nickname')
                ->where($where)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            foreach ($sel as &$v) {
                $v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);
                if ($v['status'] == 0) {
                    $v['status'] = "<span style='color:orange'>等待审核</span>";
                } elseif ($v['status'] == 1) {
                    $v['status'] = "<span style='color:green'>审核通过</span>";
                } elseif ($v['status'] == 2) {
                    $v['status'] = "<span style='color:red'>审核驳回</span>";
                }
            }
            $count = Db::name('teacher_evaluate')
                ->alias('a')
                ->join('teacher t', 'a.tid=t.id')
                ->join('user u', 'a.uid=u.id', 'left')
                ->field('a.*,t.teacher_name,u.nickname')
                ->where($where)
                ->order($order)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        $teacher_id = (int)input('teacher_id');
        $where = ['id'=>$teacher_id,'deleted'=>0];
        if(Db::name('teacher')->where($where)->count(1) == 0){
                $this->error('教师信息不存在',url('ls'));
        }
        $this->assign('teacher_id', $teacher_id);
        return $this->fetch();
    }


    //编辑用户评论
    public function talk_update()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id','content'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            $id = (int)$data['id'];
            $where = ['id'=>(int)$id,'deleted'=>0];
            $sel = Db::name('teacher_evaluate')->where($where)->find();
            if(!$sel){
                return diy_json('', -1, '评价信息不存在');
            }
            unset($item);
            unset($data['id']);
            if (!in_array($data['status'], [0,1,2])) {
                return diy_json('', -1, '参数有误');
            }
            if($data['status'] == 2 &&( !isset($data['reason']) || empty($data['reason']))){
                return diy_json('', -1, '缺少必要参数');
            }
            $res = Db::name('teacher_evaluate')->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $id = input('get.id');
        $where = ['id'=>(int)$id,'deleted'=>0];
        $sel = Db::name('teacher_evaluate')->where($where)->find();
        if(!$sel){
            $this->error('评价信息不存在',url('ls'));
        }
        $where = ['id'=>(int)$sel['uid']];
        $field = 'nickname,mobile';
        $user = Db::name('user')->field($field)->where($where)->find();
        if($user){
            $user['info'] = (empty($user['nickname'])?'匿名':$user['nickname']) . ' ' .(empty($user['mobile'])?'手机号未绑定':$user['mobile']);
            unset($user['nickname']);
            unset($user['mobile']);
        }
        $this->assign('user', $user);
        $this->assign('sel', $sel);
        return $this->fetch();

    }


//添加用户评论
    public function talk_add()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $data['createtime'] = time();
            $add = Db::name('teacher_evaluate')->insert($data);
            if ($add) {
                return json(['code' => 1, 'msg' => '添加评论成功']);
            }

        }

        $user = Db::name('user')->select();
        $teacher = Db::name('teacher')->select();
        $this->assign('user', $user);
        $this->assign('teacher', $teacher);
        return $this->fetch();

    }


    //删除会员评论
    public function talk_del()
    {

        if (request()->isAjax()) {
            $id = input('id');
            $where = ['id'=>(int)$id,'deleted'=>0];
            $sel = Db::name('teacher_evaluate')->where($where)->find();
            if(!$sel){
                return diy_json('', -1, '缺少必要参数');
            }
            $update =['deleted'=>1];
            $res = Db::name('teacher_evaluate')->where($where)->update($update);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }

    }








##################################################################################################################
    //上传图片路径
    public function move()
    {
        if (request()->isAjax()) {
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move('../public/uploads/image');
            if ($info) {
                // 成功上传后 获取上传信息
                $url = IMG_URL . '/uploads/image/' . $info->getSaveName();
                $url = str_replace("\\", "/", $url);
                return diy_json($url, 1, '操作成功');
            } else {
                // 上传失败获取错误信息
                //return $this->hrf_json(-1,);
                return diy_json('', -2, '获取错误信息' . $file->getError());
            }
        }
    }


}
