<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/4
 * Time: 14:39
 */
namespace app\admin\validate;
use think\Validate;

class Good  extends Validate{

    //规则
    protected $rule =   [
        'price|商品价格'  =>  'number',
       


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