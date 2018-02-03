<?php

namespace app\admin\controller;

use app\index\model\Userinfo;
use think\Controller;
use think\Request;

class Order extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function orderlist()
    {
        //
        if(!isset($_GET['pageIndex'] ) || !$_GET['pageIndex']){
            $_GET['pageIndex'] =1;
        }else{
            $_GET['pageIndex'] =  $_GET['pageIndex']+1;
        }
        $where = array();
        if(isset($_GET['order_number']) && $_GET['order_number']){
            $where['order_number'] = $_GET['order_number'];
        }

        if(isset($_GET['ispay']) && $_GET['ispay']){
            $where['ispay'] = $_GET['ispay'];
        }
        if(isset($_GET['status']) && $_GET['status']){
            $where['status'] = $_GET['status'];
        }
        //
        $order= \app\index\model\Order::where($where)->limit(20)->page( $_GET['pageIndex'])->order('id','desc')->select();
        $user_mod = New Userinfo();
        $mode = new \app\index\model\Order();
        if(isset($order)) {
            foreach ($order as $key => $val) {
                $user = $user_mod->get($val['user_id']);
                if (isset($user)) {
                    $order[$key]['phone'] = $user->phone;
                    $order[$key]['nickname'] = $user->nickname;
                    $order[$key]['open_id'] = $user->open_id;

                }

            }
        }

        echo json_encode(array('rows'=>$order,'results'=>$mode->count()));

    }


    public function index(){

        return $this->fetch('index');
    }


}
