<?php

namespace app\index\controller;

use think\Config;
use think\Controller;
use think\Request;
use app\index\model\userinfo;
use LaneWeChat\Core\WeChatOAuth;
class Base extends Controller
{
   public function __construct(Request $request = null)
   {
       parent::__construct($request);

       $user_agent = $_SERVER['HTTP_USER_AGENT'];
       if (strpos($user_agent, 'MicroMessenger') === false) {


       }else{

           if(!isset($_SESSION['user_info'])){

               if(isset($_GET['code'])){
                   $code = $_GET['code'];
                   //第二步，获取access_token网页版
                   $openId = WeChatOAuth::getAccessTokenAndOpenId($code);
                   $userInfo = WeChatOAuth::getUserInfo($openId['access_token'],$openId['openid']);
                   $_SESSION['user_info'] = $userInfo;

               }else{
                   WeChatOAuth::getCode('/index.php', 1, 'snsapi_userinfo');
               }

           }

           $this->assign('isweixin','1');

       }

       if(isset( $_SESSION['user_info'])){
           $data = array(
               'open_id'=>$_SESSION['user_info']['openid'],
               'img_url'=>$_SESSION['user_info']['headimgurl'],
               //'subscribe'=>$_SESSION['user_info']['subscribe'],
               'nickname'=>$_SESSION['user_info']['nickname']
           );
           $re=Userinfo::get(array('open_id'=>$data['open_id']));
           if($re){
               $re->open_id = $data['open_id'];
               $re->img_url = $data['img_url'];
               $re->subscribe = $data['subscribe'];
               $re->nickname = $data['nickname'];
               $re->save();

           }else{
               $userinfo = new Userinfo;
               $userinfo->open_id = $data['open_id'];
               $userinfo->img_url = $data['img_url'];
               //$userinfo->subscribe = $data['subscribe'];
               $userinfo->nickname = $data['nickname'];
               $userinfo->save();
           }
       }


       $wxconfig=Config::get('wx');
       $this->assign('WECHAT_APPID',$wxconfig['WECHAT_APPID']);
       if(isset($_GET["signature"])){
           $this->assign('signature ',$_GET["signature"]);
           $this->assign('timestamp ',$_GET["timestamp"]);
           $this->assign('nonceStr ',$_GET["nonce"]);

       }

   }


}