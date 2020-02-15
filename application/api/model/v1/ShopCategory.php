<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/23
 * Time: 10:11
 */

namespace app\api\model\v1;


use think\Db;
use think\Model;

class ShopCategory   extends  Model
{
    /**
     * 获取商品数据 加分类
     * @param int $cateid
     * @param int $page
     * @param int $pagesize
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getShopCategory($cateid=0,$page=1,$pagesize=10)
    {
        $o_where=['status'=>1,'deleted'=>0];
        $o_field="id,shop_category_name";
        $o_order='displayorder desc,id desc';
        $res['category'][0] = ['id'=>0,'shop_category_name'=>'ALL全部'];
        $category = $this->where($o_where)->field($o_field)->order($o_order)->select();
        if($category){
            foreach ($category as $cate){
                $res['category'][] = $cate;
            }
        }
        //$res['category'] = array_merge($all,$category);
        $res['cateid'] = $cateid;
        if(!empty($cateid)){
            $g_where = ['status'=>1,'deleted'=>0,'cid'=>$cateid];
        }else{
            $g_where = ['status'=>1,'deleted'=>0];
        }
        $field = 'id,title,short_title,img,price';
        $order = 'displayorder desc,id desc';
        $limit = ceil($page-1)*$pagesize.','.$pagesize;
        $res['data'] = Db::name('shop_goods')->where($g_where)->field($field)->order($order)->limit($limit)->select();
        return $res;
    }
}