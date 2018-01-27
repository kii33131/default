<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Order extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if(!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])){

            $backurl = 'http://'.$_SERVER['SERVER_NAME'].'/order.html';
            $url  ='http://'.$_SERVER['SERVER_NAME'].'/login.html?backurl='.urlencode($backurl);
            header('Location:'.$url);exit;
        }

        // 查找用户选中地址
        $checkadderss=\app\index\model\Address::get(array('user_id'=>$_SESSION['user']['id'],'is_check'=>1));
        if(isset($checkadderss) && !empty($checkadderss) ){
            $this->assign('address',$checkadderss);
        }

        return $this->fetch('index');
    }


}
