<?php


namespace app\admin\model;


use think\Db;
use think\exception\PDOException;
use think\Model;

class Role  extends Model
{
    /**
     * 查询角色是否存在
     * @param $id
     * @return bool
     */
   public function getRoleExist($id){
       if(empty($id)){
           return false;
       }
       $where = [tp5_where_str('id','=',(int)$id),tp5_where_str('status','=',1)];
       $res = $this->where($where)->count();
       if( $res == 1 ){
           return true;
       }else{
           return false;
       }
   }

    /**
     * 获取数据列表
     * @param array $where
     * @param int $page
     * @param int $pagesize
     * @param string $keyword
     */
    public function getList($where = [], $page = 0, $pagesize = 10, $field = "*")
    {
        $select = $this->field($field)->where($where)->order('createtime desc')->limit($page*$pagesize, $pagesize)->select();
        if ($select) {
            foreach ($select as &$item) {
                if (isset($item->createtime)) {
                    $item->createtime = empty($item->createtime)? '': date('Y-m-d H:i:s', $item->createtime);
                }

                if (isset($item->status)) {
                    $item->status = "<span style='color:#009688'>正常</span>";
                }
            }
            unset($item);
        }
        $count = $this->field($field)->where($where)->count();
        return layui_json(0, '请求成功', $count, $select);
    }


    //修改角色
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

    //添加角色
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

    //删除角色
    public function delete_data($where){
        try {
            $data = ['status'=>2,'updatetime'=>time()];
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
}