<?php


namespace app\admin\model;


use think\Db;
use think\exception\PDOException;
use think\Model;
class Admin extends Model
{

    //保存登录信息
    public function updateLogin($where, $data)
    {
        try {
            $data['updatetime'] = time();
            $data['logintime'] = time();
            $res = $this->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -2, '操作失败');
            }
        } catch (PDOException $e) {
            return diy_json('', -1, $e->getMessage());
        }
    }
    //修改管理员
    public function update_data($where, $data)
    {
        try {
            $data['updatetime'] = time();
            $res = $this->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -2, '操作失败');
            }
        } catch (PDOException $e) {
            return diy_json('', -1, $e->getMessage());
        }
    }

    //添加管理员
    public function insert_data($data){
        try {
            $res = $this->insert($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -2, '操作失败');
            }
        } catch (PDOException $e) {
            return diy_json('', -1, $e->getMessage());
        }
    }

    //删除管理员
    public function delete_data($where){
        try {
            $data = ['status'=>0,'updatetime'=>time()];
            $res = $this->where($where)->update($data);
            if ($res) {
                return diy_json('', 1, '操作成功');
            } else {
                return diy_json('', -2, '操作失败');
            }
        } catch (PDOException $e) {
            return diy_json('', -1, $e->getMessage());
        }
    }
    /**
     * 获取数据列表
     * @param array $where
     * @param int $page
     * @param int $pagesize
     * @param string $keyword
     */
    public function getList($where = [], $page = 1, $pagesize = 10, $field = "*")
    {
        $select = $this->field($field)->where($where)->order('createtime desc')->limit($page*$pagesize, $pagesize)->select();
        if ($select) {
            foreach ($select as &$item) {
                if (isset($item->role_id)) {
                    if ($item->role_id == 0 && $item->id == 1) {
                        $item->role_id = '超级管理员';
                    } else {
                        $r_where = [tp5_where_str('id', '=', $item->role_id), tp5_where_str('status', '=', 1)];
                        $role = Db::name('role')->where($r_where)->value('name');
                        $item->role_id = empty($role) ? '' : $role;
                    }
                }
                if (isset($item->createtime)) {
                    $item->createtime = empty($item->createtime)? '': date('Y-m-d H:i:s', $item->createtime);
                }
                if (isset($item->logintime)) {
                    $item->logintime = empty($item->logintime)? '': date('Y-m-d H:i:s', $item->logintime);
                }
                if (isset($item->status)) {
                    $item->status = ($item->status == 1) ? "<span style='color:#009688'>正常</span>": "<span style='color:#FF5722'>禁止登录</span>";
                }
            }
            unset($item);
        }
        $count = $this->field($field)->where($where)->count();
        return layui_json(0, '请求成功', $count, $select);
    }
}