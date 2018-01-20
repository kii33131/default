<?php
define('WECHAT_APPID','wx82c4da90c289fea0');
define('WECHAT_APPSECRET','6883f141a52032cfee6baf941b27114e');
define('WECHAT_URL','http://www.myinterestis.com');
include 'LaneWeChat/lanewechat.php';
use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;
use LaneWeChat\Core\AccessToken;
//$accessToken = AccessToken::getAccessToken();

//第一步，获取CODE1
if(!$_SESSION['user_info'] ){

    if($_GET['code']){
        $code = $_GET['code'];

        //第二步，获取access_token网页版
        $openId = WeChatOAuth::getAccessTokenAndOpenId($code);
        $userInfo = UserManage::getUserInfo($openId['openid']);

        $_SESSION['user_info'] = $userInfo;
        //echo '<pre>';
        //print_r($userInfo);exit;
    }else{
        WeChatOAuth::getCode('/index.php', 1, 'snsapi_base');
    }

}else{
    echo '<pre>';
    print_r($_SESSION['user_info']);exit;
    //echo '我是首页';
}



//此时页面跳转到了http://www.lanecn.com/index.php，code和state在GET参数中。




//第三步，获取用户信息

//$userInfo = UserManage::getUserInfo($openId['openid']);

//var_dump($userInfo);


