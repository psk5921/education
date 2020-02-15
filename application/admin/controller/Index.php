<?php

namespace app\admin\controller;

use app\admin\model\Perm as Perms;
use think\Controller;
use think\Db;
use think\session\driver\Redis;

class Index extends Base
{
    //后台首页
    public function index()
    {
        $Perms = new Perms();
        $field = 'id,name,pid,is_menu,controller,action,iconfont';
        $select = $Perms->getTrees($field, '', 1);
        if (!empty($select)) {
            foreach ($select as $child) {
                foreach ($child['child'] as &$childs) {
                    $childs['url'] = url($childs['controller'] . '/' . $childs['action']);
                }
                unset($childs);
            }
        }
        $this->assign([
            'username' => session('username', '', 'login'),
            'menu' => $select,
        ]);
        return $this->fetch();
    }


    //欢迎页
    public function welcome()
    {
        //halt(is_http());
        $this->assign([
            'now' => date('Y-m-d H:i:s'),
            'username' => session('username', '', 'login'),
            'login_count' => session('logincount', '', 'login') > 999 ? '999+' : session('logincount', '', 'login'), //总登录次数
            'login_ip' => session('loginip', '', 'login'), //登录ip
            'login_time' => date('Y-m-d H:i:s', session('logintime', '', 'login')),//最近一次登录时间
            'server_software' => request()->server()['SERVER_SOFTWARE'],//运行环境
            //'os' => request()->server()['OS'],//系统
            'server_name' => is_http() . request()->server()['HTTP_HOST'],//服务器地址
            'php_version' => PHP_VERSION,//PHP 版本号
            'sapi_name' => php_sapi_name(),//PHP 当前运行方式
            'mysql_version' => Db::query("SELECT VERSION() AS ver")[0]['ver'],//数据库版本
            'upload' => ini_get('upload_max_filesize'),//上传限制
            'max_execution_time' => ini_get('max_execution_time'),//最大执行时间
            'max_file_uploads' => ini_get('max_file_uploads'),//最大文件上传
            'memory_usage' => memory_usage(),//已使用内存
        ]);
        return $this->fetch();
    }

    //判断是否过期
    public function is_expire()
    {

        if (!session('expire', '', 'login') || session('expire', '', 'login') < time()) {
            //过期 判断请求是不是ajax请求 给与相关的响应
            return diy_json(['status' => 1], 1, '');
        } else {
            return diy_json(['status' => 0], 1, '');
        }
    }

    //首页的数据显示
    public function getData_floor1()
    {
        $total = (int)Db::name('user')->count(); //获取全部会员数量
        $this_month = strtotime(date('Y') . '-' . date('m') . '-01');
        $next_month = strtotime(' +1 month', $this_month);
        $current = (int)Db::name('user')->whereBetweenTime('createtime', $this_month, $next_month)->count(); //获取当月会员数量
        $fans = (int)Db::name('user')->where('identity', 0)->count(); //全部粉丝数量
        $pays = (int)Db::name('user')->where('identity', 1)->count(); //全部付费数量
        $course_order_pays = Db::name('course_order')->where('status', 1)->sum('price');
        $shop_order_pays = Db::name('shop_order')->where('status', 1)->sum('price');
        $total_pays = bcadd($course_order_pays, $shop_order_pays, 2);
        $current_course_order_pays = Db::name('course_order')->whereBetweenTime('pay_time', $this_month, $next_month)->where('status', 1)->sum('price');
        $current_shop_order_pays = Db::name('shop_order')->whereBetweenTime('paytime', $this_month, $next_month)->whereIn('status', '1,2,3')->sum('price');
        $current_pays = bcadd($current_course_order_pays, $current_shop_order_pays, 2);
        $data = [
            'total' => number_format($total),
            'current' => number_format($current),
            'fans' => number_format($fans),
            'pays' => number_format($pays),
            'total_pays' => number_format($total_pays),
            'current_pays' => number_format($current_pays),
        ];
        return diy_json($data, 1, '');
    }

    //首页的数据显示
    public function getData_floor2()
    {
        $data = [
            ['product', '商城订单(Shop Order)', '商城订单(Course Order)'],
        ];
        $month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        for ($i = 1; $i <= 12; $i++) {
            $time = $i < 10 ? '0' . $i : $i;
            $this_month = strtotime(date('Y') . '-' . $time . '-01');
            $next_month = strtotime(' +1 month', $this_month);
            $current_course_order_pays = Db::name('course_order')->whereBetweenTime('pay_time', $this_month, $next_month)->where('status', 1)->sum('price');
            $current_shop_order_pays = Db::name('shop_order')->whereBetweenTime('paytime', $this_month, $next_month)->whereIn('status', '1,2,3')->sum('price');
            $list = [$month[$i - 1], $current_shop_order_pays, $current_course_order_pays];
            array_push($data, $list);
        }
        return diy_json($data, 1, '');
    }

    //首页的数据显示
    public function getData_floor3()
    {
        $field1 = "sum(price) as total,course_id";
        $list1 = Db::name('course_order')->field($field1)->where('status', 1)->order('total desc')->limit(6)->select();
        $html1 = '<thead>
                        <tr>
                        <th>排名</th>
                        <th>商品名称</th>
                        <th>销售额</th>
                        </tr>
                        </thead>
                        <tbody id="html1" >';
        $html2 ="<thead>
                        <tr>
                        <th>排名</th>
                        <th>商品名称</th>
                        <th>销售额</th>
                        </tr>
                        </thead>
                        <tbody id=\"html2\" >";
        $field2 = ["sum(ShopOrderGoods.price * ShopOrderGoods.num)"=>'total'];
        $list2 = Db::view('ShopOrder', $field2)
            ->view('ShopOrderGoods', 'title,gid', 'ShopOrderGoods.oid=ShopOrder.id')
            ->whereIn('ShopOrder.status', '1,2,3')
            ->group('ShopOrderGoods.gid')
            ->orderRaw("total desc")
            ->limit(6)
            ->select();
        $color = ["#FF5722","#FFB800","#5FB878"];
        for ($i = 1; $i <= 6; $i++) {
            $c = $i<= 3 ?$color[$i-1]:"#666";
            if(!empty($list1[$i-1])){
                $title = Db::name('course')->where('id',$list1[$i-1]['course_id'])->value('course_title');
                $money = number_format($list1[$i-1]['total']);
                $html1 .= "<tr>
                        <td><i class=\"layui-icon \" style='color:{$c}!important;'>&#xe756;</i><span class=\"first\"> {$i}</span></td>
                        <td style='color:{$c}!important;'>{$title}</td>
                        <td ><span style='color:{$c}!important;'>{$money}</span></td>
                        </tr>";
            }else{
                $html1 .= "<tr>
                        <td><i class=\"layui-icon \" style='color:{$c}!important;'>&#xe756;</i><span class=\"first\"> {$i}</span></td>
                        <td></td>
                        <td><span></span></td>
                        </tr>";
            }
            $html1 .= "</tbody>";
        }
        for ($j = 1; $j <= 6; $j++) {
            $cc = $j<= 3 ?$color[$j-1]:"#666";
            if(!empty($list2[$j-1])){
                $money = number_format($list2[$j-1]['total']);
                $html2 .= "<tr>
                        <td><i class=\"layui-icon \" style='color:{$cc}!important;'>&#xe756;</i><span class=\"first\"> {$j}</span></td>
                        <td style='color:{$cc}!important;'>{$list2[$j-1]['title']}</td>
                        <td ><span style='color:{$cc}!important;'>{$money}</span></td>
                        </tr>";
            }else{
                $html2 .= "<tr>
                        <td><i class=\"layui-icon \" style='color:{$cc}!important;'>&#xe756;</i><span class=\"first\"> {$j}</span></td>
                        <td></td>
                        <td><span></span></td>
                        </tr>";

            }
            $html2 .= "</tbody>";
        }
        $data =[
          'html1'   =>$html1,
          'html2'   =>$html2,
        ];
        return diy_json($data, 1, '');
    }
    //首页的数据显示
    public function getData_floor4()
    {
        $field3 = "ordersn,uid,id";
        $list3 = Db::name('shop_order')->field($field3)->where('status', 1)->order('paytime desc')->limit(6)->select();
        $html3 = '<thead>
                        <tr>
                        <th>订单号</th>
                        <th>会员信息</th>
                        <th>支付状态</th>
                        <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="html3" >';
        $html4= '<thead>
                        <tr>
                        <th>订单号</th>
                        <th>会员信息</th>
                        <th>支付状态</th>
                        </tr>
                        </thead>
                        <tbody id="html4" >';
        $field4 = "ordersn,uid";
        $list4 = Db::name('course_order')->field($field4)->where('status', 1)->order('pay_time desc')->limit(6)->select();
        if($list3){
            foreach ($list3  as $item){
                $user = Db::name('user')->where('id',$item['uid'])->field('nickname,mobile')->find();
                $user = $user['mobile']."(".$user['nickname'].")";
                $html3 .= "<tr>
                        <td><i class=\"layui-icon \" style='color:#FF5722!important;'>&#xe756;</i><span class=\"first\"> {$item['ordersn']}</span></td>
                        <td style='color:#FF5722!important;'>{$user}</td>
                        <td style='color:#FF5722!important;'>等待发货</td>
                        <td ><a class=\"layui-btn layui-btn-danger layui-btn-xs\" onclick=\"fahuo({$item['id']})\">确认发货</a></td>
                        </tr>";
            }
        }else{
            $html3 .= "<td colspan='4' style='text-align: center'>暂无更多数据</td></tbody>";
        }
        if($list4){
            foreach ($list4  as $item){
                $user = Db::name('user')->where('id',$item['uid'])->field('nickname,mobile')->find();
                $user = $user['mobile']."(".$user['nickname'].")";
                $html4 .= "<tr>
                        <td><i class=\"layui-icon \" style='color:#FF5722!important;'>&#xe756;</i><span class=\"first\"> {$item['ordersn']}</span></td>
                        <td style='color:#FF5722!important;'>{$user}</td>
                        <td style='color:#FF5722!important;'>已支付</td>
                        </tr>";
            }
        }else{
            $html4 .= "<td colspan='3' style='text-align: center'>暂无更多数据</td></tbody>";
        }
        $data =[
            'html3'   =>$html3,
            'html4'   =>$html4,
        ];
        return diy_json($data, 1, '');
    }
}
