<?php

namespace app\index\controller;

use app\index\model\Gcategory;
use app\index\model\Route;
use think\Config;
use think\Controller;
use think\Request;
use app\index\model\Userinfo;
use LaneWeChat\Core\WeChatOAuth;
class Base extends Controller
{
   public function __construct(Request $request = null)
   {
       parent::__construct($request);
       $wxconfig=Config::get('wx');

      // echo __DIR__;exit;
        //REQUEST_URI
      // echo '<pre>';
       //print_r('http://'.$_SERVER['SERVER_NAME'].'/');exit;
        //echo $_SERVER['DOCUMENT_ROOT'];exit;
       $this->assign('servesddd','http://'.$_SERVER['SERVER_NAME'].'/');
       //首页路由
       $routearr = [];
       if($_SERVER['REQUEST_URI']=='/'){
           $routearr[] ='/';
       }else{
           $route  = str_replace('.html','',$_SERVER['REQUEST_URI']);
           $route = trim($route,'/');
           $routearr = explode('/',$route);
       }

       if(!empty($routearr)){
            $routes= Route::get(array('route'=>$routearr[0]));
            if($routearr[0]=='goods'){
                // 商品搜索路由特殊处理
                $cate_1 = $routearr[1];
                $cate_2 = $routearr[2];


                if($cate_1){

                    $cate1= Gcategory::get($cate_1);
                    $routes->title = $routes->title.'-'.$cate1->cate_name;
                    $routes->keywords = $routes->keywords.'-'.$cate1->cate_name;
                    $routes->description = $routes->description.'-'.$cate1->cate_name;
                }

                if($cate_2){

                    $cate_2ss= explode('?',$cate_2);
                    if($cate_2ss[0]){
                        $cate2= Gcategory::get($cate_2ss[0]);
                        $routes->title = $routes->title.'-'.$cate2->cate_name;
                        $routes->keywords = $routes->keywords.'-'.$cate2->cate_name;
                        $routes->description = $routes->description.'-'.$cate2->cate_name;
                    }


                }

               // exit('ss');

            }
            //detail 商品详情页
           if($routearr[0]=='detail'){
               // 商品详情页
               $goods_id=$routearr[1];
               $good=\app\index\model\Goods::get($goods_id);
               $routes->title = $routes->title.'-'.$good->goods_name;
               $routes->keywords = $routes->keywords.'-'.$good->goods_name;
               $routes->description = $routes->description.'-'.$good->goods_name;
           }


           if(isset($routes) && !empty($routes)){
               $this->assign('routes',$routes);
           }
       }

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

                   WeChatOAuth::getCode( $_SERVER['REQUEST_URI'], 1, 'snsapi_userinfo');
               }

           }

           $this->assign('isweixin','1');
            //获取微信分享配置
           $jssdk  = new Jssdk($wxconfig['WECHAT_APPID'],$wxconfig['WECHAT_APPSECRET']);
           $signPackage = $jssdk->getSignPackage();
           $this->assign('nonceStr',$signPackage['nonceStr']);
           $this->assign('timestamp',$signPackage['timestamp']);
           $this->assign('signature',$signPackage['signature']);
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
              // $re->subscribe = $data['subscribe'];
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



       $this->assign('WECHAT_APPID',$wxconfig['WECHAT_APPID']);


   }


}
