<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Env;
use app\api\controller\ChatServer;

//$chat = new ChatServer();
// +----------------------------------------------------------------------
// | Swoole设置 php think swoole:server 命令行下有效
// +----------------------------------------------------------------------
return [
    // 扩展自身配置
    'host'         => '0.0.0.0', // 监听地址
    'port'         => 9508, // 监听端口
    'type'         => 'socket', // 服务类型 支持 socket http server
    'mode'         => '', // 运行模式 默认为SWOOLE_PROCESS
    'sock_type'    => '', // sock type 默认为SWOOLE_SOCK_TCP
    'swoole_class' => '', // 自定义服务类名称

    // 可以支持swoole的所有配置参数
    'daemonize'    => true,
    'pid_file'     => Env::get('runtime_path') . 'swoole_server.pid',
    'log_file'     => Env::get('runtime_path') . 'swoole_server.log',

    /*// 事件回调定义
    'onOpen'       => function ($server, $request) use ($chat) {
        //echo "服务端: 进入的用户标识 => {$request->fd}\n";
         $chat->onOpen($server,$request);
    },

    'onMessage' => function ($server, $frame) use ($chat) {
        $dataCode = json_decode($frame->data,true);
        $flag = $dataCode['flag'];
        if ($flag == 1){
            $chat->onMessage1($server,$frame);
        }else{
            $chat->onMessage2($server,$frame);
        }

    },

    'onRequest' => function ($request, $response) {
        $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
    },

    'onClose' => function ($ser, $fd) use ($chat) {
        $chat->onClose($ser,$fd);
    },*/
];
