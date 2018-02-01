<?php
namespace app\index\controller;
use app\admin\model\Banner;
use app\index\model\Gcategory;
use app\index\model\Userinfo;

use think\Request;
class Index extends  Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);

    }

    public function index()
    {
        if(isset($_SESSION['user_info'])){

            $re=Userinfo::get(array('open_id'=>$_SESSION['user_info']['openid']));
            $this->assign('name',$re->nickname);
            $this->assign('img_url',$re->img_url);

        }

        $banner= Banner::where(array('if_show'=>1))->select();
        $this->assign('banner',$banner);
        $this->assign('SITE_LOCATION','default_index');
        return $this->fetch('index');

    }


    public function category(){
        $this->assign('SITE_LOCATION','category_index');
        $category=Gcategory::where(array('if_show'=>1))->select();
        $cate=$this->tree($category,0);
        $this->assign('cate',$cate);
        return $this->fetch('category');
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


    public function introduce(){

        $this->assign('SITE_LOCATION','introduce');
        return $this->fetch('introduce');
    }

    public function home($id,$name){

        echo $id;
        return $this->fetch('read');
    }





}
