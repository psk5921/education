<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/25
 * Time: 12:22
 */

namespace app\http\middleware;

use think\Db;
use app\code\Api;
class Login
{
    /**
     *判断用户是否已经绑定手机号
     * @param $request
     * @param \Closure $next
     * @return false|mixed|string
     */
    public function handle($request, \Closure $next)
    {
        $openid = $request->param('openid');
        if(!$openid){
            return json(api_json_middleware(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]));
        }
        $where = ['openid'=>$openid];
         $is_login = Db::name('user')->where($where)->value('mobile');
        if(!$is_login){
            return json(api_json_middleware(Api::USER_NOT_LOGIN[0],Api::USER_NOT_LOGIN[1]));
        }
        return $next($request);
    }
}