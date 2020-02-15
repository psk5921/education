<?php
/**
 * Created by PhpStorm.
 * User: Rex Pan
 * Date: 2019/10/25
 * Time: 17:37
 */

namespace app\api\controller\v1;

/**
 * 小程序相关
 * Class Wechat
 * @package app\api\controller\v1
 */
class Wechat
{
    CONST  APPID = 'wx3cda6d2e1bb9f6e0';       //小小程序 appId
    CONST  APP_SECRET = '38852680357ba5117d0543b1ae7c9c89';  //小程序 appSecret
    CONST  GRANT_TYPE = 'authorization_code';  //授权类型，此处只需填写 authorization_code

    public function __construct()
    {

    }

    //登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程
    public function auth_code2Session($code)
    {
        if(empty($code)){
            return false;
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".static::APPID."&secret=".static::APP_SECRET."&js_code={$code}&grant_type=".static::GRANT_TYPE;
         $res =  $this->curlGet($url);
         $r =  json_decode($res,true);
        return $r;
    }

    /**
     * @param $encryptedData
     * @param $iv
     * @param $data
     * @param $sessionKey
     * @return mixed
     */
    public function decryptData( $encryptedData, $iv, &$data,$sessionKey )
    {
        if (strlen($sessionKey) != 24) {
            return -1;
        }
        $aesKey=base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            return -2;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return -3;
        }
        if( $dataObj->watermark->appid != static::APPID )
        {
            return -4;
        }
        $data = $result;
        return 0;
    }

    /**
     * curl get
     * @param $url
     * @return bool|mixed
     */
    final function curlGet($url)
    {
        if(empty($url)){
            return false;
        }
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $data;

    }

    /**
     * curl post
     * @param $url
     * @param $post_data
     * @return bool|mixed
     */
    final function curlPost($url,$post_data)
    {
        if(empty($url) || empty($post_data)){
            return false;
        }
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //post提交的数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
}