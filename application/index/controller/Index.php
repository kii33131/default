<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use LaneWeChat\Core\WeChatOAuth;

use LaneWeChat\Core\UserManage;
class Index extends  Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            $_SESSION['user_info'] ='普通访问';
        }else{
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

            }

        }

    }

    public function index()
    {
        echo '<pre>';
        print_r($_SESSION['user_info']);exit;

      //  return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }


    public function read($id,$name){

        echo $id.'_'.$name;

        //var_dump($_GET);
        //var_dump( $_GET['id']);
       // return '11';
        //$this->assign();
        return $this->fetch('read');
    }

    public function editl($id){
        echo $id;
        //return $id;
        return $this->fetch('editl');
    }

    public function detail(){

        return $this->fetch('detail');

    }

}
