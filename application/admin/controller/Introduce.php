<?php

namespace app\admin\controller;

use app\index\model\Inventory;
use think\Controller;
use think\Request;

class Introduce extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

      $data= \app\index\model\Introduce::get(1);

      if(isset($data)){
          $this->assign('data',$data);
      }
      return $this->fetch('add');

    }



    public function doedit(){

       // $data= \app\index\model\Introduce::get(1);

        $mode = new \app\index\model\Introduce();

        $data['content'] = $_POST['editorValue'];

        $mode->save($data,array('id'=>1));

        $this->success('编辑成功','/admin/introduce.html');
    }


}
