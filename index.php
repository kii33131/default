<?php
define('WECHAT_APPID','wx95bb93185be8dd85');
define('WECHAT_APPSECRET','345c0eb54f0034d0e1eb87ad61a4d5e4');
define('WECHAT_URL','');
//1. 将timestamp , nonce , token 按照字典排序
$timestamp = $_GET['timestamp'];
$nonce = $_GET['nonce'];
$token = "jiangxi123";
$signature = $_GET['signature'];
$array = array($timestamp,$nonce,$token);
sort($array);

//2.将排序后的三个参数拼接后用sha1加密
$tmpstr = implode('',$array);
$tmpstr = sha1($tmpstr);

//3. 将加密后的字符串与 signature 进行对比, 判断该请求是否来自微信
if($tmpstr == $signature) {
    echo $_GET['echostr'];
    exit;
}

 include 'LaneWeChat/lanewechat.php';
 echo '我是首页';