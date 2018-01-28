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

        if(isset($_GET['time']) && $_GET['time']){

            $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order('add_time',$_GET['time'])->select();
        }elseif(isset($_GET['price']) && $_GET['price']){

            $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order('price',$_GET['price'])->select();
        }else{
            $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order('id','desc')->select();

        }
        $html = '';
        if(!empty($goodlist)){


            foreach ($goodlist as $val){
                $url = '/detail/'.$val['id'].'.html';
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


    // 商品详情页面
    public function detail($id){



        return $this->fetch('detail');
    }
}
