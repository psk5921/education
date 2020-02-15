<?php


namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\Role;
use app\common\constant\DiyCodeConstant;
use app\common\exception\DiyException;
use think\Db;

class User extends Base
{
    /**
     * 管理员列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            //异步请求数据
            $page = (int)input('page') > 0 ? (int)input('page') - 1 : 0; //当前页数
            $pagesize = (int)input('limit') > 0 ? (int)input('limit') : 10; //分页数量
            $where[] = tp5_where_str('status', '>', 0);
            if (input('username')) {
                $where[] = tp5_where_str('username', 'like', '%' . htmlspecialchars(input('username')) . '%');
            }
            $admin = new Admin();
            $field = 'id,username,role_id,createtime,logintime,loginip,logincount,status';
            $list = $admin->getList($where, $page, $pagesize, $field);
            return $list;
        }
        return $this->fetch();
    }

    /**
     * 管理员添加
     */
    public function add(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $username = RemoveXSS(input('username'));
            $password = RemoveXSS(input('password'));
            $status = (int)input('status');
            $role_id = (int)input('role_id');
            if (empty($username) || empty($password)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (Db::name('AdminConstant')->where([tp5_where_str('username', '=', $username)])->find()) {
                throw new DiyException('管理员名称已存在', -1);
            }
            $insert_data = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'createtime' => time(),
            ];
            $admin = new Admin();
            $role = new Role();
            if(!empty($role_id) && !$role->getRoleExist($role_id)){
                throw new DiyException($diycode::ROLE_NOT_EXIST[1], -1);
            }else{
                $insert_data['role_id']  = $role_id;
            }
            if(!in_array($status,[1,2])){
                throw new DiyException('状态选择有误', -1);
            }else{
                $insert_data['status']  = $status;
            }
            $res = $admin->insert_data($insert_data);
            return $res;
        }
        $r_where = [tp5_where_str('status','=',1)];
        $role = Db::name('role')->field('id,name')->where($r_where)->select();
        $this->assign([
            'role' => $role
        ]);
        return $this->fetch();
    }

    /**
     * 管理员修改
     */
    public function update(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $username = RemoveXSS(input('username'));
            $password = RemoveXSS(input('password'));
            $status = (int)input('status');
            $role_id = (int)input('role_id');
            $id = RemoveXSS(input('id'));
            if (empty($username)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $update_data = [
                'username' => $username,
            ];
            if(!empty($password)){
                $update_data['password'] = password_hash($password, PASSWORD_BCRYPT);
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '<>', 0)];
            if (false == (Db::name('AdminConstant')->field('id,username,status,role_id')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $admin = new Admin();
            $role = new Role();
            if(!empty($role_id) && !$role->getRoleExist($role_id)){
                throw new DiyException($diycode::ROLE_NOT_EXIST[1], -1);
            }else{
                $update_data['role_id']  = $role_id;
            }
            if(!in_array($status,[1,2])){
                throw new DiyException('状态选择有误', -1);
            }else{
                $update_data['status']  = $status;
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '=', 1)];
            $res = $admin->update_data($where,$update_data);
            return $res;
        }
        $id = input('id');
        if (empty($id)) {
            exit($diycode::PARAM_ERROR[1]);
        }
        $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '<>', 0)];
        if (false == ($user = Db::name('AdminConstant')->field('id,username,status,role_id')->where($where)->find())) {
            exit($diycode::INFO_NOT_FOUND[1]);
        }
        $r_where = [tp5_where_str('status','=',1)];
        $role = Db::name('role')->field('id,name')->where($r_where)->select();
        $this->assign([
            'user' => $user,
            'role' => $role
        ]);
        return $this->fetch();
    }

    /**
     * 管理员删除
     */
    public function delete(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = RemoveXSS(input('id'));
            if (empty($id)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '<>', 0)];
            if (false == ($user = Db::name('AdminConstant')->field('id,username')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $admin = new Admin();
            $res = $admin->delete_data($where);
            return $res;
        }
    }
}