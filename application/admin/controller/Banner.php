<?php

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Request;

class Banner extends Controller
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
        //


        if(isset($_GET['id'])){

           $banner= \app\admin\model\Banner::get($_GET['id']);
           $this->assign('banner',$banner);
        }

        return $this->fetch('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function dobanneradd()
    {
        $banner  = new \app\admin\model\Banner();

        $banner->name = $_POST['name'];
        $banner->url = isset($_POST['url'])?$_POST['url']:0;
        $banner->if_show = isset($_POST['if_show'])?$_POST['if_show']:0;;
        $banner->add_time = time();


        if(isset($_FILES['img']['tmp_name']) && $_FILES['img']['tmp_name']){

            if($_FILES['img']['size']>2*1024*1024){

                $this->success('上传文件不能超过2M','/admin/goodsadd.html');
            }
            $conf=Config::get('view_replace_str');
            $path = str_replace('application/admin/controller','',__DIR__).'public'.$conf['_IMG_'].'/category/';
            $name = 'goods_'.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).'.png';
            move_uploaded_file($_FILES["img"]["tmp_name"],$path .$name);

            $banner->img = $name;
        }

        //cate_id
        if(isset($_POST['id'])){

            $data['name'] = $_POST['name'];
            $data['url']  = $_POST['url'];
            $data['if_show']  =  isset($_POST['if_show'])? $_POST['if_show']:0;
            if(isset($_FILES['img']['tmp_name'])&& $_FILES['img']['tmp_name']){
                $data['img']  = $name;
            }



            $banner->save($data,array('id'=>$_POST['id']));
            //echo $good_mod->getLastSql();exit;
            $this->success('编辑成功','/admin/banner.html');exit;
        }else{
            $banner->save();
        }


        if($banner->id){

            $this->success('轮播图添加成功','/admin/banner.html');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function getbanner()
    {
       $mod = new \app\admin\model\Banner();
       $banner= \app\admin\model\Banner::select();

        if(isset($banner)){
            foreach ($banner as $key=>$val){

                $banner[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
            }

        }
        echo json_encode(array('rows'=>$banner,'results'=>$mod->count()));


    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function bannerdelete()
    {
        if(isset($_GET['id'])){

            //echo $_GET['id'];exit;
            \app\admin\model\Banner::destroy($_GET['id']);
            $this->success('删除成功','/admin/banner.html');
        }

    }
}
