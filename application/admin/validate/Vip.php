<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22
 * Time: 16:03
 */
namespace app\admin\validate;


use think\Validate;

class Vip  extends Validate{

    //规则
    protected $rule =   [
        'nickname'  => 'require|max:20',
        'credit' => 'number',
        'mobile' => 'number|length:11',

    ];
    //错误消息提示
    protected $message  =   [
        'nickname.require' => '会员昵称必须',
        'nickname.max'     => '会员昵称最多不能超过20个字符',
        'credit.number'  => '用户积分必须是数字、请重新输入',
        'mobile.number'  => '手机号码必须是数字、请重新输入',
        'mobile.length'  => '手机号码长度为11位、请重新输入',

    ];
    //场景
    protected $scene = [
        'common'  =>  ['name'], //公共验证
        'common'  =>  ['credit'], //公共验证
        'common'  =>  ['mobile'], //公共验证

    ];

















}