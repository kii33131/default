<?php
define('WECHAT_APPID','wx82c4da90c289fea0');
define('WECHAT_APPSECRET','b0158b70a0ec317db8a77e2ddb458f21');
define('WECHAT_URL','http://www.myinterestis.com');
 include 'LaneWeChat/lanewechat.php';
 include 'LaneWeChat/wechat.php';
//exit('111');
use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;



//第一步，获取CODE1

WeChatOAuth::getCode('/index.php', 1, 'snsapi_base');

//此时页面跳转到了http://www.lanecn.com/index.php，code和state在GET参数中。

$code = $_GET['code'];

//第二步，获取access_token网页版

$openId = WeChatOAuth::getAccessTokenAndOpenId($code);

echo '<pre>';
print_r($openId);exit;

//第三步，获取用户信息

$userInfo = UserManage::getUserInfo($openId['openid']);

var_dump($userInfo);


 echo '我是首页';