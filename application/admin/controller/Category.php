<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 14:11
 */

namespace app\admin\controller;

use think\Db;
use app\admin\model\VipModel;
use app\admin\validate\Category as Categarycheck;

class Category extends Base
{
    //课程分类列表
    public function type_ls()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            $where = ['deleted'=>0];
           /* $start = input('start');
            $end = input('end');*/
            $search = array();
            if ($name) {
                $search[] = array('edu_category_name|edu_category_en_name', 'like', "%".$name."%");
            }
            /*if ($start) {
                $start = strtotime($start);
                $where[] = ['createtime', '>', $start];
            }
            if ($end) {
                $end = strtotime($end);
                $where[] = ['createtime', '<', $end];
            }*/
            $order = 'displayorder desc,id desc';
            $sel = Db::name('course_category')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            foreach ($sel as $key => &$v) {
               // $v['createtime'] = date('Y-m-d h:i:s', $v['createtime']);
                if ($v['status'] == 0) {
                    $v['status'] = "<span style='color:green'>正常</span>";
                } elseif ($v['status'] == 1) {
                    $v['status'] = "<span style='color:red'>禁用</span>";
                }
            }
            $count = Db::name('course_category')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        return $this->fetch();
    }





#########################################################################################
    //课程列表
    public function ls()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name');

           /* $start = input('start');
            $end = input('end');*/
            $where = ['a.deleted'=>0];
            $search = array();

            if ($name) {
                $search[] = array('a.course_title', 'like', "%".$name."%");
            }
           /* if ($start) {
                $start = strtotime($start);
                $where[] = ['createtime', '>', $start];
            }
            if ($end) {
                $end = strtotime($end);
                $where[] = ['createtime', '<', $end];
            }*/
            $order = 'a.displayorder desc,a.id desc';
            $sel = Db::name('course')
                ->alias('a')
                ->join('course_category c', 'a.course_id = c.id')
                ->join('teacher t', 'a.teacher_id = t.id', 'left')
                ->field('a.*,c.edu_category_name,t.teacher_name')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if($sel){
                foreach ($sel as $key => &$v) {
                    if ($v['course_img'] == '') {
                        $v['course_img'] = "暂无封面";
                    }
                    if ($v['status'] == 0) {
                        $v['status'] = "<span style='color:orangered'>等待上架</span>";
                    } elseif ($v['status'] == 1) {
                        $v['status'] = "<span style='color:green'>上架</span>";
                    } elseif ($v['status'] == 2) {
                        $v['status'] = "<span style='color:red'>下架</span>";
                    }
                }
            }
            $count =  Db::name('course')
                ->alias('a')
                ->join('course_category c', 'a.course_id = c.id')
                ->join('teacher t', 'a.teacher_id = t.id', 'left')
                ->field('a.*,c.edu_category_name,t.teacher_name')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);

        }

        return $this->fetch();
    }


    //编辑课程列表
    public function course_update()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id','course_id','course_title','course_short','course_img','course_thumb','teacher_id','course_description','course_class','agreement'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $id = (int)$data['id'];
            $where = ['id'=>$id,'deleted'=>0];
            $sel = Db::name('course')->where($where)->find($id);
            if(!$sel){
                return diy_json('',-1,'课程信息不存在');
            }
            $data['course_thumb'] = serialize(explode('|',trim($data['course_thumb'],'|')));
            $data['course_img'] = trim($data['course_img'],'|');
            $data['agreement'] = trim($data['agreement'],'|');
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['evaluate_total'] = !isset($data['evaluate_total']) ? 0 : (int)$data['evaluate_total'];
            $data['course_total'] = !isset($data['course_total']) ? 0 : (int)$data['course_total'];
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            $data['createtime'] = time();
            unset($data['file']);
            unset($data['id']);
            $update = Db::name('course')->where($where)->update($data);
            if ($update) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }

        }
        $id = (int)input('id');
        $where = ['id'=>$id,'deleted'=>0];
        $sel = Db::name('course')->where($where)->find($id);
        if(!$sel){
            $this->error('课程信息不存在',url('ls'));
        }
        if($sel){
            $sel['course_thumb'] = unserialize(mb_unserialize( $sel['course_thumb']));
            $sel['course_thumbs'] = implode('|',$sel['course_thumb']);
        }
        $where = ['status'=>0,'deleted'=>0];
        $order = 'displayorder desc,id desc';
        $types = Db::name('course_category')->where($where)->order($order)->select();
        $where = ['status'=>0,'deleted'=>0];
        $order = 'id desc';
        $teacher = Db::name('teacher')->where($where)->order($order)->select();
        $this->assign('types', $types);
        $this->assign('teacher', $teacher);
        $this->assign('sel', $sel);
        return $this->fetch();

    }


    //添加课程列表
    public function course_add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['course_id','course_title','course_short','course_img','course_thumb','teacher_id','course_description','course_class','agreement'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['course_thumb'] = serialize(explode('|',trim($data['course_thumb'],'|')));
            $data['course_img'] = trim($data['course_img'],'|');
            $data['agreement'] = trim($data['agreement'],'|');
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['evaluate_total'] = !isset($data['evaluate_total']) ? 0 : (int)$data['evaluate_total'];
            $data['course_total'] = !isset($data['course_total']) ? 0 : (int)$data['course_total'];
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            $data['createtime'] = time();
            unset($data['file']);
            $add = Db::name('course')->insert($data);
            if ($add) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $where = ['status'=>0,'deleted'=>0];
        $order = 'displayorder desc,id desc';
        $types = Db::name('course_category')->where($where)->order($order)->select();
        $where = ['status'=>0,'deleted'=>0];
        $order = 'id desc';
        $teacher = Db::name('teacher')->where($where)->order($order)->select();
        $this->assign('types', $types);
        $this->assign('teacher', $teacher);
        return $this->fetch();


    }


//删除课程
    public function course_delete()
    {
        if (request()->isAjax()) {
            $id = (int)input('id');
            $where = ['id'=>$id,'deleted'=>0];
            $sel = Db::name('course')->where($where)->find($id);
            if(!$sel){
                return diy_json('',-1,'课程信息不存在');
            }
            $update = ['deleted'=>1];
            $del = Db::name('course')->where($where)->update($update);
            if ($del) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
    }



    //课程图片内容上传
    public function uploadCourse(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'course/layedit');
            if($info){
                $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/course/layedit/'.$info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' =>[
                        'src' => $image_name,
                        'title' => '',
                    ]
                ];
                return  json($json);
            }else{
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' =>[
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return  json($json);
            }
        }
    }


    //课程图片单图上传
    public function simple(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'course/simple');
            if($info){
                $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/course/simple/'.$info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' =>[
                        'src' => $image_name,
                        'title' => '',
                    ]
                ];
                return  json($json);
            }else{
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' =>[
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return  json($json);
            }
        }
    }

    //课程协议上传
    public function xy(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'course/agreement');
            if($info){
                $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/course/agreement/'.$info->getSaveName();
                $json = [
                    'code' => 0,
                    'msg' => '',
                    'data' =>[
                        'src' => $image_name,
                        'title' => '',
                    ]
                ];
                return  json($json);
            }else{
                $json = [
                    'code' => -1,
                    'msg' => $file->getError(),
                    'data' =>[
                        'src' => '',
                        'title' => '',
                    ]
                ];
                return  json($json);
            }
        }
    }
    //课程图片多图上传
    public function multiple(){
        if(request()->isPost()){
            // 获取表单上传文件 例如上传了001.jpg
            $files = request()->file('file');
            $success = [];
            foreach($files as $file){
                // 移动到框架应用根目录/uploads/ 目录下
                $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,jpeg'])->rule('uniqid')->move( 'course/multiple');
                if($info){
                    $image_name = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']. '/course/multiple/'.$info->getSaveName();
                    $success[] = $image_name;
                }
            }
            $json = [
                'code' => 0,
                'msg' => '',
                'data' =>[
                    'src' => empty($success)?'':$success,
                    'title' => '',
                ]
            ];
            return  json($json);
        }
    }


#######################################################################################

    /*  //课程对应的老师
      public function teacher_ls(){
          if(request()->isGet()){
              $limit = input("limit");
              $page = input('page');
              $id = 1 ;
              $sel = Db::name('teacher')
                  ->where(['id'=>$id])
                  ->find();

              $count = Db::name('teacher')->count();
              return layui_json(0, '请求成功', $count, $sel);

          }
          return $this->fetch();

      }*/


##############################################################


    //课程评论列表
    public function discuss()
    {
        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $start = input('start');
            $name = RemoveXSS(input('name'));
            $end = input('end');
            $where = ['a.deleted' => 0];
            $search = [];
            if ($name) {
                $search[] = ['u.nickname', 'like', "%" . $name . "%"];
            }
            if ($start) {
                $start = strtotime($start);
                $search[] = ['a.createtime', '>', $start];
            }
            if ($end) {
                $end = strtotime($end);
                $search[] = ['a.createtime', '<', $end];
            }
            $order = 'a.id desc';
            $sel = Db::name('course_evaluate')
                ->alias('a')
                ->join('course c', 'a.cid = c.id')
                ->join('user u', 'a.uid = u.id', 'left')
                ->field('a.*,c.course_title,u.nickname')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            if ($sel) {
                foreach ($sel as $key => &$v) {
                    $v['createtime'] = date('Y-m-d h:i:s', $v['createtime']);

                    if ($v['status'] == 0) {
                        $v['status'] = "<span style='color: orange'>等待审核</span>";
                    } elseif ($v['status'] == 1) {
                        $v['status'] = "<span style='color: green'>审核通过</span>";
                    } elseif ($v['status'] == 2) {
                        $v['status'] = "<span style='color: red'>审核驳回</span>";
                    }
                }
                unset($v);
            }

            $count = Db::name('course_evaluate')
                ->alias('a')
                ->join('course c', 'a.cid = c.id')
                ->join('user u', 'a.uid = u.id', 'left')
                ->field('a.*,c.course_title,u.nickname')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        return $this->fetch();
    }


    //修改课程评论
    public function discuss_update()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $id = (int)$data['id'];
            $where = ['id'=>$id,'deleted'=>0];
            if(Db::name('course_evaluate')->where($where)->count(1) == 0 ){
                return diy_json('',-1,'评价信息不存在');
            }
            $map = ['id','cid','uid','content'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            unset($data['id']);
            unset($data['uid']);
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            if($data['status'] == 2 && (!isset($data['reason']) || empty($data['reason']))){
                return diy_json('',-1,'请输入驳回原因');
            }
            $update = Db::name('course_evaluate')->where($where)->update($data);
            if($update){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }

        }
        $id = (int)input('id');
        $where = ['id'=>$id,'deleted'=>0];
        $sel = Db::name('course_evaluate')->where($where)->find();
        if(!$sel){
            $this->error('评价信息不存在',url('discuss'));
        }
        $user_field = 'id,nickname,mobile';
        $user_where = ['id'=>$sel['uid']];
        $user = Db::name('user')->field($user_field)->where($user_where)->find();
        if($user){
            $user['info'] = (empty($user['nickname'])?'匿名':$user['nickname']) . ' ' .(empty($user['mobile'])?'手机号未绑定':$user['mobile']);
            unset($user['nickname']);
            unset($user['mobile']);
        }
        //获取目前有效的课程
        $where = ['status' => 1, 'deleted' => 0];
        $field = 'id,course_title';
        $course = Db::name('course')->where($where)->field($field)->select();
        $this->assign('course', $course);
        $this->assign('user', $user);
        $this->assign('sel', $sel);
        return $this->fetch();

    }


    //添加课程评论
    public function discuss_add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['cid','uid','content'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            if(!in_array($data['status'],[0,1,2])){
                return diy_json('',-1,'参数有误');
            }
            if($data['status'] == 2 && (!isset($data['reason']) || empty($data['reason']))){
                return diy_json('',-1,'请输入驳回原因');
            }
            $data['createtime'] = time();
            $add = Db::name('course_evaluate')->insert($data);
            //增加课程的评价总次数
            Db::name('education_course')->where(['id'=>$data['cid']])->setInc('evaluate_total',1);//增加课程总评价次数
            if($add){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $user_field = 'id,nickname,mobile';
        $user = Db::name('user')->field($user_field)->select();
        if($user){
            foreach ($user as &$item){
                $item['info'] = (empty($item['nickname'])?'匿名':$item['nickname']) . ' ' .(empty($item['mobile'])?'手机号未绑定':$item['mobile']);
                unset($item['nickname']);
                unset($item['mobile']);
            }
            unset($item);
        }
        //获取目前有效的课程
        $where = ['status' => 1, 'deleted' => 0];
        $field = 'id,course_title';
        $course = Db::name('course')->where($where)->field($field)->select();
        $this->assign('course', $course);
        $this->assign('user', $user);
        return $this->fetch();

    }


//删除课程评论
    public function discuss_delete()
    {

        if (request()->isAjax()) {
            $id = (int)input('id');
            $where = ['id' => $id, 'deleted' => 0];
            if (($re = Db::name('course_evaluate')->where($where)->find()) == false) {
                return diy_json('', -1, '评价信息不存在');
            }
            $update = ['deleted' => 1];
            $res = Db::name('course_evaluate')->where($where)->update($update);
            $evaluate_total =  Db::name('education_course')->where(['id'=>$re['cid']])->value('evaluate_total');
            $update =['evaluate_total'=>(($evaluate_total-1)>0?($evaluate_total-1):0)];
            Db::name('education_course')->where(['id'=>$re['cid']])->update($update);// 减少课程总评价次数
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }


    }


###################################################################


    //添加课程分类
    public function add_type()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['edu_category_en_name','edu_category_name'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            if(!in_array($data['status'],[0,1])){
                return diy_json('',-1,'参数有误');
            }
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['createtime'] = time();
            $add = Db::name('course_category')->insert($data);
            if ($add) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        return $this->fetch();
    }


    //编辑课程分类
    public function update()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id','edu_category_en_name','edu_category_name'];
            foreach ($map as $item){
                if(!isset($data[$item]) || empty($data[$item]) ){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            $id = (int)input('id');
            $where = ['id'=>$id,'deleted'=>0];
            if(Db::name('course_category')->where($where)->count(1) ==0 ){
                return diy_json('',-1,'课程信息不存在');
            }
            unset($item);
            unset($data['id']);
            if(!in_array($data['status'],[0,1])){
                return diy_json('',-1,'参数有误');
            }
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['updatetime'] = time();
            $res= Db::name('course_category')->where($where)->update($data);
            if ($res) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $id = (int)input('id');
        $where = ['id'=>$id,'deleted'=>0];
        $sel = Db::name('course_category')
            ->where($where)
            ->find();
        if(!$sel){
            $this->error('课程信息不存在',url('type_ls'));
        }
        $this->assign('sel', $sel);
        return $this->fetch();

    }

    //删除课程分类
    public function delete()
    {
        if(request()->isPost()){
            $id = (int)input('id');
            $where = ['id'=>$id,'deleted'=>0];
            if(Db::name('course_category')->where($where)->count(1) == 0 ){
                return diy_json('',-1,'分类信息不存在');
            }
        }
        $update = ['deleted'=>1];
        $del = Db::name('course_category')->where($where)->update($update);
        if ($del) {
            return diy_json('',1,'操作成功');
        }else{
            return diy_json('',-1,'操作失败,请重新尝试');
        }

    }



    ####################################################################################
    //课程评论
    public function course_review()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $id = input('id');
            $sel = Db::name('course_evaluate')
                ->alias('a')
                ->join('user u', 'a.uid=u.id', 'left')
                ->field('a.*,u.nickname')
                ->where(['a.cid' => $id])
                ->limit(($page - 1) * $limit, $limit)
                ->select();
            foreach ($sel as &$v) {
                $v['createtime'] = date('Y-m-d h:i:s', $v['createtime']);

                if ($v['status'] == 0) {
                    $v['status'] = '等待审核';
                } else if ($v['status'] == 1) {
                    $v['status'] = '审核通过';
                } else if ($v['status'] == 2) {
                    $v['status'] = '审核驳回';
                }
            }
            $count = db('course_class_package')->count();
            return layui_json(0, '请求成功', $count, $sel);


        }

        $id = input('id');
        $this->assign('id', $id);
        return $this->fetch();

    }





###################################################################################

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