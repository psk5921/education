<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/20
 * Time: 15:10
 */
namespace app\admin\controller;
use think\Db;
use app\common\exception\DiyException;
use app\admin\model\VipModel;
use app\admin\validate\Vip as vipcheck;
class Vip extends Base{
    //会员列表
    public function ls(){

        if(request()->isAjax()){
            $limit = input("limit");
            $page = input('page');
            $name = input('name');
            $start = input('start');
            $end = input('end');
            $where = array();
            $search = array();
            if(!empty($name)){
                //$where[] = ['nickname','like','%'.$name.'%'];
                $search[] = ['nickname','like','%'.RemoveXSS($name).'%'];
            }
            if ($start){
                $start = strtotime($start);
                $search[] =['createtime','>',$start];
            }
            if ($end){
                $end = strtotime($end);
                $search[] =['createtime','<',$end];
            }
            $order = 'id desc';
            $sel = Db::name('user')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page-1)*$limit,$limit)
                ->select();
            if($sel){
                foreach ($sel as $k=>&$v){
                    //  print_r($v);
                    $v['createtime'] = date('Y-m-d',$v['createtime']) . "<br/>" . date('H:i:s',$v['createtime']);

                    if ($v['identity'] == 0){
                        $v['identity'] = "<span style='color:green'>粉丝用户</span>";
                    }elseif($v['identity'] == 1){
                        $v['identity'] = "<span style='color:red'>付费用户</span>";
                    }elseif($v['identity'] == 2){
                        $v['identity'] = "<span style='color:mediumvioletred'>教师</span>";
                    }
                    if($v['is_verification'] == 0){
                        $v['is_verification'] = "<span style='color:green'>否</span>";
                    }elseif( $v['is_verification'] = 1){
                        $v['is_verification'] = "<span style='color:red'>是</span>";
                    }
                    if ($v['gender'] == 0){
                        $v['gender'] = '未知';
                    }elseif($v['gender'] == 1){
                        $v['gender'] = '男';
                    }elseif($v['gender'] == 2){
                        $v['gender'] = '女';
                    }
                }
            }
            $count = Db::name('user')
                ->where($where)
                ->where($search)
                ->order($order)
                ->limit(ceil($page-1)*$limit,$limit)
                ->count();
            return layui_json(0, '请求成功', $count, $sel);


        }

        return $this->fetch();
    }

    //添加会员
    public function add(){

        if(request()->isPost()){
            $data = input('post.');
            $validate = new vipcheck;
            if (!$validate->check($data,[],'common')) {
                return json(['code' => 0, 'msg' => $validate->getError()]);
            }
            if (isset($data['file'])){
                unset($data['file']);
            }
            //print_r($data);die;
            $data['createtime'] = time();
            $add = Db::name('user')->insert($data);
            if($add){
                return json(['code'=>1,'msg'=>'添加会员成功']);
            }

        }


        return $this->fetch();
    }




    //编辑会员
    public function update(){

        if(request()->isPost()){
            $data = input('post.');
         /*   $validate = new vipcheck;
            if (!$validate->check($data,[],'common')) {
                return json(['code' => 0, 'msg' => $validate->getError()]);
            }
            if (isset($data['file'])){
                unset($data['file']);
            }*/
            foreach ($data as &$item){
                $item = RemoveXSS($item);
            }
            unset($item);
            $id = $data['id'];
            $res = Db::name('user')
                    ->where(['id'=>$id])
                    ->update($data);
            if ($res) {
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }

        }
        $id = input('id');
        $sel = Db::name('user')
            ->where('id',$id)
            ->find();
        $this->assign('sel',$sel);
        return $this->fetch();

    }


    //删除会员
    public function delete(){

        if(request()->isAjax()){
            $id = input('id');
            $res = Db::name('user')->delete($id);
            if($res){
                return json(['code'=>1,'msg'=>'删除会员成功']);
            }
        }

    }





  ###########################################################################

    //会员的宝宝
    public function baby_list(){

    if(request()->isAjax()){
        $limit = input("limit");
        $page = input('page');
        $id = input('id');
        $sel = Db::name('user_baby')
            ->where(['uid'=>$id])
            ->limit(ceil($page-1)*$limit,$limit)
            ->select();
        foreach($sel as &$v){
            $v['createtime'] = date('Y-m-d',$v['createtime']) . "<br/>".date('H:i:s',$v['createtime']);
            $v['year'] = $v['birth_year']. '年-' .$v['birth_month'].'月'. $v['birth_day'].'日';
            if($v['baby_sex'] == 0){
                $v['baby_sex'] = '未知';
            }else if($v['baby_sex'] == 1){
                $v['baby_sex'] = '男';
            }else if($v['baby_sex'] == 2){
                $v['baby_sex'] = '女';
            }
        }
        $count = Db::name('user_baby')
            ->where(['uid'=>$id])->count();
        return layui_json(0, '请求成功', $count, $sel);
    }
        $id = input('id');
        $where = ['uid'=>(int)$id,'deleted'=>0];
        $cnt = Db::name('user_baby')
            ->where($where)->count();
        $this->assign('id',$id);
        $this->assign('cnt',$cnt);
        return $this->fetch();

    }



    //修改宝宝信息
    public  function  update_baby(){

        if(request()->isPost()){

            $data = input('post.');
            $map = ['baby_name','id'];
            if(empty($data)){
                return diy_json('',-1,'缺少必要参数');
            }
            foreach ($map as $k){
                if(empty($data[$k])){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            $id = $data['id'];
            //判断宝宝信息是否存在
            $baby_where = ['id'=>$id,'deleted'=>0];
            if(!($user = Db::name('user_baby')->where($baby_where)->find()) ){
                return diy_json('',-1,'宝宝信息不存在');
            }
            if(!empty($data['babytime'])){
                $arr = explode('-',$data['babytime']);
                $data['birth_year'] = $arr['0'];
                $data['birth_month'] = $arr['1'];
                $data['birth_day'] = $arr['2'];
                unset($data['babytime']);
            }
            $add = Db::name('user_baby')->where($baby_where)->update($data);
            if($add){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }



        }
        $id = input('id');
        $sel = Db::name('user_baby')->where(['id'=>$id])->find();
        if($sel){
            $sel['babytime'] =  $sel['birth_year'].'-'.$sel['birth_month'].'-'.$sel['birth_day'];
        }
        $this->assign('sel',$sel);
        return $this->fetch();



    }



    //添加宝宝信息
    public function add_baby(){
        if(request()->isPost()){
            $data = input('post.');
            $map = ['uid','baby_name'];
            if(empty($data)){
                return diy_json('',-1,'缺少必要参数');
            }
            foreach ($map as $k){
                if(empty($data[$k])){
                    return diy_json('',-1,'缺少必要参数');
                }
            }
            $uid = $data['uid'];
            //判断用户是否存在
            $user_where = ['id'=>$uid];
            if(!($user = Db::name('user')->where($user_where)->find()) ){
                return diy_json('',-1,'用户不存在');
            }
           if(!empty($data['babytime'])){
               $arr = explode('-',$data['babytime']);
               $data['birth_year'] = $arr['0'];
               $data['birth_month'] = $arr['1'];
               $data['birth_day'] = $arr['2'];
               unset($data['babytime']);
           }
            $data['createtime'] = time();
            $baby_where = ['uid'=>$uid,'deleted'=>0];
           if(Db::name('user_baby')
               ->where($baby_where)->count()>0){
               return diy_json('',-1,'一个会员仅可添加一个宝宝信息');
           }
            $add = Db::name('user_baby')->insert($data);
            if($add){
                return diy_json('',1,'操作成功');
            }else{
                return diy_json('',-1,'操作失败,请重新尝试');
            }
        }
        $id = input('id');
        $this->assign('uid',$id);
        return $this->fetch();

    }


    //删除宝宝
    public function del_baby(){
        if(request()->isAjax()){
            $id = input('id');
            $res = Db::name('user_baby')->delete($id);
            if($res){
                return json(['code'=>1,'msg'=>'删除宝宝成功']);
            }
        }


    }




#################################################################################
    //用户家庭住址列表
    public function address_ls(){

    if(request()->isAjax()){
        $limit = input("limit");
        $page = input('page');
        $id = input('id');
        $sel = Db::name('user_home_address')
            ->alias('a')
            ->join('user u','a.uid=u.id','left')
            ->field('a.*,u.nickname')
            ->where(['uid'=>$id])
            ->limit(($page-1)*$limit,$limit)
            ->select();
        foreach($sel as &$v){
            $v['createtime'] = date('Y-m-d',$v['createtime']);

            $v['adress'] = $v['province'] . '-' . $v['city'] .'-' . $v['country'] ;

        }
        $count = db('user_home_address')->count();
        return layui_json(0, '请求成功', $count, $sel);

    }

    $id = input('id');

    $this->assign('id',$id);
    return $this->fetch();

    }


    //修改家庭住址
    public function update_address(){

        if(request()->isPost()){

            $data = input('post.');
            $id = $data['id'];
            $res = Db::name('user_home_address')
                ->where(['id'=>$id])
                ->update($data);
            if($res){
                return json(['code'=>'1','msg'=>'编辑会员家庭住址成功']);
            }


        }
        $id = input('id');
        $sel = Db::name('user_home_address')->where(['id'=>$id])->find();

        $this->assign('sel',$sel);
        return $this->fetch();
    }




    //删除用户家庭地址
    public function del_address(){

        if(request()->isAjax()){
            $id = input('id');
            $res = Db::name('user_home_address')->delete($id);
            if($res){
                return json(['code'=>1,'msg'=>'删除会员家庭地址成功']);
            }
        }


    }




    //添加用户家庭地址
    public function add_address(){
        if(request()->isPost()){
            $data = input('post.');

            $data['createtime'] = time();
            $add =  Db::name('user_home_address')->insert($data);
            if($add){
                return json(['code'=>1,'msg'=>'添加会员家庭住址成功']);
           }

        }
        $id = input('id');
        $this->assign('uid',$id);
        return $this->fetch();

    }

####################################################################################
//积分记录列表
    public function jifen_ls(){

    if(request()->isAjax()){
        $limit = input("limit");
        $page = input('page');
        $id = input('id');
        $sel = Db::name('user_intergral_log')
            ->alias('a')
            ->join('user b','a.uid=b.id')
            ->field('a.*,b.nickname')
            ->limit(ceil($page-1)*$limit,$limit)
            ->where(['a.uid'=>$id])
            ->select();


        foreach ($sel as &$v){
            $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);

            if($v['type']==0){
                $v['type'] = "<span style='color:#0000FF'>购买</span>";
            }elseif($v['type']==1){
                $v['type'] = "<span style='color: orangered'>后台</span>";
            }
        }

        $count = Db::name('user_intergral_log')
            ->alias('a')
            ->join('user b','a.uid=b.id')
            ->field('a.*,b.nickname')
            ->limit(ceil($page-1)*$limit,$limit)
            ->where(['a.uid'=>$id])
            ->count();

        return layui_json(0, '请求成功', $count, $sel);



    }
    $id = input('id');
    $this->assign('id',$id);
    return $this->fetch();

}



//编辑用户积分记录
public function jifen_update(){
        if(request()->isPost()){
            $id = input('id');
          $data = input('post.');

          $data['createtime'] = time();
          $res = Db::name('user_intergral_log')->where(['id'=>$id])->update($data);
          if($res){
              return json(['code'=>'1','msg'=>'编辑会员积分记录成功']);
          }

        }
        $id = input('id');
        $sel = Db::name('user_intergral_log')->find($id);
        $this->assign('sel',$sel);
        return $this->fetch();


}



//添加用户积分记录
public function jifen_add(){
       if(request()->isPost()){

        $data = input('post.');
        $data['createtime'] = time();
        $add = Db::name('user_intergral_log')->insert($data);
        if($add){
            return json(['code'=>1,'msg'=>'添加会员积分记录成功']);
        }

       }
        $id = input('id');
        $user = Db::name('user')->select();
        $this->assign('user',$user);
        $this->assign('uid',$id);
        return $this->fetch();


}






public function jifen_del(){

        if(request()->isAjax()){
            $id = input('id');
            $res = Db::name('user_intergral_log')->delete($id);
            if($res){
                return json(['code'=>1,'msg'=>'删除会员积分记录成功']);
            }
        }


}






###################################################################################


    //上传图片路径
    public function move()
    {
        if (request()->isAjax()){
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move('../public/uploads/image');
            if ($info) {
                // 成功上传后 获取上传信息
                $url = IMG_URL.'/uploads/image/' . $info->getSaveName();
                $url = str_replace("\\", "/", $url);
                return diy_json($url, 1, '操作成功');
            } else {
                // 上传失败获取错误信息
                //return $this->hrf_json(-1,);
                return diy_json('', -2, '获取错误信息' . $file->getError());
            }
        }
    }










}