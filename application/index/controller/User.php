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
        $this->assign('SITE_LOCATION','user');
        return $this->fetch('index');

    }


}
