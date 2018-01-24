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
            // 没有注册并且登录
            $userid=$this->regitser($_POST);
            $info=userinfo::get($userid);
            $_SESSION['user'] = $info;
            echo  json_encode(array('code'=>200,'msg'=>'success','data'=>'登录成功'));exit;

        }else{
            //有的话比对密码登录

           $bool=$this->login($_POST,$info->password);
           if($bool){

               $_SESSION['user'] = $info;

               echo  json_encode(array('code'=>200,'msg'=>'success','data'=>'登录成功'));exit;
           }else{
               echo  json_encode(array('code'=>400,'msg'=>'登录失败','data'=>'登录成功'));exit;
           }




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
           // echo  json_encode(array('code'=>200,'msg'=>'succs','data'=>$userinfo->id));exit;
            return $userinfo->id;

        }else{
            //微信注册
           if(isset($_SESSION['user_info']['openid']) && !empty($_SESSION['user_info']['openid'])){
              $user=userinfo::get(array('open_id'=>$_SESSION['user_info']['openid']));
              if($user){
                  $user->save($cc,array('open_id'=>$_SESSION['user_info']['openid']));
                  return $user->id;
                //  echo  json_encode(array('code'=>200,'msg'=>'succs','data'=>$user->id));exit;
              }

           }


        }


    }


    public function login($data,$password){

        $cc['phone'] = $data['phone'];
        $cc['password'] = md5($data['password']);
        if($password!=md5($data['password'])){
            echo  json_encode(array('code'=>400,'msg'=>'密码输入有误'));exit;
        }
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {}else{

            //在微信里面
            if(isset($_SESSION['user_info']['openid']) && !empty($_SESSION['user_info']['openid'])){
                $user=userinfo::get(array('open_id'=>$_SESSION['user_info']['openid']));
                if($user){

                    // 执行微信登录操作
                    $user->save($cc,array('open_id'=>$_SESSION['user_info']['openid']));

                    $old=userinfo::get(array('phone'=> $cc['phone'],'open_id'=>'','nickname'=>''));
                    if($old){
                        // 先同步订单 购物车 等信息到新用户上面

                        //最后删除吊老用户
                        userinfo::destroy($old->id);

                    }

                    return $user->id;
                    //  echo  json_encode(array('code'=>200,'msg'=>'succs','data'=>$user->id));exit;
                }

            }


        }

        return true;
    }

}
