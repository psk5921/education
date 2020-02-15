<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/19
 * Time: 15:23
 */

namespace app\admin\model;


use think\Model;

class MemberModel extends Model
{
    protected $table = 'education_user';

    public static function memberList(array $filter = [] , array $order = ['field' => 'id' , 'value' => 'desc'] , int $limit = 10)
    {
        $filter['nickname'] = $filter['nickname'] ?? '';
        $filter['id'] = $filter['id'] ?? '';
        $order['field'] = $order['field'] ?? 'id';
        $order['value'] = $order['value'] ?? 'desc';

        $where = [];

        if ($filter['nickname'] !== '') {
            $where[] = ['nickname' , '=' , $filter['nickname']];
        }
        $res = self::where($where)
            ->order($order['field'] , $order['value'])
            ->paginate($limit);

        // think\paginator\driver\Bootstrap
//        print_r($res);



        $res = convert_obj($res);


      //  print_r($res);

        foreach ($res->data as $v)
        {

            $v->identity_explain = get_value('busiess.identity_type' , $v->identity);

        }
        return $res;

    }


}