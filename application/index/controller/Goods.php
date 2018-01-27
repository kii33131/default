<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Goods extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($cate_1,$cate_2)
    {
        //
        if(isset($cate_1) && $cate_1){
            $where['cate_1'] = $cate_1;
        }
        if(isset($cate_2) && $cate_2){
            $where['cate_2'] = $cate_2;
        }
        $goodlist=\app\index\model\Goods::where($where)->limit(0,10)->order('id', 'desc')->select();
        $this->assign('goodlist',$goodlist);
        $this->assign('cate_1',$cate_1);
        $this->assign('cate_2',$cate_2);
        return $this->fetch('index');


    }


    public function moregoods(){
        $where = [];
        if(isset($_GET['cate_1']) && $_GET['cate_1']){
            $where['cate_1'] = $_GET['cate_1'];
        }
        if(isset($_GET['cate_2']) && $_GET['cate_2']){
            $where['cate_2'] = $_GET['cate_2'];
        }
        $order=  "'".'id'."'".','."'".'desc'."'";
        if(isset($_GET['time']) && $_GET['time']){

            $order=  "'".'add_time'."'".','."'".$_GET['add_time']."'";
        }

        if(isset($_GET['price']) && $_GET['price']){
            $order=  "'".'price'."'".','."'".$_GET['price']."'";
        }
        $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order($order)->select();
        $html = '';
        if(!empty($goodlist)){


            foreach ($goodlist as $val){
                $url = 'aa';
                $html .= '<div class="yx-scrollgl-item"><a href="'.$url.'">';
                $html .= ' <div class="yxscroll-iimg"><img src="'.$val['default_image'] .'" alt=""></div>';
                $html .= ' <div class="yxscroll-iname">'.$val['goods_name'].'</div>';
                $html .= ' <div class="yxscroll-iprice">';

                if($val['promiseprice']){

                    $html .= ' <del>'.$val['price'].'</del> <span><em>￥</em>'.$val['promiseprice'].'</span>';
                }else{
                    $html .= ' <span><em>￥</em>'.$val['price'].'</span>';

                }

                $html .= '  </div> </a></div>';
            }



        }

        echo json_encode(array('code'=>200,'msg'=>'success','html'=> $html));


    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
