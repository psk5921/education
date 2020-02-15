<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 11:03
 */

namespace app\api\validate\v1;


use think\Validate;

class UserDeliver  extends  Validate
{
//规则
    protected $rule = [
        'delivery_name'  => 'require|max:50',
        'delivery_mobile'  => 'require|mobile|max:11',
        'province_id'  => 'require',
        'city_id'  => 'require',
        'country_id'  => 'require',
        'delivery_area'  => 'require',
        'delivery_address'  => 'require|max:100',
    ];

    //消息提示
    protected $message  =   [
        'delivery_name.require' => '收货人必须',
        'delivery_name.max'     => '收货人最多不能超过50个字符',
        'delivery_mobile.require' => '手机号必须',
        'delivery_mobile.mobile'     => '手机号格式不正确',
        'delivery_mobile.max' => '手机号最多不能超过11个字符',
        'province_id.require'     => '省份必须',
        'city_id.require'     => '城市必须',
        'country_id.require'     => '区域必须',
        'delivery_area.require'     => '收货区域必须',
        'delivery_address.require'     => '收货人详细地址必须',
        'delivery_address.max'     => '收货人详细地址最多不能超过100个字符',
    ];
}