<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if(!function_exists('pkcs5_pad')){
    /**
     * 字符串填充
     * @param $text
     * @param $blocksize
     * @return string
     */
    function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}

if(!function_exists('urlsafe_b64encode')){
    /**
     * urlbase64参数安全编码
     * @param $string
     * @return mixed|string
     */
    function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_','.'),$data);
        return $data;
    }
}

if(!function_exists('urlsafe_b64decode')){
    /**
     * urlbase64参数安全解码
     * @param $string
     * @return mixed|string
     */
    function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-','_','.'),array('+','/','='),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}


if(!function_exists('diy_json')){
    /**
     * 自定义json 响应
     * @param $datas
     * @param $codes
     * @param $msgs
     * @return array
     */
    function diy_json($datas,$codes,$msgs)
    {
        $data = $datas;
        $code = $codes;
        $msg = $msgs;
        return compact('data','code','msg');
    }
}


if(!function_exists('memory_usage')){
    /**
     * 查看已使用内存
     * @return string
     */
    function memory_usage() {
        $memory     = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'M';
        return $memory;
    }
}

if(!function_exists('is_http')){
    /**
     * 判断http 还是https
     * @return string
     */
    function is_http() {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type;
    }
}




if(!function_exists('layui_json')){
    /**
     * @param $codes  //解析接口状态
     * @param $msgs  //解析提示文本
     * @param $counts  //解析数据长度
     * @param $datas  //解析数据列表
     * @return array
     */
    function layui_json($codes,$msgs,$counts,$datas) {
        $data = $datas;
        $code = $codes;
        $msg = $msgs;
        $count = $counts;
        return compact('code','msg','count','data');
    }
}


if(!function_exists('tp5_where_str')){
    /**
     * tp5 where 构造
     * @param $key
     * @param $exp
     * @param $value
     * @return array
     */
    function tp5_where_str($key,$exp,$value) {
        $data = [(string)$key,(string)$exp,$value];
        return $data;
    }
}

if(!function_exists('RemoveXSS')){

    /**
     * 防止xss代码攻击
     * @param $val
     * @return string|string[]|null
     */
    function RemoveXSS($val) {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@avascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

            // @ @ search for the hex values
            $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }
        return $val;
    }
}

/**
 * 返回json 数据格式
 */
if(!function_exists('api_json')){
    function  api_json($code=0,$message='',$data=''){
        return json_encode(compact('code','message','data'),JSON_UNESCAPED_UNICODE);
    }
}

/**
 * 返回数组数据格式
 */
if(!function_exists('api_json_middleware')){
    function  api_json_middleware($code=0,$message='',$data=''){
        return compact('code','message','data');
    }
}

/**
 * 字符反序列化失败的处理
 *
 */
if(!function_exists('mb_unserialize')){
    function mb_unserialize($str) {
        return preg_replace_callback('#s:(\d+):"(.*?)";#s',function($match){return 's:'.strlen($match[2]).':"'.$match[2].'";';},$str);
    }
}

/**
 * 写入消息记录
 */
if(!function_exists('write_msg')){
    function write_msg($openid,$title,$content) {
      if(empty($openid) || empty($title) || empty($content)  ){
           return false;
      }
        $insert = [
           'openid' =>$openid,
           'title' =>$title,
           'content' =>$content,
           'status' =>0,
           'createtime' =>time(),
        ];
       return  \think\Db::name('user_message')->insert($insert);
    }
}





