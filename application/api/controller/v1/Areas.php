<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 20:59
 */

namespace app\api\controller\v1;


use app\code\Api;
use think\Controller;
use think\Db;

    class Areas   extends       Base
{
    /**
     * 根据id 筛选区域数据
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
     public function getArea(){
         $id = isset($this->_input['id']) ? (int)$this->_input['id'] : -1;
         if(empty($id)){
             $id = -1;
         }
         $field =   'areaId,areaName';
         $where = ['parentId'=>$id];
         $list  =  Db::name('area')->where($where)->field($field)->select();
         return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$list);
     }
}