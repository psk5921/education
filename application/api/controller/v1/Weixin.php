<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/31
 * Time: 13:51
 */

namespace app\api\controller\v1;

use think\Controller;
use think\Db;
use think\facade\Cache;
class Weixin   extends Controller
{
    /**
     * AppID 			appid
     * AppSecret 		AppSecret
     * token 			token
     * MchID 			商户号
     * key 				商户MD5密钥
     * apiclient_cert 	商户密钥文件apiclient_cert.pem路径
     * apiclient_key 	商户密钥文件apiclient_key.pem路径
     * rootca 			商户密钥文件rootca.pem路径
     * wap_name 		商户Wap名称
     */
    function __construct(){
        $this->AppID='wx3cda6d2e1bb9f6e0';
        $this->MchID="1490944902";
        $this->key="Kxusa4HKbLmA77wuLAKE3e3d7lqMxnrD";
        $this->AppSecret='38852680357ba5117d0543b1ae7c9c89';
        $this->apiclient_cert='../cert/apiclient_cert.pem';
        $this->apiclient_key='../cert/apiclient_key.pem';
        $this->courseRemindTemplateId = "6FQj8UUrZGZTtSOfu1XvSpByNAO1vo1MiT89rVh9B-k"; //上课提醒模板id
        $this->delieverRemindTemplateId = "7qn4dZIJjC3gX5K5PpZgo8T3CfG9FpiLIe8pAWfM30c"; //订单发货提醒模板id
        $this->consumeRemindTemplateId = "dRdbiaa8WgspWrNv8S4Y5kh0BaMW3VDAftIEFdzFnT4"; //课时消耗提醒模板id
    }


    /**
     * url array互转
     */
    function arrayurl($data){
        if(is_array($data)){
            $sign='';
            foreach($data as $k=>$v){
                $sign.=$k.'='.$v.'&';
            }
            return trim($sign,'&');
        }
        if(is_string($data)){
            $sign=trim($data,'&');
            $sign=explode('&',$sign);
            foreach($sign as $v){
                $cache=explode('=',$v);
                $result[$cache[0]]=$cache[1];
            }
            return $result;
        }
        return $data;
    }
    /**
     * 	一维数组转xml
     * 	$arraydata  传入的数组
     * 	$rootDom  根标签
     */
    public function arrayToXml($arraydata,$rootDom='xml'){
        if(!$rootDom){$rootDom='xml';}
        $xml = '<'.$rootDom.'>';
        foreach ($arraydata as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.='</'.$rootDom.'>';
        return $xml;
    }

    /**
     * 	生成随机字符串
     * 	$length	随机字符串
     * 	$onlyNum	是否仅数字
     */
    function getRandomString($length=4,$onlyNum=false){
        $number=range(0,9);
        $letterChar=range('A','Z');
        $lowerChar=range('a','z');
        if(!$onlyNum){
            $result=array_merge_recursive($number,$lowerChar,$letterChar);
        }else{
            $result=$number;
        }
        $maxKey=count($result)-1;
        $data='';
        for($i=1;$i<=$length;$i++){
            $key=mt_rand(0,$maxKey);
            $data.=$result[$key];
        }
        return $data;
    }
    /**
     * 获取微信小程序支付参数
     */
    function getAppletPayParams($out_trade_no,$body,$total_fee,$notify_url,$openid){
        $url='https://api.mch.weixin.qq.com/pay/unifiedorder';
        if(!$total_fee||$total_fee<0.01){
            $total_fee=1;
        }else{
            $total_fee=$total_fee*100;
        }
        $total_fee=intval($total_fee);
        $data['appid']=$this->AppID;
        $data['mch_id']=$this->MchID;
        $data['nonce_str']=$this->getRandomString(16);
        $data['body']=$body;
        $data['out_trade_no']=$out_trade_no;
        $data['total_fee']=$total_fee;
        $data['openid']=$openid;
        $data['spbill_create_ip']=$_SERVER['REMOTE_ADDR'];
        $data['notify_url']=$notify_url;
        $data['trade_type']='JSAPI';
        ksort($data);
        $signStr=$this->arrayurl($data).'&key='.$this->key;
        $data['sign']=strtoupper(md5($signStr));
        $data=$this->arrayToXml($data);
        $result=json_decode(json_encode(simplexml_load_string(@$this->curlPost($url,$data),'SimpleXMLElement', LIBXML_NOCDATA)),true);
        $data=array();
        $data['appId']=$this->AppID;
        $data['signType']='MD5';
        $data['package']='prepay_id='.$result['prepay_id'];
        $data['nonceStr']=$this->getRandomString(16);
        $data['timeStamp']=''.time().'';
        ksort($data);
        $signStr=$this->arrayurl($data).'&key='.$this->key;
        $data['paySign']=strtoupper(md5($signStr));
        return $data;
    }


    /**
     * 小程序上课提醒通知发送
     * @param $openid
     * @param $student
     * @param $time
     * @return bool|mixed
     */
    function sendCouserReminToUser($openid,$student,$time){
        if(!$openid || !$student || !$time){
            return false;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$this->getMiniProgramAccessToken();
        $data = [
            'touser' =>$openid,
            'template_id' =>$this->courseRemindTemplateId,
            'data' =>  ['thing1'=>['value'=>$student],'time2'=>['value'=>$time]],
        ];
        $res =$this->curlPost($url,json_encode($data));
        return $res;
    }

    /**
     * 小程序订单发货提醒通知发送
     * @param $openid
     * @param $goods
     * @param $express
     * @param $express_no
     * @param $receiver
     * @return bool|mixed
     */
    function sendDelieverReminToUser($openid,$goods,$express,$express_no,$receiver){
        if(!$openid || !$goods || !$express || !$express_no || !$receiver){
            return false;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$this->getMiniProgramAccessToken();
        $data = [
            'touser' =>$openid,
            'template_id' =>$this->delieverRemindTemplateId,
            'data' =>  ['thing1'=>['value'=>$goods],'thing2'=>['value'=>$express],'character_string3'=>['value'=>$express_no],'name4'=>['value'=>$receiver]],
        ];
        $res =$this->curlPost($url,json_encode($data));
        return $res;
    }


    /**
     * 小程序课时消耗提醒通知发送
     * @param $openid
     * @param $name
     * @param $date
     * @param $course
     * @return bool|mixed
     */
    function sendConsumeReminToUser($openid,$name,$date,$course){
        if(!$openid || !$name || !$date || !$course){
            return false;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$this->getMiniProgramAccessToken();
        $data = [
            'touser' =>$openid,
            'template_id' =>$this->consumeRemindTemplateId,
            'data' =>  ['name1'=>['value'=>$name],'date2'=>['value'=>$date],'thing3'=>['value'=>$course]],
        ];
        $res =$this->curlPost($url,json_encode($data));
        return $res;
    }

    /**
     * 	curl提交
     */
    function curlPost($url,$data,$header=array(),$sllkey=false){
        $curl=curl_init();
        if($header&&$header!=array()){
            curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        }
        if($sllkey){
            curl_setopt($curl,CURLOPT_SSLCERT,$this->apiclient_cert);
            curl_setopt($curl,CURLOPT_SSLKEY,$this->apiclient_key);
            /*curl_setopt($curl,CURLOPT_CAINFO,$this->rootca);*/
        }
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        $result=curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    //获取小程序access_token $isrefer 是否重置
    public function getMiniProgramAccessToken($isrefer=false){
        if( !$isrefer && ($access_token =  Cache::get('access_token')) == true){
           $json = json_decode($access_token,true);
            return $json['access_token'];
        }else{
            $url =  "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=". $this->AppID."&secret=". $this->AppSecret;
            $access_token = file_get_contents($url);
            $json = json_decode($access_token,true);
            if(json_last_error() || isset($json['errcode'])){
                return   $this->getMiniProgramAccessToken();
            }
            //$json['expires_in'] = time()+ 7000;
            Cache::set('access_token',$access_token,7000);
            return $json['access_token'];
        }

    }
}