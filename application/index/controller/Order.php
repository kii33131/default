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


    //订单列表
    public function orderlist(){
        if(!isset($_GET['page'])){

            $_GET['page'] =1;
        }

        $where = array();
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
