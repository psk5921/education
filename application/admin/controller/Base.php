<?php


namespace app\admin\controller;


use think\App;
use think\Controller;
use app\common\constant\DiyCodeConstant;
use app\common\exception\DiyException;
use think\Db;

class Base extends Controller
{
    //初始化调用
    public function initialize()
    {
        $diycode = new DiyCodeConstant;
        //判断登录信息
        $this->login($diycode);
        //判断角色权限
        $this->role();
    }

    /**
     * 判断登录信息
     */
    public function login(DiyCodeConstant $diyCodeConstant)
    {
        //首先判断登录缓存信息是否存在
        if (session('uid', '', 'login') && session('expire', '', 'login')) {
            //其次判断登录信息是否过期
            if (session('expire', '', 'login') < time()) {
                //过期 判断请求是不是ajax请求 给与相关的响应
                if (request()->isAjax()) {
                   exit(json_encode(diy_json('', $diyCodeConstant::LOGIN_USER_INFO_EXPIRED[0], $diyCodeConstant::LOGIN_USER_INFO_EXPIRED[1]),JSON_UNESCAPED_UNICODE));
                } else {
                    $url = url('login/index');
                    $str = "<script src=\"/source/kdwl_admin/lib/layui/layui.js\" charset=\"utf-8\"></script>
<script src=\"/source/kdwl_admin/js/xadmin.js\" charset=\"utf-8\"></script>
<script>
try{
xadmin.close();
location.reload();
parent.parent.location.href = '".$url."';
}catch (e) {
  parent.location.href = '".$url."';
}

</script>
";
                    echo $str;
                    die;
                  //$this->redirect('login/index'); //重定向到登录页面
                }
            }
        } else {
            if (request()->isAjax()) {
                exit(json_encode(diy_json('', $diyCodeConstant::LOGIN_USER_INFO_EXPIRED[0], $diyCodeConstant::LOGIN_USER_INFO_EXPIRED[1]),JSON_UNESCAPED_UNICODE));
            } else {
                $url = url('login/index');
                $str = "<script src=\"/source/kdwl_admin/lib/layui/layui.js\" charset=\"utf-8\"></script>
<script src=\"/source/kdwl_admin/js/xadmin.js\" charset=\"utf-8\"></script>
<script>
try{
xadmin.close();
location.reload();
parent.parent.location.href = '".$url."';
}catch (e) {
  parent.location.href = '".$url."';
}

</script>
";
                echo $str;
                die;
               /*echo "<script>parent.parent.location.href = \"{:url('login/index')}\"</script>";die;*/
                //$this->redirect('login/index'); //重定向到登录页面
            }
        }
    }



    /**
     * 判断角色权限
     */
    public function role()
    {
        //当前访问的方法 当前缓存的数据节点是否在其中
        $roleid = session('role_id', '', 'login');
        $uid = session('uid', '', 'login');
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());//当前方法转化成小写
        $no_access = ['uploadcourse', 'simple', 'xy', 'multiple', 'uploadgood', 'uploadteacher', 'getinfofromcourse']; //不需要验证的方法
        if ($uid == 1 || in_array($action, $no_access)) {
            //超管用户 不判断权限信息
        } elseif ($roleid == -1 || $roleid == 0) {
            //无访问权限直接代码提示无权限并执行终止
            exit('无权限操作');
        } else if ($controller == 'index') {

        } else {
            $role_arr = explode(',', $roleid); //权限节点数组
            if (empty($role_arr)) {
                exit('无权限操作');
            }
            //$controller = strtolower(request()->controller());//当前控制器转化成小写

            $where = [['controller', '=', $controller], ['action', '=', $action], ['status', '<>', 2]];
            $r_id = Db::name('perm')->where($where)->value('id'); //查找节点id 是否存在
            //无访问权限直接代码提示无权限并执行终止
            if (empty($r_id)) {
                exit('无权限操作');
            } else {
                if (!in_array($r_id, $role_arr)) {
                    exit('无权限操作');
                }
            }
        }
    }

    public function fetch($template = '', $vars = [], $config = [])
    {
        // 每个视图共享的变量
        $this->assign('version', config('app.version'));

        return parent::fetch($template, $vars, $config);
    }
}
