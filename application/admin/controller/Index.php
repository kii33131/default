<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Index extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        if(isset($_SESSION['admin']) && !empty($_SESSION['admin']) ){

            $this->assign('admin',$_SESSION['admin']);
        }
        return $this->fetch('index');
    }


    public function floor(){

        return $this->fetch('floor');
    }

    public function header(){

        return $this->fetch('header');
    }

    public function main(){

        return $this->fetch('main');
    }


}
