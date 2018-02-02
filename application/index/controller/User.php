<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class User extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        if(isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){

            $this->assign('haslogin','1');
        }

        if(isset($_SESSION['user_info']) && !empty($_SESSION['user_info'])){

            $this->assign('headerimg',$_SESSION['user_info']['img_url']);
        }

        $this->assign('SITE_LOCATION','user');
        return $this->fetch('index');

    }


}
