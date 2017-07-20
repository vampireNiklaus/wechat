<?php
/**
 * Created by PhpStorm.
 * User: qianwenweiqaq
 * Date: 2017/7/18
 * Time: 下午6:49
 */


//定义请求的URL地址
$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx4fc84724cf4f094f&secret=6324a6885bb58f3698ca408db400ed15';
//使用http_request发送请求
$str = http_request($url);
//使用json_decode对$str进行转移
$json = json_decode($str);
//输出access_token
//echo $json->access_token;
//定义一个变量保存access_token这个值
$access_token = $json->access_token;


//封装curl库
function http_request($url,$data=null){
    //第一步：创建curl
    $ch = curl_init();
    //第二步：设置curl
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);//禁止服务器端校验ssl证书
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//以文档流的形式返回数据
    //判断$data数据是否为空
    if(!empty($data)){
        //模拟发送post请求
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    //第三步：执行curl
    $optput = curl_exec($ch);
    //第四步：关闭curl
    curl_close($ch);

    //把$output当作返回值返回
    return $optput;
}
