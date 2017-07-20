<?php
/**
 * Created by PhpStorm.
 * User: qianwenweiqaq
 * Date: 2017/7/18
 * Time: 下午7:24
 */

//定义响应头信息
header('Content-type:text/html;charset=utf-8');

//载入get_token.php页面
require 'get_token.php';

//定义自定义菜单的创建窗口
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";

//定义要携带的数据
$data = '{
     "button":[
     {	
          "type":"click",
          "name":"每日推荐",
          "key":"V1001_TODAY_MUSIC"
      },
      {
           "name":"菜单",
           "sub_button":[
           {	
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
                       {	
               "type":"view",
               "name":"视频",
               "url":"http://v.qq.com/"
            },
            {	
               "type":"view",
               "name":"新闻",
               "url":"http://news.sina.com.cn/"
            },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       },
       {	
          "type":"click",
          "name":"关注赢未来",
          "key":"V1001_TODAY_MUSIC"
       }]
 }';

//发送http请求
$str = http_request($url,$data);

//使用json_decode函数进行转移
$json = json_decode($str);

//判断是否创建成功
if($json->errmsg == 'ok'){
    echo '自定义菜单创建成功！';
}else{
    echo $json->errmsg;
}
 