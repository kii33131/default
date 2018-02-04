<?php

namespace app\index\controller;

use app\index\model\Inventory;
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

        $cart_mod = new \app\index\model\Cart();

        $carted=$cart_mod->where(array('checked'=>1,'user_id'=>$_SESSION['user']['id']))->select();

        if(isset($carted) && !empty($carted)){
            $goodsmod = new \app\index\model\Goods();
            $money  = 0;
            foreach ($carted as $key=>$val){

                $goods = $goodsmod->get($val['goods_id']);
                $carted[$key]['goods_name'] = $goods['goods_name'];
                $carted[$key]['promiseprice'] = $goods['promiseprice'];
                $carted[$key]['price'] = $goods['price'];
                $carted[$key]['default_image'] = $goods['default_image'];
                if($val['checked']==1){
                    if($goods['promiseprice'] ){

                        $money+=$goods['promiseprice']*$val['num'];
                    }else{
                        $money+=$goods['price']*$val['num'];
                    }

                }
            }
            $this->assign('money',$money);
            $this->assign('cart',$carted);
            //$this->
        }else{

            return $this->success('您没有选择商品,请选择要购买的商品','/cart.html');
        }

        return $this->fetch('index');
    }


    // 提交订单
    public function suborder(){

        if(!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])){
            $backurl = 'http://'.$_SERVER['SERVER_NAME'].'/order.html';
            $url  ='http://'.$_SERVER['SERVER_NAME'].'/login.html?backurl='.urlencode($backurl);
            header('Location:'.$url);exit;
        }
        // 查找用户选中地址
        $checkadderss=\app\index\model\Address::get(array('user_id'=>$_SESSION['user']['id'],'is_check'=>1));
        if(!isset($checkadderss) || empty($checkadderss)){

            return $this->success('您没有选择送货地址,请先去选择吧','/cart.html');
        }

        $cart_mod = new \app\index\model\Cart();

        $carted=$cart_mod->where(array('checked'=>1,'user_id'=>$_SESSION['user']['id']))->select();

        if(isset($carted) && !empty($carted)){
            $goodsmod = new \app\index\model\Goods();
            $money  = 0;

            $order = new \app\index\model\Order();
            foreach ($carted as $key=>$val){
                $inventory = new Inventory();
                $goods = $goodsmod->get($val['goods_id']);
                $carted[$key]['goods_name'] = $goods['goods_name'];
                $carted[$key]['promiseprice'] = $goods['promiseprice'];
                $carted[$key]['price'] = $goods['price'];
                $carted[$key]['default_image'] = $goods['default_image'];
                if($val['checked']==1){
                    if($goods['promiseprice'] ){

                        $money+=$goods['promiseprice']*$val['num'];
                    }else{
                        $money+=$goods['price']*$val['num'];
                    }

                }

                // 添加清单表
                $inventory->order_id =0;
                $inventory->goods_id =$val['goods_id'];
                $inventory->num =$val['num'];
                $inventory->price =$goods['price'];
                $inventory->promiseprice =$goods['promiseprice'];
                $inventory->goods_name =$goods['goods_name'];
                $inventory->user_id =$_SESSION['user']['id'];
                $inventory->save();
                if($inventory->id){
                    // 删除这条购物车记录
                    \app\index\model\Cart::destroy($val['id']);
                }


            }

            // 添加订单表
            $order->user_id = $_SESSION['user']['id'];
            $order->address_id = $checkadderss->id;
            $order->money =$money;
            $order->order_number =date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            //date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $order->save();
            if($order->id){
               $invent= Inventory::where(array('order_id'=>0,'user_id'=>$_SESSION['user']['id']))->select();

               foreach ($invent as $val){

                   Inventory::where('id', $val['id'])
                       ->update(['order_id' => $order->id]);

               }

                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $result = $this->wxpayconfig($order);


               //判断是否微信
                if (strpos($user_agent, 'MicroMessenger') === false) {}else{

                    $result = $this->wxpayconfig($order);
                    //echo '<pre>';
                    //print_r($result);exit;

                    $_SESSION['coupon_wechatpay'] = base64_encode($result);


                }


                return $this->success('订单提交成功,跳转支付页面','/pay/'.$order->id.'.html');
            }else{

                return $this->success('系统错误','/order.html');
            }
           // $this->assign('money',$money);
           // $this->assign('cart',$carted);
            //$this->
        }else{

            return $this->success('您没有选择商品,请选择要购买的商品','/cart.html');
        }


    }





    public function wxpayconfig($data)
    {
        ///var/www/html/default/application/index/controller
        ///
         $path = str_replace('/index/controller','/extra/WxpayAPI/',__DIR__);

        ///if(isset($_POST))
        require_once $path."lib/WxPay.Api.php";
        require_once $path."example/WxPay.JsApiPay.php";
       // require_once "WxPay.JsApiPay.php";

        ini_set('date.timezone','Asia/Shanghai');

        $tools = new \JsApiPay();

       // echo '<pre>';
       // print_r($tools);exit;
        // 获取用户open_id
        if(!isset($_SESSION['user_info']['openid']) || empty($_SESSION['user_info']['openid'])){

            $openId= $tools->GetOpenid();


        }else{

            $openId = $_SESSION['user_info']['openid'];
        }
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("订单威信支付");
        $input->SetAttach("订单威信支付");
        $input->SetOut_trade_no( $data->order_number);//\WxPayConfig::MCHID.date("YmdHis")
        $input->SetTotal_fee($data->money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("测试商品");
        $input->SetNotify_url('http://'.$_SERVER['SERVER_NAME'].'/'."/notify/".$data->id);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $back= '/' ;
        $js = <<<PPP
            <script type="text/javascript">
            function jsApiCall()
            {
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    {$jsApiParameters},
                    function(res){
                        WeixinJSBridge.log(res.err_msg);
                        if(res.err_msg == 'get_brand_wcpay_request:ok'){
                            window.location = '{$back}';
                            return;
                        }
                        if(res.err_msg == 'get_brand_wcpay_request:cancel'){
                            alert("付款已取消")
                            window.location = '{$back}';
                            return;
                        }
                    }
                );
            }
            function callpay()
            {
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    jsApiCall();
                }
            }
            callpay();
            </script>
PPP;
        return $js;



    }

    // wx支付回调
    public function notify($id){


    }


    //订单列表
    public function orderlist(){

        if(!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])){

            $backurl = 'http://'.$_SERVER['SERVER_NAME'].'/orderlist.html';
            $url  ='http://'.$_SERVER['SERVER_NAME'].'/login.html?backurl='.urlencode($backurl);
            header('Location:'.$url);exit;
        }


        if(!isset($_GET['page'])){

            $_GET['page'] =1;
        }

        $where = array();
        $where['user_id'] =  $_SESSION['user']['id'];
        $list=\app\index\model\Order::where($where)->limit(50)->page($_GET['page'])->order('id','DESC')->select();
        $in_mod = new Inventory();
        $g_mod = new \app\index\model\Goods();
        if(isset($list)){
            foreach ($list as $key=>$val){

                $goods=$in_mod->where(array('order_id'=>$val['id']))->select();
                if(isset($goods)){

                    foreach ($goods as $l=>$van){
                        $g=$g_mod->get($van['goods_id']);
                        $goods[$l]['img'] = $g['default_image'];
                    }
                }

                $list[$key]['goods'] =  $goods;

                // $list[$key]['goods'] =

            }

            $this->assign('list',$list);
        }
        return $this->fetch('orderlist');
    }



}
