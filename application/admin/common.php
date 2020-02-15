<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/19
 * Time: 15:43
 */

// 其他对象 -> StdClass 对象
//new StdClass();
function convert_obj($val)
{
   // $name['nihao'] = 'fsdfds';
   // $name->nihao = 'fsdfds';

    return json_decode(json_encode($val));
}

function get_value($key , $value)
{
    $range = config($key);
    foreach ($range as $k => $v)
    {
        if ($k == $value) {
           return $v;
        }
    }
    return '';
}