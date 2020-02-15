<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/22
 * Time: 14:08
 */

namespace app\api\validate\v1;


use think\Validate;

class UserHomeAddress  extends Validate
{
//规则
    protected $rule = [
        'province'  => 'require',
        'city'   => 'require',
        'country' => 'require',
        'area' => 'require|max:50',
        'address' => 'require|max:255',
    ];

    //消息提示
    protected $message  =   [
        'province.require' => '省份必须',
        'city.require' => '城市必须',
        'country.require'     => '地区最多不能超过50个字符',
        'area.require' => '所在地信息必须',
        'area.max'     => '所在地信息最多不能超过50个字符',
        'address.require' => '详细地址必须',
        'address.max'     => '详细地址最多不能超过255个字符',
    ];

    //场景
    protected $scene = [
        'insert'  =>  ['province','city','country','address'],
    ];
}