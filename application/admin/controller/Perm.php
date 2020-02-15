<?php


namespace app\admin\controller;


use app\common\constant\DiyCodeConstant;
use app\common\exception\DiyException;
use app\admin\model\Perm as Perms;
use app\admin\validate\Perm as Per;
use think\Db;

class Perm   extends  Base
{
    /**
     * 权限列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            //异步请求数据
            /*$where[] = tp5_where_str('status', '<>', 2);
            $where[] = tp5_where_str('pid', '=', 0);*/
            /*if (input('name')) {
                $where[] = tp5_where_str('name', 'like', '%' . htmlspecialchars(trim(input('name'))) . '%');
            }*/
            $Perms = new Perms();
            $field = 'id,name,status,create_time,controller,action,sortnum,pid';
            $select = $Perms->getTree($field);
            $list = $Perms->getList($select);
            return layui_json(0,'',0,$list);
        }
        return $this->fetch();
    }

    /**
     * 权限添加
     */
    public function add(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $sortnum = (int)(input('sortnum'));
            $name = RemoveXSS(input('name'));
            $pid = (int)(input('pid'));
            $controller = strtolower(RemoveXSS(input('controller')));
            $action = strtolower(RemoveXSS(input('action')));
            $iconfont = RemoveXSS(input('iconfont'));
            $status = (int)(input('status'));
            $is_menu = (int)(input('is_menu'));
            $Perms = new Perms;
            if (empty($name)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }else{
                if($Perms->getPermExist($name)){
                    throw new DiyException('该菜单名称已存在', $diycode::INFO_REPEAT[0]);
                }
            }
            if (!empty($pid) && Db::name('perm')->where([tp5_where_str('id', '=', $pid),tp5_where_str('status', '<>', 2)])->count() == 0 ) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            if (empty($controller) || empty($action) ) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (!in_array($status,[0,1])) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (!in_array($is_menu,[0,1])) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $insert_data = [
                'name' => $name,
                'sortnum' => $sortnum,
                'pid' => $pid,
                'controller' => $controller,
                'action' => $action,
                'iconfont' => $iconfont,
                'status' => $status,
                'is_menu' => $is_menu,
                'create_time' => time(),
            ];
            $validate = new Per;
            if (!$validate->check($insert_data,[],'common')) {
                throw new DiyException($diycode::VALIDATE_ERROR[1], $validate->getError());
            }
            $Perms = new Perms();
            $res = $Perms->insert_data($insert_data);
            return $res;
        }
        //获取顶级菜单的数据
        $Perms = new Perms();
        $field = 'id,name,pid';
        $select = $Perms->getTree($field,'— — ',1);
        $select = $Perms->getList($select,1);
        $this->assign(['top_menu'=>$select]);
        return $this->fetch();
    }

    /**
     * 权限修改
     */
    public function update(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $sortnum = (int)(input('sortnum'));
            $name = RemoveXSS(input('name'));
            $controller = strtolower(RemoveXSS(input('controller')));
            $action = strtolower(RemoveXSS(input('action')));
            $iconfont = RemoveXSS(input('iconfont'));
            $status = (int)(input('status'));
            $is_menu = (int)(input('is_menu'));
            $id = (int)(input('id'));
            if(empty($id)){
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }else{
                $where = [['id','=',$id],['status','<>',2]];
                if(Db::name('perm')->where($where)->count() == 0 ){
                    throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
                }
            }
            if (empty($name)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (empty($controller) || empty($action) ) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (!in_array($status,[0,1])) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            if (!in_array($is_menu,[0,1])) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $update_data = [
                'name' => $name,
                'sortnum' => $sortnum,
                'controller' => $controller,
                'action' => $action,
                'iconfont' => $iconfont,
                'status' => $status,
                'is_menu' => $is_menu,
            ];
            $validate = new Per;
            if (!$validate->check($update_data,[],'common')) {
                throw new DiyException($diycode::VALIDATE_ERROR[1], $validate->getError());
            }
            $Perms = new Perms();
            $res = $Perms->update_data($where,$update_data);
            return $res;
        }
        $id = input('id');
        if (empty($id)) {
            exit($diycode::PARAM_ERROR[1]);
        }
        $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '<>', 2)];
        if (false == ($perm = Db::name('perm')->field('*')->where($where)->find())) {
            exit($diycode::INFO_NOT_FOUND[1]);
        }
        //获取顶级菜单的数据
        $Perms = new Perms();
        $field = 'id,name,pid';
        $select = $Perms->getTree($field,'',1);
        $select = $Perms->getList($select,1);
        $this->assign([
            'perm' => $perm,
            'top_menu'=>$select,
        ]);
        return $this->fetch();
    }

    /**
     * 权限删除
     */
    public function delete(DiyCodeConstant $diycode)
    {
        if (request()->isAjax() && request()->isPost()) {
            $id = RemoveXSS(input('id'));
            if (empty($id)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '<>', 2)];
            if (false == (Db::name('perm')->field('id,name')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $role = new Perms();
            $res = $role->delete_data($where);
            return $res;
        }
    }

    //图标库
    function iconfont(){
        return $this->fetch();
    }

    //排序修改
    function sortnum(DiyCodeConstant $diycode){
        if (request()->isAjax() && request()->isPost()) {
            $id = (int)(input('id'));
            $sortnum = (int)(input('sortnum'));
            if (empty($id)) {
                throw new DiyException($diycode::PARAM_ERROR[1], $diycode::PARAM_ERROR[0]);
            }
            $where = [tp5_where_str('id', '=', $id), tp5_where_str('status', '<>', 2)];
            if (false == (Db::name('perm')->field('id')->where($where)->find())) {
                throw new DiyException($diycode::INFO_NOT_FOUND[1], $diycode::INFO_NOT_FOUND[0]);
            }
            $role = new Perms();
            $data = [
                    'sortnum'=>$sortnum
            ];
            $res = $role->update_data($where,$data);
            return $res;
        }
    }
}
