<?php

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Request;

class Route extends Controller
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

    public function getroute(){

        $mode = new \app\index\model\Route();
        $data=$mode->select();
        echo json_encode(array('rows'=>$data,'results'=>$mode->count()));

    }

    public function add(){
        // if

        if(isset($_GET['id'])){
            $route=\app\index\model\Route::get($_GET['id']);
            $this->assign('route',$route);
        }
        return $this->fetch('add');
    }



    public function doadd(){
        if(isset($_POST['id'])){
            $mode = new \app\index\model\Route();

            if(isset($_FILES['img']['tmp_name']) && $_FILES['img']['tmp_name']){

                if($_FILES['img']['size']>2*1024*1024){

                    $this->success('上传文件不能超过2M','/admin/goodsadd.html');
                }
                $conf=Config::get('view_replace_str');
                $path = str_replace('application/admin/controller','',__DIR__).'public'.$conf['_IMG_'].'/category/';
                $name = 'goods_'.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).'.png';
                move_uploaded_file($_FILES["img"]["tmp_name"],$path .$name);

                $data['shareimg'] = $name;
            }
            $data['title'] = $_POST['title'];
            $data['keywords'] = $_POST['keywords'];
            $data['description'] = $_POST['description'];
            $data['sharetitle'] = $_POST['sharetitle'];
            $data['sharecontent'] = $_POST['sharecontent'];
            $mode->save($data,array('id'=>$_POST['id']));

            $this->success('编辑成功','/admin/route.html');
        }


    }


}
