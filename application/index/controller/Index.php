<?php
namespace app\index\controller;
use app\index\model\userinfo;

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
        return $this->fetch('index');

    }

    
    public function home($id,$name){

        echo $id;
        return $this->fetch('read');
    }


}
