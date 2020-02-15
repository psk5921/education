<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/4
 * Time: 14:39
 */
namespace app\admin\validate;
use think\Validate;

class Package  extends Validate{

    //规则
    protected $rule =   [
        'package_time|套餐可使用多少次数'  =>  'number',
        'package_month|套餐可使用月数' => 'number',
        'package_price|套餐价格' => 'number',


    ];
    //错误消息提示
    protected $message  =   [

    ];
    //场景
    /* protected $scene = [
         'common'  =>  ['teacher_name'], //公共验证
         'common'  =>  ['zans'], //公共验证
         'common'  =>  ['teacher_age'], //公共验证
     ];*/



}