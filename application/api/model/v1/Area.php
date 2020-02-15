<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/29
 * Time: 9:13
 */

namespace app\api\model\v1;


use think\Model;

class Area  extends Model
{

    /**
     * 获取地区名称
     * @param $id
     * @return mixed
     */
    public function getAreaName($id){
        $where = ['areaId'=>$id];
        return  $this->where($where)->value('areaName');
    }

    /**
     * 查看区域id 是否有效
     * @param $id
     * @return bool
     */
    public function checkAreaId($id,$pid){
        $where = ['areaId'=>$id,'parentId'=>$pid];
        return  ($this->where($where)->count(1) == 1) ? true : false;
    }
}