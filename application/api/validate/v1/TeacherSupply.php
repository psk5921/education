<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/22
 * Time: 15:49
 */

namespace app\api\validate\v1;


use think\Validate;

class TeacherSupply  extends  Validate
{
//规则
    protected $rule = [
        'nationality'  => 'require|max:50',
        'work_type'  => 'require|between:1,2',
        'subject'  => 'require|max:180',
        'contact_way'  => 'require|between:1,2',
        'contact'  => 'require|max:180',
        'avaliable_time'  => 'require|between:1,3',
    ];

    //消息提示
    protected $message  =   [
        'nationality.require' => '国籍必须',
        'nationality.max'     => '国籍最多不能超过50个字符',
        'work_type.require' => '工作类型必须',
        'work_type.max'     => '工作类型选择有误',
        'subject.require' => '学科必须',
        'subject.max'     => '学科最多不能超过180个字符',
        'contact_way.require' => '联系方式必须',
        'contact_way.max'     => '联系方式选择有误',
        'contact.require' => '联系号码必须',
        'contact.max'     => '联系号码最多不能超过180个字符',
        'avaliable_time.require' => '上课时间必须',
        'avaliable_time.max'     => '上课时间选择有误',
    ];
}