<?php
define('WECHAT_APPID','wx95bb93185be8dd85');
define('WECHAT_APPSECRET','345c0eb54f0034d0e1eb87ad61a4d5e4');
define('WECHAT_URL','http://47.93.235.238/');
 include 'LaneWeChat/lanewechat.php';
 include 'LaneWeChat/wechat.php';

use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;



//第一步，获取CODE

WeChatOAuth::getCode('http://47.93.235.238/index.php', 1, 'snsapi_base');

//此时页面跳转到了http://www.lanecn.com/index.php，code和state在GET参数中。

$code = $_GET['code'];

//第二步，获取access_token网页版

$openId = WeChatOAuth::getAccessTokenAndOpenId($code);

//第三步，获取用户信息

$userInfo = UserManage::getUserInfo($openId['openid']);

var_dump($userInfo);


 echo '我是首页';