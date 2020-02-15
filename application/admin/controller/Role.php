<?php


namespace app\admin\controller;

use app\admin\model\Role as Roles;
use app\common\constant\DiyCodeConstant;
use app\common\exception\DiyException;
use app\admin\model\Perm;
use think\Db;
class Role   extends  Base
{
    /**
     * 角色列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            //异步请求数据
            $page = (int)input('page') > 0 ? (int)input('page') - 1 : 0; //当前页数
            $pagesize = (int)input('limit') > 0 ? (int)input('limit') : 10; //分页数量
            $where[] = tp5_where_str('status', '=', 1);
            if (input('name')) {
                $where[] = tp5_where_str('name', 'like', '%' . htmlspecialchars(input('name')) . '%');
            }
            $roles = new Roles();
            $field = 'id,name,status,createtime';
            $list = $roles->getList($where, $page, $pagesize, $field);
            return $list;
        }
        return $this->fetch();
    }

    /**
     * 角色添加
     */
    public function add(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $name = RemoveXSS(input('name'));
            if (empty($name)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (Db::name('role')->where([tp5_where_str('name', '=', $name)])->find()) {
                throw new DiyException('角色名称已存在', -1);
            }
            $insert_data = [
                'name' => $name,
                'status' => 1,
                'createtime' => time(),
            ];
            $roles = new Roles();
            $res = $roles->insert_data($insert_data);
            return $res;
        }
        return $this->fetch();
    }

    /**
     * 角色修改
     */
    public function update(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $name = RemoveXSS(input('name'));
            $id = RemoveXSS(input('id'));
            if (empty($name)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $update_data = [
                'name' => $name,
            ];
            $role = new Roles();
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '=', 1)];
            if (false == ( Db::name('role')->field('id,name')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $res = $role->update_data($where,$update_data);
            return $res;
        }
        $id = input('id');
        if (empty($id)) {
            exit($diycode::PARAM_ERROR[1]);
        }
        $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '=', 1)];
        if (false == ($role = Db::name('role')->field('id,name')->where($where)->find())) {
            exit($diycode::INFO_NOT_FOUND[1]);
        }
        $this->assign([
            'role' => $role
        ]);
        return $this->fetch();
    }

    /**
     * 角色删除
     */
    public function delete(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = RemoveXSS(input('id'));
            if (empty($id)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '=', 1)];
            if (false == (Db::name('role')->field('id,name')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $role = new Roles();
            $res = $role->delete_data($where);
            return $res;
        }
    }

    //分配权限
    public function perm(DiyCodeConstant $diycode){

        $Perm = new Perm;
        $field = 'id,name,pid';
        $select = $Perm->getTree($field,'');
        if (request()->isAjax() && request()->isPost()) {
            $id = RemoveXSS(input('id'));
            if (empty($id)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '=', 1)];
            if (false == (Db::name('role')->field('id,name')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $perm = input('perm');
            if(empty($perm) || !is_array($perm)){
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $role = new Roles();
            $update_data = [
                'menu_id' => implode(',',$perm),
                'updatetime' => time(),
            ];
            $res = $role->update_data($where,$update_data);
            return $res;
        }
        $id = input('role_id');
        if (empty($id)) {
            exit($diycode::PARAM_ERROR[1]);
        }
        $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '=', 1)];
        if (false == ($role = Db::name('role')->field('id,name,menu_id')->where($where)->find())) {
            exit($diycode::INFO_NOT_FOUND[1]);
        }
        $role['menu_id']  = !empty($role['menu_id'])?explode(',',$role['menu_id']): [];
        $this->assign([
            'id' => $id         ,
            'perm'=>$select,
            'menu_id'=>$role['menu_id'],
        ]);
        return $this->fetch();
    }
}
