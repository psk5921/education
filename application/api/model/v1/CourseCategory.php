<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 16:18
 */

namespace app\api\model\v1;


use think\Model;

class CourseCategory extends Model
{
    /**
     * 获取课程分类信息
     * @param $where
     * @param $field
     */
    public function getCourseCategory($where=['status'=>0,'deleted'=>0], $field="id,edu_category_en_name,edu_category_name", $order='displayorder desc,createtime desc', $no_limit = false, $limit = 4)
    {
        if ($no_limit) {
            $res = $this->where($where)->field($field)->order($order)->limit($limit)->select();
        }else{
            $res = $this->where($where)->field($field)->order($order)->select();
        }
        return $res;
    }
}