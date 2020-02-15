<?php


namespace app\admin\controller;


use think\Controller;
use think\Db;
use think\Request;
use app\common\constant\DiyCodeConstant;
use app\common\exception\DiyException;
use app\admin\model\Admin;
class Login  extends Controller
{
    //登录页面
   public function index(){
       return $this->fetch();
   }

   //提交登录
    public function login(Request $request,DiyCodeConstant $diycode){
        try{
            if($request->isAjax() && $request->isPost()){

                //ajax 提交数据
                $param = input('param.'); //请求参数
                if(!isset($param['username']) ||  empty($param['username'])){
                    throw new DiyException($diycode::LOGIN_USER_EMPTY_ERROR[1],$diycode::LOGIN_USER_EMPTY_ERROR[0]);
                }
                if(!isset($param['password']) ||  empty($param['password']) ){
                    throw new DiyException($diycode::LOGIN_PWD_EMPTY_ERROR[1],$diycode::LOGIN_PWD_EMPTY_ERROR[0]);
                }
                $where = [['username','=',htmlspecialchars($param['username'])],['status','>',0]];
                if(false == ($user = Db::name('AdminConstant')->where($where)->field('id,password,status,logincount,loginip,logintime,role_id,username')->find())){
                    throw new DiyException($diycode::LOGIN_USER_NOT_FOUND_ERROR[1],$diycode::LOGIN_USER_NOT_FOUND_ERROR[0]);
                }

                if( $user['status'] == 2 ){
                    throw new DiyException($diycode::LOGIN_DENY_ERROR[1],$diycode::LOGIN_DENY_ERROR[0]);
                }

                if(false == password_verify(htmlspecialchars($param['password']),$user['password'])){
                    throw new DiyException($diycode::LOGIN_INFO_ERROR[1],$diycode::LOGIN_INFO_ERROR[0]);
                }

                //验证完成 可以正常登录
                $login_ip = $request->ip(); //登录ip
                $data['loginip'] = $login_ip;
                $data['logincount'] = $user['logincount'] + 1;
                //设置登录保存信息
                $admin = new Admin;
                $res = $admin->updateLogin($where,$data);
                if($res['code'] == 1){
                    session('uid', $user['id'], 'login');//登录用户id
                    session('username', $user['username'], 'login'); //登录用户名
                    session('logincount', $user['logincount'], 'login'); //总登录次数
                    session('logintime', $user['logintime'], 'login'); //最近一次登录时间
                    session('loginip', $user['loginip'], 'login'); //最近一次登录ip
                    session('expire', time()+7200, 'login'); //有效期2小时 登录信息失效
                    if( $user['role_id'] == 0 ){
                        session('role_id', $diycode::PERM_NO[0], 'login'); //没有权限
                    }else{
                        $r_where = ['id'=>$user['role_id'],'status'=>1];
                        if($role = Db::name('role')->where($r_where)->value('menu_id')){
                            session('role_id', $role, 'login'); //当前用户拥有的权限
                        }else{
                            session('role_id', $diycode::ROLE_NOT_EXIST[0], 'login'); //信息不存在
                        }
                    }
                    return diy_json('',$diycode::LOGIN_SUCCESS[0],$diycode::LOGIN_SUCCESS[1]);
                }else{
                    throw new DiyException($diycode::LOGIN_ERROR[1],$diycode::LOGIN_ERROR[0]);
                }
            }else{
                throw new DiyException($diycode::REQUEST_METHOD_ERROR[1],$diycode::REQUEST_METHOD_ERROR[0]);
            }
        }catch (DiyException $e){
            return diy_json('',$e->getError(),$e->getStatusCode());
        }

    }

    //退出登录
    public function logout(){
        session(null, 'login');
        $this->redirect('login/index');
    }
}
