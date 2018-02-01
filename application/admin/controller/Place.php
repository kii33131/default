<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Place extends Controller
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

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        if(isset($_GET['id'])){

            $place  = new \app\admin\model\Place();
            $p= $place->get($_GET['id']);
            $this->assign('place',$p);
        }
        return $this->fetch('add');
    }

    public function getplace(){
        $mod = new \app\admin\model\Place();
        $cate= \app\admin\model\Place::where(array())->order('short', 'asc')->select();
        echo json_encode(array('rows'=>$cate,'results'=>$mod->count()));
    }


    public function doplace(){

        $place  = new \app\admin\model\Place();

        $place->title = isset($_POST['title'])?$_POST['title']:'';
        $place->ftitle = isset($_POST['ftitle'])?$_POST['ftitle']:'';
        $place->short = isset($_POST['short'])?$_POST['short']:'';
        $place->url = isset($_POST['url'])?$_POST['url']:'';

        //cate_id
        if(isset($_POST['id'])){
            $data['title']=isset($_POST['title'])?$_POST['title']:'';
            $data['ftitle']=isset($_POST['ftitle'])?$_POST['ftitle']:'';
            $data['short']=isset($_POST['short'])?$_POST['short']:'';
            $data['url']=isset($_POST['url'])?$_POST['url']:'';
            $place->save($data,array('id'=>$_POST['id']));
            //echo $good_mod->getLastSql();exit;
            $this->success('编辑成功','/admin/place.html');exit;
        }else{
            $place->save();
        }


        if($place->id){

            $this->success('添加成功','/admin/place.html');
        }

    }


    public function placedelete(){

        if(isset($_GET['id'])){

            //echo $_GET['id'];exit;
            \app\admin\model\Place::destroy($_GET['id']);

            $this->success('删除成功','/admin/place.html');
        }
    }


    public function guanliangoods(){
        if(isset($_GET['id'])){

            $this->assign('id',$_GET['id']);
        }

        return $this->fetch('guanliangoods');
    }
}
