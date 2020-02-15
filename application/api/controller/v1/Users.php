<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/20
 * Time: 11:33
 */

namespace app\api\controller\v1;


use app\code\Api;
use think\Db;
use app\api\validate\v1\User as v_User;
use app\api\validate\v1\UserHomeAddress;
use app\api\validate\v1\UserDeliver as v_UserDeliver;
use app\api\model\v1\User as m_User;
use app\api\model\v1\UserCourse;
use app\api\model\v1\UserDeliver;
use app\api\model\v1\Area;
use app\api\validate\v1\Feedback;
use app\api\model\v1\UserHomeAddress as m_UserHomeAddress;
use app\api\model\v1\Feedback as m_Feedback;
use Endroid\QrCode\QrCode;
use app\api\controller\v1\Wechat;
class Users  extends  Base
{

    /**
     * 获取openid 并保存用户信息
     * @return false|string
     */
    public function getOpenid(){
       $Wechat  = new Wechat;
        $code = isset($this->_input['code']) ? $this->_input['code'] : null;
        if(empty($code)){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $res = $Wechat->auth_code2Session($code);
        if($res && !isset($res['errcode'])){
            $map = [
              'openid' =>   $res['openid'],
              'session_key' =>   $res['session_key'],
              'createtime' =>   time(),
            ];
            $where = ['openid'=>$res['openid']];
            if(Db::name('user')->where($where)->find()){
                $u_update = ['session_key'=>$res['session_key']];
                Db::name('user')->where($where)->update($u_update);
            }else{
                Db::name('user')->insert($map); //用户入库
            }
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$res['openid']);
        }else{
            return api_json(Api::PARAM_ERROR[0],$res['errmsg']);
        }
    }
    /**
     * 用户授权用户信息
     */
    public function  bindUserInfo(){
       $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
       $nickname = isset($this->_input['nickname']) ? $this->_input['nickname'] : null;
       $avatar = isset($this->_input['avatar']) ? $this->_input['avatar'] : null;
       $gender = isset($this->_input['gender']) ? $this->_input['gender'] : null;
       $province = isset($this->_input['province']) ? $this->_input['province'] : null;
       $city = isset($this->_input['city']) ? $this->_input['city'] : null;
       $country = isset($this->_input['country']) ? $this->_input['country'] : null;
       $m_User = new m_User();
       $map = compact('openid','nickname','avatar','gender','province','city','country');
       if($m_User->findByOpenid($openid)){
          //更新不走验证器
           $res = $m_User->_insert($map);
           return $res;
       }
       //新增
        //$v_User = new v_User();
        /*if (!$v_User->check($map)) {
            return api_json(Api::PARAM_ERROR[0],$v_User->getError());
        }*/
        $res = $m_User->_insert($map);
        return $res;
    }

    /**
     * 绑定手机号
     */
    public function bindMobile(){
        $code = isset($this->_input['code']) ? $this->_input['code'] : null;
        $iv = isset($this->_input['iv']) ? $this->_input['iv'] : null;
        $encryptedData = isset($this->_input['encryptedData']) ? $this->_input['encryptedData'] : null;
        if(empty($code) || empty($iv) ||empty($encryptedData) ){
            return api_json(Api::PARAM_ERROR[0],'缺少必要参数');
        }
        $Wechat  = new Wechat;
        $res = $Wechat->auth_code2Session($code);
        if($res && !isset($res['errcode'])){
            $openid =  $res['openid'];
            $m_User = new m_User();
            if(!($uu =$m_User->findByOpenid($openid))){
                return api_json(Api::PARAM_ERROR[0],'用户信息不存在');

            }
            /*$map = [
                'openid' =>   $res['openid'],
                'session_key' =>   $res['session_key'],
                'createtime' =>   time(),
            ];*/
          /*  file_put_contents('a.log',var_export($res,true).PHP_EOL,FILE_APPEND);*/
            $d = $Wechat->decryptData($encryptedData,$iv,$ddatas,$res['session_key']);
            $ud = $Wechat->decryptData($encryptedData,$iv,$udatas,$uu['session_key']);
/*
            file_put_contents('a.log',var_export($encryptedData,true).PHP_EOL,FILE_APPEND);
            file_put_contents('a.log',var_export($iv,true).PHP_EOL,FILE_APPEND);
            file_put_contents('a.log',var_export($ddatas,true).PHP_EOL,FILE_APPEND);
            file_put_contents('a.log',var_export($udatas,true).PHP_EOL,FILE_APPEND);
            file_put_contents('a.log',var_export($uu['session_key'],true).PHP_EOL,FILE_APPEND);
            file_put_contents('a.log',var_export($res['session_key'],true).PHP_EOL,FILE_APPEND);
            file_put_contents('a.log','sss'.var_export($d,true).PHP_EOL,FILE_APPEND);*/
            //解密数据
            if($d!=0 && $ud!=0){
                return api_json(Api::PARAM_ERROR[0],'获取手机号失败,请重新尝试');
            }
            if($d==0){
                $datas = $ddatas;
            }
            if($ud==0){
                $datas = $udatas;
            }
           // $datas = json_decode($datas,true);
            //$map['mobile'] = $datas['phoneNumber'];
            $dataObj=json_decode( $datas );
            $where = ['openid'=>$res['openid']];
            $u_update = ['mobile'=>$dataObj->phoneNumber,'session_key'=>$res['session_key']];
            Db::name('user')->where($where)->update($u_update);
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$dataObj->phoneNumber);
        }else{
            return api_json(Api::PARAM_ERROR[0],$res['errmsg']);
        }
    }


    /**
     * 用户是否登录
     */
    public function isLogin(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
           return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $where = ['openid'=>$openid];
        $is_login = Db::name('user')->where($where)->value('mobile');
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],!empty($is_login)?1:0);
    }

    /**
     * 查询用户信息
     */
    public function getUserInfo(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $field = isset($this->_input['field']) ? $this->_input['field'] : null;
        $map = ['avatar','nickname','credit','mobile','identity','is_verification'];
        if(empty($field)){
            return api_json(Api::PARAM_ERROR[0],'缺少请求参数');
        }
        $field = explode('|',$field);
        foreach ($field as $item){
            if(!in_array($item,$map)){
                return api_json(Api::PARAM_ERROR[0],'字段不存在');
            }
        }
        $m_User = new m_User();
        $res = $m_User->findByOpenid($openid,$field);
        if(!$res){
            return api_json(Api::SYSTEM_ERROR[0],Api::SYSTEM_ERROR[1]);
        }else{
            return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$res);
        }
    }



    /**
     * 我的课程订单
     */
        public function getMyCourseOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $m_User = new m_User();
        $UserCourse = new UserCourse();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $data = $UserCourse->getOrderByOpenid($uid,$page,$pagesize);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$data);
    }



    /**
     * 扫码获取二维码用户的课程订单
     */
    public function getMyEwmCourseOrder(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $str = isset($this->_input['str']) ? $this->_input['str'] : null;
        if(empty($str)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $decode = base64_decode($str);
        $uid = substr($decode,6,-4);
        if(!$uid){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
        $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
        $UserCourse = new UserCourse();
        $data = $UserCourse->getOrderByOpenid($uid,$page,$pagesize);
        return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$data);
    }



    /**
     * 生成二维码链接
     */
    public function qrcode(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $rule = $m_User->findByOpenid($openid,'id');
        $start = $this->rand_num(6);
        $end = $this->rand_num(4);
        $str = $start.$rule['id'].$end;
        $str =  base64_encode($str);
        $qrCode = new QrCode($str);
        $qrCode->setWriterByName('png');
        $pngData = $qrCode->writeDataUri();
       return api_json(Api::REQUEST_SUCCESS[0],Api::REQUEST_SUCCESS[1],$pngData);
    }

    /**
     * 生成随机字符串
     * @param int $len
     * @return string
     */
    final function rand_num($len=6){
       $str = array_merge(range(0,9),range('a','z'),range('A','Z'));
       shuffle($str) ;
       $num = '';
       for ($i=0;$i<$len;$i++){
           $num .= $str[mt_rand(0,count($str)-1)];
       }
        return $num;
    }

    /**
     * 核销订单 必须有核销权限的人数才可以
     */
    public function scanOrder(){
        //核实核销人身份 是否有权限核销
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $UserCourse = new UserCourse();
        $rule = $m_User->findByOpenid($openid,'is_verification,id');
        if($rule['is_verification'] !=1){
            return api_json(Api::OPENID_ERROR[0],'核销失败,权限不足');
        }
        $str = isset($this->_input['str']) ? $this->_input['str'] : null;
        $id = isset($this->_input['id']) ? $this->_input['id'] : null;
        if(empty($str)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        if(empty($id)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $decode =  base64_decode($str);
        $uid = substr($decode,6,-4);
        if(!$uid){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        //核实被核销人身份 课程是否跟被核销人匹配 是否过期 核销课程的次数是否足够
        $where = ['uid'=>$uid,'id'=>$id];
        $field = 'count,end_time,month';
        $res = $UserCourse->findByWhere($where,$field);
        $first = true;
        if(!$res){
            return api_json(Api::PARAM_ERROR[0],'核销失败,信息不符');
        }
        if(!empty($res['end_time']) && $res['end_time'] <= time() ){
            return api_json(Api::PARAM_ERROR[0],'核销失败,课程已过期');
        }
        if($res['count'] == 0 ){
            return api_json(Api::PARAM_ERROR[0],'核销失败,课程次数不足,请重新下单购买');
        }
        //判断是不是第一次核销 第一次核销要修改开始时间和结束时间
        if(!empty($res['end_time'])){
            $first = false;
        }
        //验证通过 进行核销 扣减次数 增加一条核销记录
        $res = $UserCourse->verifyCourse($rule['id'],$uid,$id,$first,$res['month']);
        return $res;
    }

    /**
     * 家庭住址编辑或修改
     */
    public function homeAddress(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $province = isset($this->_input['province']) ? $this->_input['province'] : null;
        $city = isset($this->_input['city']) ? $this->_input['city'] : null;
        $country = isset($this->_input['country']) ? $this->_input['country'] : null;
        $address = isset($this->_input['address']) ? $this->_input['address'] : null;
        $m_UserHomeAddress = new m_UserHomeAddress();
        $area = new Area;
        if(!$area->checkAreaId($province,-1) || !$area->checkAreaId($city,$province) || !$area->checkAreaId($country,$city)){
            return api_json(Api::PARAM_ERROR[0],Api::PARAM_ERROR[1]);
        }
        $area =  $area->getAreaName($province).'-'.$area->getAreaName($city).'-'.$area->getAreaName($country);
        $map = compact('uid','province','city','country','address','area');
        if($m_UserHomeAddress->findByUid($uid) == 1){
            //更新不走验证器
            $res = $m_UserHomeAddress->_insert($uid,$map);
            return $res;
        }
        //新增
        $UserHomeAddress = new UserHomeAddress();
        if (!$UserHomeAddress->check($map)) {
            return api_json(Api::PARAM_ERROR[0],$UserHomeAddress->getError());
        }
        $res = $m_UserHomeAddress->_insert($uid,$map);
        return $res;
    }

    /**
     * 意见反馈
     */
    public function feedback(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $content= isset($this->_input['content']) ? $this->_input['content'] : null;
        //新增
        $map = compact('uid','content');
        $Feedback = new Feedback();
        if (!$Feedback->check($map)) {
            return api_json(Api::PARAM_ERROR[0],$Feedback->getError());
        }
        $m_Feedback = new m_Feedback();
        $res = $m_Feedback->_insert($map);
        return $res;
    }


    /**
     * 添加修改地址
     * @return false|string
     */
     public function address(){
         $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
         if(empty($openid)){
             return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
         }
         $id= isset($this->_input['id']) ? $this->_input['id'] : 0;
         $delivery_name = isset($this->_input['delivery_name']) ? $this->_input['delivery_name'] : 0;
         $delivery_mobile = isset($this->_input['delivery_mobile']) ? $this->_input['delivery_mobile'] : 0;
         $delivery_address = isset($this->_input['delivery_address']) ? $this->_input['delivery_address'] : 0;
         $province_id = isset($this->_input['province_id']) ? $this->_input['province_id'] : 0;
         $city_id = isset($this->_input['city_id']) ? $this->_input['city_id'] : 0;
         $country_id = isset($this->_input['country_id']) ? $this->_input['country_id'] : 0;
         $is_default = isset($this->_input['is_default']) ? $this->_input['is_default'] : 0;
         $area = new Area;
         if(!$area->checkAreaId($province_id,-1) || !$area->checkAreaId($city_id,$province_id) || !$area->checkAreaId($country_id,$city_id)){
             return api_json(Api::PARAM_ERROR[0],Api::PARAM_ERROR[1]);
         }
         $delivery_area =  $area->getAreaName($province_id).'-'.$area->getAreaName($city_id).'-'.$area->getAreaName($country_id);
         $map = compact('openid','id','delivery_name','delivery_mobile','delivery_area','delivery_address','is_default','province_id','city_id','country_id');
         //新增
         $v_UserDeliver = new v_UserDeliver();
         $UserDeliver = new UserDeliver();
         if (!$v_UserDeliver->check($map)) {
             return api_json(Api::PARAM_ERROR[0],$v_UserDeliver->getError());
         }
         $res = $UserDeliver->_insert($map);
         return $res;
     }

    /**
     * 设置默认地址
     * @return false|string
     */
     public function setDefault(){
         $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
         if(empty($openid)){
             return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
         }
         $id= isset($this->_input['id']) ? $this->_input['id'] : 0;
         $UserDeliver = new UserDeliver();
         $res = $UserDeliver->setDefault($openid,$id);
         return $res;
     }

    /**
     * 删除用户地址
     * @return false|string
     */
     public function removeAddress(){
         $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
         if(empty($openid)){
             return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
         }
         $id= isset($this->_input['id']) ? $this->_input['id'] : '';
         if(empty($id) ||  strpos($id,'|') == false){
             return api_json(Api::OPENID_ERROR[0],'缺少必要参数');
         }
         $UserDeliver = new UserDeliver();
         $res = $UserDeliver->removeAddress($openid,$id);
         return $res;
     }


    /**
     * 查看指定用户的地址信息
     * @return false|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
     public function viewAddress(){
         $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
         if(empty($openid)){
             return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
         }
         $id= isset($this->_input['id']) ? $this->_input['id'] : '';
         if(empty($id)){
             return api_json(Api::OPENID_ERROR[0],'缺少必要参数');
         }
         $UserDeliver = new UserDeliver();
         $res = $UserDeliver->viewAddress($openid,$id);
         return $res;
     }

    /**
     * 查看指定用户的地址列表
     * @return false|string
     * @throws \think\Exception
     */
     public function addressList(){
         $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
         if(empty($openid)){
             return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
         }
         $UserDeliver = new UserDeliver();
         $res = $UserDeliver->addressList($openid);
         return $res;
     }


    /**
     * 获取我的消息列表
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
     public function getMyMessageList(){
         $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
         if(empty($openid)){
             return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
         }
         $page = isset($this->_input['page']) ? (int)$this->_input['page'] : 1;
         $pagesize = isset($this->_input['pagesize']) ? (int)$this->_input['pagesize'] : 10;
         $m_User = new m_User();
         $uid = $m_User->getIdByOpenid($openid);
         if(empty($uid)){
             return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
         }
         $where = ['openid'=>$openid];
         $list = Db::name('user_message')->where($where)->order('status asc')->limit(ceil($page-1)*$pagesize,$pagesize)->field('id,title,content,createtime,status')->select();
         if($list){
             foreach ($list as &$item){
                 $item['content'] = mb_substr( $item['content'],0,50);
                 $item['createtime'] =date('Y-m-d',$item['createtime']);
             }
             unset($item);
         }else{
             $list = [];
         }
         return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1],$list);
     }

    /**
     * 读取指定消息详情
     * @return false|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function getMyMessageInfo(){
        $openid = isset($this->_input['openid']) ? $this->_input['openid'] : null;
        if(empty($openid)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $id = (int)$this->_input['id'];
        if(empty($id)){
            return api_json(Api::OPENID_ERROR[0],Api::OPENID_ERROR[1]);
        }
        $m_User = new m_User();
        $uid = $m_User->getIdByOpenid($openid);
        if(empty($uid)){
            return api_json(Api::SYSTEM_ERROR[0],'用户信息查询有误');
        }
        $where = ['openid'=>$openid,'id'=>$id];
        $list = Db::name('user_message')->where($where)->field('title,content,createtime')->find();
        if($list){
            $list['createtime'] =date('Y-m-d',$list['createtime']);
            $update =['status'=>1];
            Db::name('user_message')->where($where)->update($update);
        }else{
            $list = [];
        }
        return api_json(Api::REQUEST_SUCCESS[0], Api::REQUEST_SUCCESS[1],$list);
    }
}