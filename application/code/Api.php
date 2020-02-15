<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/20
 * Time: 11:13
 */

namespace app\code;
/**
 * API 全局错误码定义
 * Class Api
 * @package app\code
 */
class Api
{
    /**
     * 用户相关错误码
     */
    const   USER_NOT_LOGIN = [1000, '请先登录'];


    /**
     * API 请求相关错误码
     */
    const   OPENID_ERROR = [2001, '缺少参数Openid或Openid不存在'];


    /**
     * API公共请求
     */
    const   REQUEST_SUCCESS = [1, '请求成功'];

    const   PARAM_ERROR = [2, '参数错误'];

    const   REQUEST_FAIL = [0, '请求失败'];

    const   SYSTEM_ERROR = [-1, '系统错误'];


}