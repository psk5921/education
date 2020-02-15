<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 15:30
 */

namespace app\api\model\v1;


use think\Model;

class ShopGoods  extends  Model
{
    /**
     * 验证商品是否有效
     * @param $gid
     * @return bool
     */
   public function goodsIsUseful($gid){
       if(empty($gid)){
           return false;
       }
       $where = ['id'=>$gid,'status'=>1,'deleted'=>0];
       $cnt = (int)$this->where($where)->count();
       if($cnt == 0){
           return false;
       }
       return true;
   }

    /**
     * 获取指定商品信息
     * @param $gid
     * @param string $field
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function getFieldById($gid,$field='id,title,short_title,img,price'){
       if(empty($gid)){
           return false;
       }
       $where = ['id'=>$gid,'status'=>1,'deleted'=>0];
       $res = $this->where($where)->field($field)->find();
       return $res;
   }

    /**
     * 返回支付前订单的页面数据
     * @param $gid
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function getOrderTotalPrice($gid){
        if(!is_array($gid) || count($gid) == 0 ){
            return false;
        }
        $total = 0;
        $gtotal = 0;
        $data = [
            'goods' => null,
            'total' => 0,
        ];
        foreach ($gid as $k=>$g){
            if(!$this->goodsIsUseful($k)){
                break;
            }
            $goods = $this->getFieldById($k);
            if($goods){
                $total += bcmul($goods['price'],$g,2);
                $goods['num'] = $g;
                $gtotal += $g;
                $data['goods'][] = $goods;
            }
        }
        $data['total'] = $total;
        $data['gtotal'] = $gtotal;
        return  $data;
   }
}