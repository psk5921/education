<?php


namespace app\common\exception;


use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use app\common\exception\DiyException;

class Http extends Handle
{
    public function render(Exception $e)
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json($e->getError(), 422);
        }

        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }

        //自定义错误
        if($e instanceof DiyException && request()->isAjax()){
            return json(diy_json('',$e->getError(), $e->getStatusCode()));
        }

        // 其他错误交给系统处理
        return parent::render($e);
    }

}