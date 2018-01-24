<?php

namespace app\index\controller;

use app\index\model\userinfo;
use think\Controller;
use think\Request;

class Login extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //

        return $this->fetch('login');
    }


    public function yzm(){

        return $this->fetch('yzm');
    }


    public function dologin(){

        if(!isset($_POST['phone'])||empty($_POST['phone'])){

            echo  json_encode(array('code'=>400,'msg'=>'手机号不能为空'));exit;
        }

        if(!captcha_check($_POST['yzm'])){
            echo  json_encode(array('code'=>400,'msg'=>'请填写正确的验证码'));exit;
        };

        $info=userinfo::get(array('phone'=>$_POST['phone']));

        if(!isset($info) || empty($info)){
            $this->regitser($_POST);
        }

        //$_POST['phone']

        // 判断用户有没有注册有的话执行注册逻辑 没有的话执行登录逻辑

    }


    public function regitser($data){
        $userinfo  =  new userinfo();
        $cc['phone'] = $data['phone'];
        $cc['password'] = md5($data['password']);
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            //微信外面注册
            $userinfo->creat($cc);
            echo  json_encode(array('code'=>200,'msg'=>'succs','data'=>$userinfo->id));exit;
        }else{
           if(isset($_SESSION['user_info']['openid']) && !empty($_SESSION['user_info']['openid'])){
              $user=userinfo::get(array('openid'=>$_SESSION['user_info']['openid']));
              if($user){
                  $user->save($cc,array('open_id'=>$_SESSION['user_info']['openid']));
                  echo  json_encode(array('code'=>200,'msg'=>'succs','data'=>$user->id));exit;
              }

           }


        }






    }


    public function login(){


    }

}
