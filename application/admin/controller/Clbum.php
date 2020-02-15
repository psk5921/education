<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 16:08
 */

namespace app\admin\controller;

use think\Db;

class Clbum extends Base
{

    //班级列表
    public function ls()
    {

        if (request()->isAjax()) {

            $limit = input("limit");
            $page = input('page');
            $name = input('name');

            /*  $start = input('start');

              $end = input('end');*/
            $where = ['a.deleted' => 0];
            if ($name) {
                $where[] = array('a.class_name', 'like', "%" . RemoveXSS($name) . "%");
            }
            /* if ($start){
                 $start = strtotime($start);
                 $where[] =['a.createtime','>',$start];
             }
             if ($end){
                 $end = strtotime($end);
                 $where[] =['a.createtime','<',$end];
             }*/
            $order = "a.displayorder desc,a.id desc";
            $sel = Db::name('course_class')
                ->alias('a')
                ->join('course c', 'a.cid = c.id', 'left')
                ->field('a.*,c.course_title')
                ->where($where)
                ->order($order)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->select();
            foreach ($sel as $key => &$v) {
                $v['createtime'] = date('Y-m-d H:i:s', $v['createtime']);

                if ($v['status'] == 0) {
                    $v['status'] = "<span style='color:orange'>等待上架</span>";
                } elseif ($v['status'] == 1) {
                    $v['status'] = "<span style='color:green'>上架</span>";
                } elseif ($v['status'] == 2) {
                    $v['status'] = "<span style='color:red'>下架</span>";
                }


            }


            $count = Db::name('course_class')
                ->alias('a')
                ->join('course c', 'a.cid = c.id', 'left')
                ->field('a.*,c.course_title')
                ->where($where)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }


        return $this->fetch();
    }


    //添加班级
    public function add_class()
    {

        if (request()->isPost()) {

            $data = input('post.');
            $map = ['class_name', 'cid'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            if (!in_array($data['status'], [0, 1, 2])) {
                return diy_json('', -1, '参数有误');
            }
            $data['createtime'] = time();
            $add = Db::name('course_class')->insert($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $where = ['deleted' => 0, 'status' => 1];
        $field = 'id,course_title';
        $coure = Db::name('course')->where($where)->field($field)->select();
        $this->assign('couse', $coure);
        return $this->fetch();

    }


    //修改班级
    public function update()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $id = $data['id'];
            $where = ['deleted' => 0, 'id' => $id];
            if (Db::name('course_class')->where($where)->count() == 0) {
                return diy_json('', -1, '班级信息有误');
            }
            $map = ['class_name', 'cid'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            if (!in_array($data['status'], [0, 1, 2])) {
                return diy_json('', -1, '参数有误');
            }
            $data['createtime'] = time();
            $add = Db::name('course_class')->where($where)->update($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $id = input('id');
        $sel = Db::name('course_class')
            ->alias('a')
            ->join('course c', 'a.cid=c.id', 'left')
            ->field('a.*,c.course_title')
            ->where(['a.id' => $id])
            ->find();
        $where = ['deleted' => 0, 'status' => 1];
        $field = 'id,course_title';
        $coure = Db::name('course')->where($where)->field($field)->select();
        $this->assign('kecheng', $coure);
        $this->assign('sel', $sel);
        return $this->fetch();
    }


    //删除班级
    public function delete()
    {
        if (request()->isAjax()) {
            $id = input('id');
            $where = ['deleted' => 0, 'id' => $id];
            if (Db::name('course_class')->where($where)->count() == 0) {
                return diy_json('', -1, '班级信息有误');
            }
            $update = ['deleted' => 1];
            $res = Db::name('course_class')->where($where)->update($update);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }


    }


#################################################################################################
    //班级关联的套餐列表
    public function package_list()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            $id = input('class_id');
            $where = ['class_id' => $id, 'deleted' => 0];
            $search = [];
            if ($name) {
                $search[] = ['package_name', 'like', '%' . RemoveXSS($name) . '%'];
            }
            $order = 'displayorder desc,id desc';
            $sel = Db::name('course_class_package')
                ->where($where)
                ->where($search)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->order($order)
                ->select();
            foreach ($sel as &$v) {
                $v['createtime'] = date('Y-m-d h:i:s', $v['createtime']);
                $v['package_month'] = $v['package_month'] . '个月';
                $v['package_time'] = $v['package_time'] . '次';
                if ($v['status'] == 0) {
                    $v['status'] = "<span style='color:orange'>等待上架</span>";
                } else if ($v['status'] == 1) {
                    $v['status'] = "<span style='color:green'>上架</span>";
                } else if ($v['status'] == 2) {
                    $v['status'] = "<span style='color:red'>下架</span>";
                }
            }
            $count = Db::name('course_class_package')
                ->where($where)
                ->where($search)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);
        }
        $class_id = input('class_id');
        $where = ['id' => $class_id, 'deleted' => 0];
        if (Db::name('course_class')->where($where)->count(1) == 0) {
            $this->error('班级信息不存在', url('ls'));
        }
        $this->assign('class_id', $class_id);
        return $this->fetch();


    }


    //编辑课程对应套餐
    public function package_update()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id', 'class_id', 'package_name', 'package_time', 'package_month', 'package_price',];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }

            $where = ['id' => $data['class_id'], 'deleted' => 0];
            if (Db::name('course_class')->where($where)->count(1) == 0) {
                return diy_json('', -1, '班级信息不存在');
            }
            $where = ['id' => $data['id'], 'deleted' => 0];
            if (Db::name('course_class_package')->where($where)->count(1) == 0) {
                $this->error('套餐信息不存在', url('ls'));
            }
            unset($item);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['package_time'] = (int)$data['package_time'];
            $data['package_month'] = (int)$data['package_month'];
            $data['package_price'] = floatval($data['package_price']);
            if (!in_array($data['status'], [0, 1, 2])) {
                return diy_json('', -1, '参数有误');
            }
            $data['updatetime'] = time();
            $add = Db::name('course_class_package')->where($where)->update($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $class_id = input('class_id');
        $where = ['id' => $class_id, 'deleted' => 0];
        if (Db::name('course_class')->where($where)->count(1) == 0) {
            $this->error('班级信息不存在', url('ls'));
        }
        //$class = Db::name('course_class')->select();
        $this->assign('class_id', $class_id);
        $id = input('id');
        $where = ['id' => $id, 'deleted' => 0];
        if (Db::name('course_class_package')->where($where)->count(1) == 0) {
            $this->error('套餐信息不存在', url('ls'));
        }
        $sel = Db::name('course_class_package')->where($where)->find();
        $this->assign('sel', $sel);
        $this->assign('id', $id);
        return $this->fetch();


    }


    //添加套餐
    public function package_add()
    {

        if (request()->isPost()) {
            $data = input('post.');
            $map = ['class_id', 'package_name', 'package_time', 'package_month', 'package_price',];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            $where = ['id' => $data['class_id'], 'deleted' => 0];
            if (Db::name('course_class')->where($where)->count(1) == 0) {
                return diy_json('', -1, '班级信息不存在');
            }
            unset($item);
            $data['displayorder'] = !isset($data['displayorder']) ? 0 : (int)$data['displayorder'];
            $data['package_time'] = (int)$data['package_time'];
            $data['package_month'] = (int)$data['package_month'];
            $data['package_price'] = floatval($data['package_price']);
            if (!in_array($data['status'], [0, 1, 2])) {
                return diy_json('', -1, '参数有误');
            }
            $data['createtime'] = time();
            $add = Db::name('course_class_package')->insert($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $id = input('class_id');
        $where = ['id' => $id, 'deleted' => 0];
        if (Db::name('course_class')->where($where)->count(1) == 0) {
            $this->error('班级信息不存在', url('ls'));
        }
        //$class = Db::name('course_class')->select();
        $this->assign('class_id', $id);
        return $this->fetch();
    }


    //删除套餐成功
    public function package_delete()
    {

        if (request()->isAjax()) {
            $class_id = input('class_id');
            $where = ['id' => $class_id, 'deleted' => 0];
            if (Db::name('course_class')->where($where)->count(1) == 0) {
                return diy_json('', -1, '班级信息不存在');
            }
            $id = input('id');
            $where = ['id' => $id, 'deleted' => 0];
            if (Db::name('course_class_package')->where($where)->count(1) == 0) {
                return diy_json('', -1, '套餐信息不存在');
            }
            $update = ['deleted' => 1];
            $del = Db::name('course_class_package')->where($where)->update($update);
            if ($del) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }


    }


########################################################################################################
    //班级对应时间
    public function class_time()
    {

        if (request()->isAjax()) {
            $limit = input("limit");
            $page = input('page');
            $class_id = input('class_id');
            $where = ['class_id' => $class_id, 'deleted' => 0];
            $order = 'displayorder desc,id desc';
            $sel = Db::name('course_class_time')
                ->where($where)
                ->limit(ceil($page - 1) * $limit, $limit)
                ->order($order)
                ->select();
            foreach ($sel as &$v) {
                if ($v['status'] == 0) {
                    $v['status'] = "<span style='color:orange'>等待上架</span>";
                } else if ($v['status'] == 1) {
                    $v['status'] = "<span style='color:green'>上架</span>";
                } else if ($v['status'] == 2) {
                    $v['status'] = "<span style='color:red'>下架</span>";
                }

            }
            $count = Db::name('course_class_time')
                ->where($where)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);

        }

        $class_id = input('class_id');
        $where = ['id' => $class_id, 'deleted' => 0];
        if (Db::name('course_class')->where($where)->count(1) == 0) {
            $this->error('班级信息不存在', url('ls'));
        }
        $this->assign('class_id', $class_id);
        return $this->fetch();

    }

    //编辑班级课程时间
    public function classtime_update()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['id','class_id', 'time'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['updatetime'] = time();
           /* $arrks = explode(":", $data['kstime']);
            $arrjs = explode(":", $data['jstime']);*/
            /*$data['time_start_h'] = $arrks['0'];
            $data['time_start_m'] = $arrks['1'];
            $data['time_end_h'] = $arrjs['0'];
            $data['time_end_m'] = $arrjs['1'];*/

            /*unset($data['kstime']);
            unset($data['jstime']);
            unset($data['jstime']);*/
            /*if($data['week_type'] == 1 && (!isset($data['week_start']) || !in_array((int)$data['week_start'],range(1,7)))){
                return diy_json('', -1, '缺少必要参数');
            }
            if($data['week_type'] == 2 && (!isset($data['week_start']) || !isset($data['week_end']) || !in_array((int)$data['week_start'],range(1,7)) || !in_array((int)$data['week_end'],range(1,7)) )){
                return diy_json('', -1, '缺少必要参数');
            }*/
            $where = ['id' => $data['class_id'], 'deleted' => 0];
            if (Db::name('course_class')->where($where)->count(1) == 0) {
                $this->error('班级信息不存在', url('ls'));
            }
            $id = (int)$data['id'];
            $where = ['id' => $id, 'deleted' => 0];
            if (Db::name('course_class_time')->where($where)->count(1) == 0) {
                return diy_json('', -1, '上课信息不存在');
            }
            unset($data['id']);
            $add = Db::name('course_class_time')->where($where)->update($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }

        }
        $class_id = (int)input('class_id');
        $where = ['id' => $class_id, 'deleted' => 0];
        if (Db::name('course_class')->where($where)->count(1) == 0) {
            return diy_json('', -1, '班级信息不存在');
        }
        $id = (int)input('id');
        $where = ['id' => $id, 'deleted' => 0];
        if (Db::name('course_class_time')->where($where)->count(1) == 0) {
            return diy_json('', -1, '上课信息不存在');
        }
        $sel = Db::name('course_class_time')->where($where)->find();
        /*if($sel){
            $sel['kstime'] = $sel['time_start_h'].':'.$sel['time_start_m'].":00";
            $sel['jstime'] = $sel['time_end_h'].':'.$sel['time_end_m'].":00";
        }*/
        $this->assign('sel', $sel);
        $this->assign('id', $id);
        $this->assign('class_id', $class_id);
        return $this->fetch();

    }


    //添加课程对应时间
    public function classtime_add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $map = ['class_id', 'time'];
            foreach ($map as $item) {
                if (!isset($data[$item]) || empty($data[$item])) {
                    return diy_json('', -1, '缺少必要参数');
                }
            }
            foreach ($data as &$item) {
                $item = RemoveXSS($item);
            }
            unset($item);
            $data['createtime'] = time();
           /* $arrks = explode(":", $data['kstime']);
            $arrjs = explode(":", $data['jstime']);
            $data['time_start_h'] = $arrks['0'];
            $data['time_start_m'] = $arrks['1'];
            $data['time_end_h'] = $arrjs['0'];
            $data['time_end_m'] = $arrjs['1'];
            unset($data['kstime']);
            unset($data['jstime']);
            if($data['week_type'] == 1 && (!isset($data['week_start']) || !in_array((int)$data['week_start'],range(1,7)))){
                return diy_json('', -1, '缺少必要参数');
            }
            if($data['week_type'] == 2 && (!isset($data['week_start']) || !isset($data['week_end']) || !in_array((int)$data['week_start'],range(1,7)) || !in_array((int)$data['week_end'],range(1,7)) )){
                return diy_json('', -1, '缺少必要参数');
            }
            $where = ['id' => $data['class_id'], 'deleted' => 0];
            if (Db::name('course_class')->where($where)->count(1) == 0) {
                $this->error('班级信息不存在', url('ls'));
            }*/
            $add = Db::name('course_class_time')->insert($data);
            if ($add) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }
        $class_id = input('class_id');
        $where = ['id' => $class_id, 'deleted' => 0];
        if (Db::name('course_class')->where($where)->count(1) == 0) {
            $this->error('班级信息不存在', url('ls'));
        }
        //$class = Db::name('course_class')->select();
        $this->assign('class_id', $class_id);
        return $this->fetch();

    }

    //删除课程对应时间
    public function classtime_del()
    {

        if (request()->isAjax()) {
            $class_id = (int)input('class_id');
            $where = ['id' => $class_id, 'deleted' => 0];
            if (Db::name('course_class')->where($where)->count(1) == 0) {
                return diy_json('', -1, '班级信息不存在');
            }
            $id = (int)input('id');
            $where = ['id' => $id, 'deleted' => 0];
            if (Db::name('course_class_time')->where($where)->count(1) == 0) {
                return diy_json('', -1, '上课信息不存在');
            }
            $update =['deleted'=>1];
            $del = Db::name('course_class_time')->where($where)->update($update);
            if ($del) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -1, '操作失败,请重新尝试');
            }
        }


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
}