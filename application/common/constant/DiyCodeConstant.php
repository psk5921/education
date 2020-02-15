<?php

/**
 * 自定义错误类
 */
namespace app\common\constant;


class DiyCodeConstant
{
    //------------------------------公共错误码相关-----------------------------------------

    /**
     * 数据请求成功
     */
    const REQUEST_SUCCESS = [200,'数据请求成功'];

    /**
     * 数据请求失败
     */
    const REQUEST_FAILED = [-1,'数据请求失败'];

    /**
     * 请求方式有误
     */
   const REQUEST_METHOD_ERROR = [10000,'请求方式有误'];

    /**
     * 参数请求有误
     */
    const PARAM_ERROR = [10001,'参数请求有误'];

    /**
     * 未知错误
     */
    const UNKNOWN_ERROR = [10002,'未知错误'];


    /**
     * 其他错误
     */
    const OTHER_ERROR = [10003,'其他错误'];

    /**
     * 暂未查询到相关信息
     */
    const INFO_NOT_FOUND = [10004,'暂未查询到相关信息'];

    /**
     * 数据重复
     */
    const INFO_REPEAT = [10005,'数据重复'];

    /**
     * 验证错误
     */
    const VALIDATE_ERROR = [10006,'验证错误'];

    //------------------------------登录相关-----------------------------------------
    /**
     * 登录失败
     */
    const LOGIN_ERROR = [-2,'登录失败'];


    /**
     * 禁止登录
     */
    const LOGIN_DENY_ERROR = [20001,'禁止登录'];


    /**
     * 密码和用户名不匹配
     */
    const LOGIN_INFO_ERROR = [20002,'密码和用户名不匹配'];

    /**
     * 用户名不能为空
     */
    const LOGIN_USER_EMPTY_ERROR = [20003,'用户名不能为空'];

    /**
     * 密码不能为空
     */
    const LOGIN_PWD_EMPTY_ERROR = [20004,'密码不能为空'];

    /**
     * 用户信息不存在
     */
    const LOGIN_USER_NOT_FOUND_ERROR = [20005,'用户信息不存在'];

    /**
     * 用户信息失效
     */
    const LOGIN_USER_INFO_EXPIRED = [20006,'用户信息失效'];


    /**
     * 登录成功
     */
    const LOGIN_SUCCESS = [20000,'登录成功'];

    //------------------------------权限相关-----------------------------------------
    /**
     * 暂未分配权限
     */
    const PERM_NO = [0,'暂未分配权限'];

    //------------------------------角色相关-----------------------------------------
    /**
     * 角色不存在
     */
    const ROLE_NOT_EXIST = [-1,'角色不存在'];
}
