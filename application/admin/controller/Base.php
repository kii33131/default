<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){

            $url  ='http://'.$_SERVER['SERVER_NAME'].'/admin/login.html';

           // echo $url;exit;
            header('Location:'.$url);exit;
        }
    }

}
