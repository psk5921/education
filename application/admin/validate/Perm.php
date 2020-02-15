<?php

/**
 * 权限验证
 */
namespace app\admin\validate;


use think\Validate;

class Perm  extends Validate
{
    //规则
    protected $rule =   [
        'name'  => 'require|max:20',
        'controller'  => 'require|max:50',
        'action'  => 'require|max:50',
    ];
    //错误消息提示
    protected $message  =   [
        'name.require' => '菜单名称必须',
        'name.max'     => '菜单名称最多不能超过20个字符',
        'controller.require' => '控制器名称必须',
        'controller.max'     => '控制器名称最多不能超过50个字符',
        'action.require' => '方法名称必须',
        'action.max'     => '方法名称最多不能超过50个字符',
    ];
    //场景
    protected $scene = [
        'common'  =>  ['name','controller','action'], //公共验证
    ];
}