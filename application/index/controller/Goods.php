<?php

namespace app\index\controller;

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
    public function index($cate_1,$cate_2)
    {
        //
        ///echo $_SERVER['REQUEST_URI'];exit;
        $where = [];
        if(isset($cate_1) && $cate_1){
            $where['cate_1'] = $cate_1;
        }
        if(isset($cate_2) && $cate_2){
            $where['cate_2'] = $cate_2;
        }
        if(isset($_GET['words']) && $_GET['words']){
            $where['goods_name']  = array('like', '%'. $_GET['words'].'%');

            $this->assign('words',$_GET['words']);
        }
        $goodlist=\app\index\model\Goods::where($where)->limit(0,10)->order('id', 'desc')->select();

        //$mode  = new \app\index\model\Goods();
       // echo $mode->getLastSql();exit;
        $this->assign('goodlist',$goodlist);
        $this->assign('cate_1',$cate_1);
        $this->assign('cate_2',$cate_2);
        $this->assign('url','/goods/'.$cate_1.'/'.$cate_2.'.html');
        return $this->fetch('index');


    }


    public function moregoods(){
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

            $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order('add_time',$_GET['time'])->select();
        }elseif(isset($_GET['price']) && $_GET['price']){

            $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order('price',$_GET['price'])->select();
        }else{
            $goodlist=\app\index\model\Goods::where($where)->limit(10)->page($_GET['page'])->order('id','desc')->select();

        }

        $html = '';
        if(!empty($goodlist)){

            //_IMG_/goods/{$item.default_image}

            $con=Config::get('view_replace_str');
            foreach ($goodlist as $val){
                $url = '/detail/'.$val['id'].'.html';
                $html .= '<div class="yx-scrollgl-item"><a href="'.$url.'">';
                $html .= ' <div class="yxscroll-iimg"><img src="'.$con['_IMG_'].'/goods/'.$val['default_image'] .'" alt=""></div>';
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

        $goodsmod = new \app\index\model\Goods();
        $data= $goodsmod->get($id);
        if(!empty($data)){
            $goodlist=\app\index\model\Goods::where(array('cate_1'=>$data->cate_1,'is_show'=>1))->limit(10)->order('id','desc')->select();
            if(!empty($goodlist)){
                $this->assign('goodlist',$goodlist);
            }

            $this->assign('data',$data);
        }

        return $this->fetch('detail');
    }
}
