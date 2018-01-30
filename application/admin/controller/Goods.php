<?php

namespace app\admin\controller;

use app\index\model\Gcategory;
use think\Config;
use think\Controller;
use think\Request;

class Goods extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('index');
    }



    public function getlist(){

        $where = [];
        $where['is_show']=1;
        if(isset($_GET['cate_1']) && $_GET['cate_1']){
            $where['cate_1'] = $_GET['cate_1'];
        }
        if(isset($_GET['cate_2']) && $_GET['cate_2']){
            $where['cate_2'] = $_GET['cate_2'];
        }
        if(isset($_GET['words']) && $_GET['words']){
            $where['goods_name']  = array('like','%'. $_GET['words'].'%');
        }
        if(isset($_GET['time']) && $_GET['time']){

            $goodlist=\app\index\model\Goods::where($where)->order('add_time',$_GET['time'])->select();
        }elseif(isset($_GET['price']) && $_GET['price']){

            $goodlist=\app\index\model\Goods::where($where)->order('price',$_GET['price'])->select();
        }else{

            $goodlist=\app\index\model\Goods::where($where)->order('id','desc')->select();

        }

        if(isset($goodlist)){
            foreach ($goodlist as $key=>$val){

                $goodlist[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
            }

        }

        echo json_encode(array('rows'=>$goodlist,'results'=>count($goodlist)));
    }


    // 添加商品
    public function add(){

        $pcate = Gcategory::where(array('parent_id'=>0))->select();
        if(isset($pcate)){
            $this->assign('pcate',$pcate);
        }

        if(isset($_GET['id'])){
            $user=\app\index\model\Goods::get($_GET['id']);

            $pcateson = Gcategory::where(array('parent_id'=>$user->cate_1))->select();
            $this->assign('cate_2',$pcateson);
            $this->assign('goods',$user);

        }

        return $this->fetch('add');
    }


    public function addgoods(){



        if(isset($_POST)){
           // echo '<pre>';
           // print_r($_POST);exit;


            $good_mod = new \app\index\model\Goods();
            $good_mod->goods_name = $_POST['goods_name'];
            $good_mod->price = $_POST['price'];
            $good_mod->promiseprice = $_POST['promiseprice'];
            $good_mod->is_show =  isset( $_POST['is_show'])? $_POST['is_show']:0;
            $good_mod->cate_1 = $_POST['cate_1'];
            $good_mod->cate_2 = $_POST['cate_2'];
            $good_mod->stock = $_POST['stock'];
            $good_mod->add_time =time();
            $good_mod->descrption =isset( $_POST['editorValue'])? $_POST['editorValue']:'';

            if(isset($_FILES['img']['tmp_name'])){

                if($_FILES['img']['size']>2*1024*1024){

                    $this->success('上传文件不能超过2M','/admin/goodsadd.html');
                }
                $conf=Config::get('view_replace_str');
                $path = str_replace('application/admin/controller','',__DIR__).'public'.$conf['_IMG_'].'/goods/';
                $name = 'goods_'.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).'.png';
                move_uploaded_file($_FILES["img"]["tmp_name"],$path .$name);


                $good_mod->default_image = $name;
            }

            if(isset($_POST['id'])){

                $data['goods_name'] = $_POST['goods_name'];
                $data['price']  = $_POST['price'];
                $data['promiseprice'] = $_POST['promiseprice'];
                $data['is_show']  =  isset( $_POST['is_show'])? $_POST['is_show']:0;
                $data['cate_1'] = $_POST['cate_1'];
                //$data['id'] = $_POST['id'];
                $data['cate_2']  = $_POST['cate_2'];
                $data['stock']  = $_POST['stock'];
                $data['add_time']=time();
                $data['descrption'] =isset( $_POST['editorValue'])? $_POST['editorValue']:'';
                if(isset($_FILES['img']['tmp_name'])){
                    $data['default_image']  = $name;
                }

                $good_mod->save($data,array('id'=>$_POST['id']));

                $this->success('商品编辑成功','/admin/goods.html');exit;
            }else{
                $good_mod->save();
            }



            if($good_mod->id){

                $this->success('商品上传成功','/admin/goods.html');
            }

        }else{

            $this->success('什么都没上传','/admin/goodsadd.html');
        }


    }


    public function getcateson(){
        $parent_id = $_GET['parent_id'];
        $cate=Gcategory::all(array('parent_id'=>$parent_id));
        $str = '';
        if(!empty($cate)){
            foreach ($cate as $val){
                $str.='<option value="'.$val['cate_id'].'"> '.$val['cate_name'].'</option>';
            }
        }
        echo json_encode(array('code'=>200,'msg'=>'success','html'=>$str));
    }


}
