<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/19
 * Time: 13:10
 */
namespace app\admin\controller;
use app\admin\model\MemberModel;
use ArrayAccess;
use Iterator;
use think\Db;
use think\Controller;
use Validator;

class Member extends Base
{
    //会员列表
    public function memberLs()
    {
        if (request()->isAjax()) {
            $param = input('');
            $limit = input('limit') ?? '10';
            $name = input('nickname');
            $filter = [];
            $order = [];
            if ($name) {
                $user_res = MemberModel::memberList($filter, $order, $limit);

                //print_r($user_res);die;
                //$userRes = Db::name('user')->where(['nickname'=>$name])->paginate($limit);
            } else {
                $userRes = Db::name('user')->paginate($limit);
            }
            return json_encode(['code' => 0, 'data' => $userRes]);
        }
        return $this->fetch();
    }

}



