<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/12/4
 * Time: 10:41
 */

namespace app\admin\controller;

use think\Db;
use think\Exception;

class Message
{
    final function convert_week($str)
    {
        if (empty($str)) {
            return '';
        }
        $search = ["周一", "周二", "周三", "周四", "周五", "周六", "周天", "星期天"];
        $replace = ['mon', "tue", 'wed', 'thu', 'fri', 'sat', 'sun', 'sun'];
        $new_str = str_replace($search, $replace, $str);
        return $new_str;
    }

    //发送家长提醒
    public function sendParentsRemind()
    {
        //遍历符合条件的课程信息添加消息
        $time_where = ['deleted' => 0, 'status' => 1];
        $time_list = Db::name('course_class_time')->where($time_where)->field('id,time')->select();
        $hour = 1;
        if ($time_list) {
            foreach ($time_list as $item) {
                $time = explode('-', $item['time']);
                if (count($time) < 2) {
                    continue;
                } else {
                    if ($date = strtotime($this->convert_week($time[0]))) {
                        if (strtotime('today') < $date && ($date - $hour * 60 * 60) > time() && $date < strtotime("tomorrow")) {
                            //符合今天上课条件的时间id
                            $where = [['time_id', '=', $item['id']], ['is_underline', '=', 0], ['count', '>', 0], ['status', '=', 0]];
                            $list = Db::name('user_course')->where($where)->select();
                            if ($list) {
                                try {
                                    foreach ($list as $v) {
                                        //todo 写入消息记录
                                        $user = Db::name('user')->where(['id' => $v['uid']])->field('openid,mobile')->find();
                                        $title = Db::name('course')->where(['id' => $v['course_id']])->value('course_title');
                                        write_msg($user['openid'], '上课提醒', "尊敬的{$user['mobile']}会员，您预订的{$title}课程将在" . date("Y年m月d日H时i分", $date) . "开始上课，请做好准备，谢谢!");
                                    }
                                } catch (Exception $e) {
                                    echo $e->getMessage();
                                }
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }
            }
        }


    }

    //发送老师提醒
    public function sendTeachersRemind()
    {
        //遍历符合条件的课程信息添加消息
        $time_where = ['deleted' => 0, 'status' => 1];
        $time_list = Db::name('course_class_time')->where($time_where)->field('id,time,class_id')->select();
        $hour = 1;
        if ($time_list) {
            foreach ($time_list as $item) {
                $time = explode('-', $item['time']);
                if (count($time) < 2) {
                    continue;
                } else {
                    if ($date = strtotime($this->convert_week($time[0]))) {
                        if (strtotime('today') < $date && ($date - $hour * 60 * 60) > time() && $date < strtotime("tomorrow")) {
                            try {
                                //符合今天上课条件的时间id
                                $where = [['id', '=', $item['class_id']], ['deleted', '=', 0], ['status', '=', 1]];
                                //找classid  然后再找courseid
                                $course_id = Db::name('course_class')->where($where)->value('cid');
                                $course_where = [['id', '=', $course_id], ['deleted', '=', 0], ['status', '=', 1]];
                                $course = Db::name('course')->where($course_where)->field('teacher_id')->find();
                                $teacher = Db::name('teacher')->where(['id' => $course['teacher_id'],'status' => 0,'deleted' => 0])->field('uid,teacher_name')->find();
                                $openid = Db::name('user')->where(['id' => $teacher['uid']])->value('openid');
                                $user_course_where = [['time_id', '=', $item['id']], ['is_underline', '=', 1], ['count', '>', 0], ['status', '=', 0]];
                                $list = Db::name('user_course')->where($user_course_where)->select();
                                if ($list) {
                                    foreach ($list as $k) {
                                        write_msg($openid, '上课提醒', "{$teacher['teacher_name']}老师，请务必在" . date("Y年m月d日H时i分", $date) . "到达指定地址{$k['class_address']} 开始上课");
                                    }

                                }else{
                                    continue;
                                }
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }
            }
        }

    }

}