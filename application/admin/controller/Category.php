<?php

namespace app\admin\controller;

use app\index\model\Gcategory;
use think\Config;
use think\Controller;
use think\Request;

class Category extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $category=Gcategory::where(array())->select();
        $cate=$this->tree($category,0);
        $this->assign('cate',$cate);
        return $this->fetch('index');
    }


    public function tree($a,$pid){

        $tree = array();                                //每次都声明一个新数组用来放子元素
        foreach($a as $v){
            if($v['parent_id'] == $pid){                      //匹配子记录
                $v['children'] = $this->tree($a,$v['cate_id']); //递归获取子记录
                if($v['children'] == null){
                    unset($v['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }
                $tree[] = $v;                           //将记录存入新数组
            }
        }
        return $tree;                                  //返回新数组

    }


    public function cateson(){

       $parent_id = $_GET['parent_id'];
      //  echo '1';exit;
       if(isset($parent_id)){
           $category=Gcategory::where(array('parent_id'=>$parent_id))->select();
           //dump($category);exit;
           if(isset($category)){

                    $str ='';
                 foreach ($category as $val){
                     $str .= '<tr  >';
                     $str .='<td class="bui-grid-hd1">'.$val['cate_name'].'</td>';

                     if($val['cate_img']){
                         $str .='<td class="bui-grid-hd1">有</td>';
                     }else{
                         $str .='<td class="bui-grid-hd1">没有</td>';
                     }

                     if($val['if_show']){
                         $str .='<td class="bui-grid-hd1">是</td>';
                     }else{
                         $str .='<td class="bui-grid-hd1">否</td>';
                     }
                     $str .=' <td class="bui-grid-hd1"><a href="/admin/addcategory.html?cate_id='.$val['cate_id'].'">编辑</a></td>';
                     $str.='</tr>';

                 }



                 //$this->assign('');
               echo json_encode(array('code'=>'200','html'=>$str));

           }


       }

    }


    public function addcategory(){

        $category_mod  = new Gcategory();
        if(isset( $_GET['pid'])){
            $this->assign('pid',$_GET['pid']);
        }

        if(isset($_GET['cate_id'])){
           $cate= $category_mod->get($_GET['cate_id']);
            $this->assign('cate',$cate);
        }

        return $this->fetch('add');
    }


    public function doaddcate(){
        $category_mod  = new Gcategory();

        $category_mod->cate_name = $_POST['cate_name'];
        $category_mod->parent_id = isset($_POST['parent_id'])?$_POST['parent_id']:0;
        $category_mod->if_show = isset($_POST['if_show'])?$_POST['if_show']:0;;
        $category_mod->add_time = time();


        if(isset($_FILES['img']['tmp_name']) && $_FILES['img']['tmp_name']){

            if($_FILES['img']['size']>2*1024*1024){

                $this->success('上传文件不能超过2M','/admin/goodsadd.html');
            }
            $conf=Config::get('view_replace_str');
            $path = str_replace('application/admin/controller','',__DIR__).'public'.$conf['_IMG_'].'/category/';
            $name = 'goods_'.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).'.png';
            move_uploaded_file($_FILES["img"]["tmp_name"],$path .$name);

            $category_mod->cate_img = $name;
        }

        //cate_id
        if(isset($_POST['cate_id'])){

            $data['cate_name'] = $_POST['cate_name'];
            $data['parent_id']  = $_POST['parent_id'];
            $data['if_show']  =  isset($_POST['if_show'])? $_POST['if_show']:0;
            if(isset($_FILES['img']['tmp_name'])&& $_FILES['img']['tmp_name']){
                $data['cate_img']  = $name;
            }



            $category_mod->save($data,array('cate_id'=>$_POST['cate_id']));
            //echo $good_mod->getLastSql();exit;
            $this->success('编辑成功','/admin/category.html');exit;
        }else{
            $category_mod->save();
        }


        if($category_mod->cate_id){

          $this->success('商品上传成功','/admin/category.html');
        }


    }


    public function delete(){

        $cate_id = $_GET['cate_id'];

        if(isset($cate_id)){
            $category=Gcategory::where(array('parent_id'=>$cate_id))->select();
            if($category){
                $this->success('不能删除 ,先去删除子分类吧','/admin/category.html');
                exit();
            }
           Gcategory::destroy($cate_id);

            $this->success('删除成功','/admin/category.html');
        }
    }


}
