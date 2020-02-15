<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 14:42
 */

namespace app\api\validate\v1;


use think\Validate;

class User  extends  Validate
{
    //规则
    protected $rule = [
        'openid'  => 'require|max:50',
        'nickname'   => 'require|max:50',
        'mobile' => 'require|mobile|max:11',
        'avatar' => 'require|max:150',
        'gender' => 'require|number|between:0,2',
        'session_key' => 'require|max:150',
    ];

    //消息提示
    protected $message  =   [
        'openid.require' => 'openid必须',
        'openid.max'     => 'openid最多不能超过50个字符',
        'nickname.require' => '昵称必须',
        'nickname.max'     => '昵称最多不能超过50个字符',
        'mobile.require' => '手机号必须',
        'mobile.mobile' => '手机号格式不正确',
        'mobile.max'     => '手机号最多不能超过11个字符',
        'avatar.require' => '头像必须',
        'avatar.max'     => '头像最多不能超过150个字符',
        'gender.require' => '性别必须',
        'gender.number'     => '性别格式有误',
        'gender.between'     => '性别类型有误',
        'session_key.require' => 'session_key必须',
        'session_key.max'     => 'session_key最多不能超过150个字符',
    ];

    //场景
    protected $scene = [
        'insert'  =>  ['openid','nickname','mobile','avatar','gender','session_key'],
    ];
}