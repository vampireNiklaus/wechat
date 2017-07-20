<?php
/**
 * wechat php test
 */

//define your token
define("TOKEN", "weixin");


class wechatCallbackapiTest
{
    public function valid(){
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if(!empty($postStr)){

            $postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //file_put_contents('log.txt',json_encode($postStr));
            $fromUsername = $postObj->FromUserName;
            $keyword      = trim($postObj->Content);
            $toUsername   = $postObj->ToUserName;
            $time         = time();

            //接收MsgType节点并判断其值
                switch($postObj->MsgType) {
                    case 'text':
                        if($keyword == '图片'){
                            //file_put_contents('log.txt',$keyword);
                            //定义相关变量
                            $msgType = 'image';
                            //定义mediaid
                            $mediaid = 'wgH4LJ1X-7R-jadnJDEEh30idjK5OOq4Ra5ctbky3XAXbGAFqUHCiivk_9MjJved';
                            //使用sprintf函数格式化xml文档
                            $resultStr  = sprintf($this->getTemplate()['image'], $fromUsername, $toUsername, $time, $msgType, $mediaid);
                            //返回格式化后的xml数据
                            //file_put_contents('json.txt',json_encode($resultStr));
                            echo $resultStr;
                        }elseif($keyword == '音乐'){
                            //定义相关变量
                            $msgType = 'music';
                            //定义与音乐相关的变量信息
                            $title = 'Where is the love';
                            $description = 'Josh Vietti-Where is the love';
                            $url = 'https://wechat.qianwenwei.com/music.mp3';
                            $hurl = 'https://wechat.qianwenwei.com/music.mp3';
                            $resultStr  = sprintf($this->getTemplate()['music'], $fromUsername, $toUsername, $time, $msgType, $title,$description,$url,$hurl);
                            echo $resultStr;
                        }elseif($keyword == '单图文'){
                            //定义相关变量
                            $msgType = 'news';
                            $count = 1;
                            $str = '<item>
                                    <Title><![CDATA[信我的！千万别学编程！]]></Title> 
                                    <Description><![CDATA[曾经我也是个长发飘飘的汉子！]]></Description>
                                    <PicUrl><![CDATA[https://wechat.qianwenwei.com/images/study.jpg]]></PicUrl>
                                    <Url><![CDATA[https://wechat.qianwenwei.com]]></Url>
                                    </item>';
                            $resultStr  = sprintf($this->getTemplate()['news'], $fromUsername, $toUsername, $time, $msgType, $count,$str);
                            echo $resultStr;
                        }elseif($keyword == '多图文'){
                            //定义相关变量
                            $msgType = 'news';
                            $count = 2;
                            $str = '<item>
                                    <Title><![CDATA[我就是来搞笑的！你来打我啊！]]></Title> 
                                    <Description><![CDATA[你还在担心隔壁老王吗？送你神图辟邪！不用谢我！关注一波就好了。。。]]></Description>
                                    <PicUrl><![CDATA[https://wechat.qianwenwei.com/images/green.jpg]]></PicUrl>
                                    <Url><![CDATA[https://wechat.qianwenwei.com]]></Url>
                                    </item>
                                    <item>
                                    <Title><![CDATA[你知道现在走在杭州的路上是什么感觉吗？]]></Title> 
                                    <Description><![CDATA[你知道现在走在杭州的路上是什么感觉吗？是绝望。。。]]></Description>
                                    <PicUrl><![CDATA[https://wechat.qianwenwei.com/images/hot.jpg]]></PicUrl>
                                    <Url><![CDATA[https://wechat.qianwenwei.com]]></Url>
                                    </item>';
                            $resultStr  = sprintf($this->getTemplate()['news'], $fromUsername, $toUsername, $time, $msgType, $count,$str);
                            echo $resultStr;
                        }
                    break;
                    /*case 'image':
                        $msgType    = 'text';
                        $contentStr = '您发送的是图片信息！';
                        $resultStr  = sprintf($this->getTemplate()['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    break;*/
                    case 'voice':
                        //接受微信语音是被结果
                        $rec = $postObj->Recognition;
                        $msgType    = 'text';
                        $contentStr = "语音识别为:{$rec}";
                        $resultStr  = sprintf($this->getTemplate()['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    break;
                    case 'event':
                        if($postObj->Event == 'subscribe') {
                            $msgType = 'text';
                            $contentStr = '感谢您的关注！长得这么可爱还关注我的不多了！么么哒！';
                            $resultStr  = sprintf($this->getTemplate()['text'], $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            echo $resultStr;
                        }

                        if($postObj->Event == 'CLICK' && $postObj->EventKey == 'V1001_TODAY_MUSIC'){
                            //定义相关变量
                            $msgType = 'music';
                            //定义与音乐相关的变量信息
                            $title = 'Where is the love';
                            $description = 'Josh Vietti-Where is the love';
                            $url = 'http://wechat.qianwenwei.com/music.mp3';
                            $hurl = 'http://wechat.qianwenwei.com/music.mp3';
                            $resultStr  = sprintf($this->getTemplate()['music'], $fromUsername, $toUsername, $time, $msgType, $title,$description,$url,$hurl);
                            echo $resultStr;
                        }
                    break;
                }

        } else{
            echo "";
            exit;
        }
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return TRUE;
        } else{
            return FALSE;
        }
    }


    private function getTemplate(){

            //定义一个模板数组
            $tmp_arr = array(
                'text' => <<<EOT
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>
EOT
,
                'image' => <<<EOT
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Image>
            <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            </xml>
EOT
,
                'music' => <<<EOT
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Music>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <MusicUrl><![CDATA[%s]]></MusicUrl>
            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
            </Music>
            </xml>
EOT
,
                'news' => <<<EOT
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <ArticleCount>%s</ArticleCount>
            <Articles>
            %s
            </Articles>
            </xml>
EOT


            );
            return $tmp_arr;
    }

}

$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
//开启自动回复
$wechatObj->responseMsg();
