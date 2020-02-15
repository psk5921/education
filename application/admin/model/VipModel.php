<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 15:20
 */
namespace app\admin\model;
use think\Model;
class VipModel extends Model{
    protected  $table = "education_user";


    public function getList($where = [], $page = 0, $pagesize = 10, $field = "*")
    {
        $select = $this->field($field)->where($where)->order('createtime desc')->limit($page*$pagesize, $pagesize)->select();

        if ($select) {
            foreach ($select as &$item) {
                if (isset($item->createtime)) {
                    $item->createtime = empty($item->createtime)? '': date('Y-m-d H:i:s', $item->createtime);
                }


            }
            unset($item);
        }
        $count = $this->field($field)->where($where)->count();
        return layui_json(0, '请求成功', $count, $select);
    }


















}