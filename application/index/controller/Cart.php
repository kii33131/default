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
        if(!isset($_SESSION['user']['id']) && empty($_SESSION['user']['id'])){
            $backurl = 'http://'.$_SERVER['SERVER_NAME'].'/cart.html';
            $url  ='http://'.$_SERVER['SERVER_NAME'].'/login.html?backurl='.urlencode($backurl);
            header('Location:'.$url);exit;
        }
        $cartmod = New \app\index\model\Cart();
        $cart=$cartmod->select();
        if(!empty($cart)){
            $goodsmod = new \app\index\model\Goods();
            foreach ($cart as $key=>$val){

                $goods = $goodsmod->get($val['goods_id']);
                $cart[$key]['goods_name'] = $goods['goods_name'];
                $cart[$key]['promiseprice'] = $goods['promiseprice'];
                $cart[$key]['price'] = $goods['price'];
                $cart[$key]['default_image'] = $goods['default_image'];
                //default_image
            }
            $this->assign('cart',$cart);
        }
        return $this->fetch('index');

    }


    // 添加购物车
    public function addcart(){
        if(!isset($_SESSION['user']['id']) && empty($_SESSION['user']['id'])){
            $backurl = 'http://'.$_SERVER['SERVER_NAME'].'/detail/'.$_GET['goods_id'].'.html';
            $url  ='http://'.$_SERVER['SERVER_NAME'].'/login.html?backurl='.urlencode($backurl);
            echo json_encode(array('code'=>402,'msg'=>'您还没有登录,去登录吧','url'=>$url)); exit;
        }

        if(isset($_GET['goods_id'])){
            $goodsmod = new \app\index\model\Goods();
            $cartmod = New \app\index\model\Cart();
            $goods=$goodsmod->get($_GET['goods_id']);
            if($goods){
                if($goods->stock==0){
                    echo json_encode(array('code'=>400,'msg'=>'该商品卖完啦')); exit;
                }
                $carts=$cartmod->get(array('user_id'=>$_SESSION['user']['id'],'goods_id'=>$_GET['goods_id']));
                if(!empty($carts)){
                    echo json_encode(array('code'=>400,'msg'=>'您已经添加过啦,去购物车结算吧')); exit;
                }

                $cartmod->user_id = $_SESSION['user']['id'];
                $cartmod->goods_id = $_GET['goods_id'];
                $cartmod->num = 1;
                $cartmod->add_time = time();
                $cartmod->save();
                if($cartmod->id){

                    echo json_encode(array('code'=>200,'msg'=>'添加成功,去购物车结算吧')); exit;
                }else{
                    echo json_encode(array('code'=>400,'msg'=>'添加失败')); exit;
                }

            }else{
                echo json_encode(array('code'=>400,'msg'=>'没有这个商品')); exit;
            }
            //echo '<pr>';

        }else{
            echo json_encode(array('code'=>400,'msg'=>'缺少商品id')); exit;

        }


    }


    //更改购物车数量

    public function changecart(){
        $cart_id = $_GET['cart_id'];
        $num = $_GET['num'];
        if(!$cart_id || !isset($cart_id)){
            echo json_encode(array('code'=>400,'msg'=>'非法操作')); exit;
        }
        if(!$num || !isset($num)){
            echo json_encode(array('code'=>400,'msg'=>'非法操作')); exit;
        }
        $cartmod = new \app\index\model\Cart();
        $cart = $cartmod->get($cart_id);

        $goodsmod = new \app\index\model\Goods();

        $goods = $goodsmod->get($cart->goods_id);


        if($goods->stock<$num){
           // echo $goods->stock;exit;
            echo json_encode(array('code'=>400,'msg'=>'库存不足')); exit;
        }

        $cart->save(array('num'=>$num),array('id'=>$cart_id));
        echo json_encode(array('code'=>200,'msg'=>'')); exit;


    }


    // 删除购物车商品

    public function delete(){
        $cart_id = $_GET['cart_id'];
        if(!$cart_id || !isset($cart_id)){
            echo json_encode(array('code'=>400,'msg'=>'非法操作')); exit;
        }
        \app\index\model\Cart::destroy($cart_id);
        echo json_encode(array('code'=>200,'msg'=>'删除成功')); exit;
    }


}
