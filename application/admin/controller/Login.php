<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\Request;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('index');
    }

    // 登录操作
    public function dologin(){
        $name  = $_POST['name'];
        $password = $_POST['password'];
       // echo $password;exit;
        $admin_mod = new Admin();
        $user=$admin_mod->get(array('name'=>$name,'password'=>md5($password)));
        if(isset($user) && !empty($user)){

            $_SESSION['admin']['id'] = $user->id;
            $_SESSION['admin']['name'] = $user->name;

            echo json_encode(array('code'=>200,'msg'=>'success'));

        }else{
            echo json_encode(array('code'=>400,'msg'=>'用户名或密码错误'));
        }

    }


    public function logout(){

        unset($_SESSION['admin']);
        $url  ='http://'.$_SERVER['SERVER_NAME'].'/admin/login.html';
        header('Location:'.$url);exit;
    }


}
