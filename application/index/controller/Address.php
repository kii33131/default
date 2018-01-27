<?php

namespace app\index\controller;

use app\index\model\Region;
use app\index\model\Userinfo;
use think\Controller;
use think\Request;

class Address extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function index()
    {
        if(isset($_GET['id'])){
           $addressinfo= \app\index\model\Address::get($_GET['id']);
           $sonlist=Region::all(array('parent_id'=>$addressinfo->province_id));
           $this->assign('sonlist',$sonlist);
           $this->assign('addressinfo',$addressinfo);
        }
        $address=Region::all(array('parent_id'=>2));
        $this->assign('address',$address);
        if(isset($_GET['from'])){
            $this->assign('back',$_GET['from']);
        }
        return $this->fetch('index');
    }

    public function getcity(){
        $parent_id = $_GET['parent_id'];
        $address=Region::all(array('parent_id'=>$parent_id));
        $str = '';
        if(!empty($address)){
            foreach ($address as $val){
                $str.='<option value="'.$val['region_id'].'"> '.$val['region_name'].'</option>';
            }
        }
        echo json_encode(array('code'=>200,'msg'=>'success','html'=>$str));
    }

    // 新增地址
    public function addaddress(){

       $address_mod  =  new  \app\index\model\Address();
       if(!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])){
           $backurl = 'http://'.$_SERVER['SERVER_NAME'].'/address.html';
           $url  ='http://'.$_SERVER['SERVER_NAME'].'/login.html?backurl='.urlencode($backurl);
           echo json_encode(array('code'=>404,'msg'=>'请重新登录','url'=>$url));
           exit;
       }
        $address_mod->user_id = $_SESSION['user']['id'];
        $address_mod->city_id = $_POST['city_id'];
        $address_mod->province_id = $_POST['province_id'];
        $address_mod->name = $_POST['name'];
        $address_mod->phone = $_POST['phone'];
        $address_mod->address = $_POST['address'];
        $address_mod->is_check = $_POST['is_check'];
        //is_check
       if(!$_POST['id']){
           $where['user_id'] = $_SESSION['user']['id'];

               $list=$address_mod->where($where)->select();
               if(!empty($list)){
                   foreach ($list as $val){
                       \app\index\model\Address::where('id', $val['id'])
                           ->update(['is_check' => 0]);
                   }
               }

           $address_mod->save();
       }else{
           $where['id'] = array("neq",$_POST['id']);
           $where['user_id'] = $_SESSION['user']['id'];
           if($address_mod->is_check==1){
               $list=$address_mod->where($where)->select();
               if(!empty($list)){
                   foreach ($list as $val){
                       \app\index\model\Address::where('id', $val['id'])
                           ->update(['is_check' => 0]);
                   }
               }
           }
           $address_mod->save(array('id'=>$_POST['id']),$address_mod);
       }
       if($address_mod->id){

           echo json_encode(array('code'=>200,'msg'=>'add success')); exit;
       }else{

           echo json_encode(array('code'=>400,'msg'=>'add error'));exit;
       }


    }

    //地址列表

    public function addresslist(){
        $address_mod  =  new  \app\index\model\Address();
        $list= $address_mod
            ->order('id', 'desc')
            ->select();

        $this->assign('list',$list);
        return $this->fetch('addresslist');

    }



}
