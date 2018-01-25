<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Cart extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $this->assign('SITE_LOCATION','cart');
        return $this->fetch('index');

    }


}
