<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22
 * Time: 23:12
 */

namespace app\api\validate\v1;


use think\Validate;

class CourseAppointment  extends  Validate
{
//规则
    protected $rule = [
        'hope_time'  => 'require|max:50',
        'mobile'  => 'require|mobile|max:11',
        'baby_sex'  => 'require|between:1,2',
        'baby_name'  => 'require|max:50',
        'baby_en_name'  => 'require|max:50',
        'baby_school'  => 'max:50',
        'address'  => 'max:150',
        'remarks'  => 'max:500',
    ];

    //消息提示
    protected $message  =   [
        'hope_time.require' => '希望时间必须',
        'hope_time.max'     => '希望时间最多不能超过50个字符',
        'mobile.require' => '手机号必须',
        'mobile.mobile'     => '手机号格式不正确',
        'mobile.max' => '手机号最多不能超过11个字符',
        'baby_sex.require'     => '宝宝性别必须',
        'baby_sex.between'     => '宝宝性别类型选择有误',
        'baby_name.require'     => '宝宝姓名必须',
        'baby_name.max'     => '宝宝性别类型选择有误',
        'baby_en_name.require'     => '宝宝英文名称必须',
        'baby_en_name.max'     => '宝宝英文名称最多不能超过50个字符',
        'baby_school.max'     => '宝宝学校最多不能超过50个字符',
        'address.max'     => '家庭住址最多不能超过150个字符',
        'remarks.max'     => '备注最多不能超过500个字符',
    ];
}