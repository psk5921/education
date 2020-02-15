<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/22
 * Time: 15:08
 */

namespace app\api\validate\v1;


use think\Validate;

class Feedback  extends  Validate
{
//规则
    protected $rule = [
        'content'  => 'require|max:180',
    ];

    //消息提示
    protected $message  =   [
        'content.require' => '内容必须',
        'content.max'     => '内容最多不能超过180个字符',
    ];

}