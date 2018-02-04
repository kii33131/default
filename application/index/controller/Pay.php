<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Pay extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($id)
    {
        //
        //$id
        $order=\app\index\model\Order::get($id);
        if(isset($order)){
            $this->assign('order',$order);
        }
        return $this->fetch('index');
    }

    public function wxpay(){
        if(isset($_SESSION['coupon_wechatpay'])){
            $this->assign("weixin_js",base64_decode($_SESSION['coupon_wechatpay']));
            unset($_SESSION['coupon_wechatpay']);
        }

        return $this->fetch('wxpay');
        //   $this->assign()
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
