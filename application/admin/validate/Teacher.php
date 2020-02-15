<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/4
 * Time: 14:39
 */
namespace app\admin\validate;
use think\Validate;

class Teacher  extends Validate{

    //规则
    protected $rule =   [
        'zans'  =>  'number',
        'teacher_name' => 'require',
        'teacher_age|教师年龄' => 'number|between:1,80',

    ];
    //错误消息提示
    protected $message  =   [
        'teacher_name.require' => '教师昵称必须',
        'zans.number'     => '点赞数量必须是数字',
        'teacher_age.number'  => '教师年龄必须是数字、请重新输入',
        'teacher_age.max'  => '教师年级最大80岁、请重新输入',
    ];
    //场景
   /* protected $scene = [
        'common'  =>  ['teacher_name'], //公共验证
        'common'  =>  ['zans'], //公共验证
        'common'  =>  ['teacher_age'], //公共验证
    ];*/



}