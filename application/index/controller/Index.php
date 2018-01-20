<?php
namespace app\index\controller;
use app\index\model\userinfo;
use think\Controller;
use think\Request;
use LaneWeChat\Core\WeChatOAuth;
use LaneWeChat\Core\UserManage;
class Index extends  Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            $_SESSION['user_info'] =0;
        }else{
            if(!$_SESSION['user_info'] ){

                if($_GET['code']){
                    $code = $_GET['code'];

                    //第二步，获取access_token网页版
                    $openId = WeChatOAuth::getAccessTokenAndOpenId($code);
                    $userInfo = UserManage::getUserInfo($openId['openid']);

                    $_SESSION['user_info'] = $userInfo;
                    //echo '<pre>';
                    //print_r($userInfo);exit;
                }else{
                    WeChatOAuth::getCode('/index.php', 1, 'snsapi_base');
                }

            }

        }

    }

    public function index()
    {

        if($_SESSION['user_info']){

            $data = array(
                'open_id'=>$_SESSION['user_info']['openid'],
                'img_url'=>$_SESSION['user_info']['headimgurl'],
                'subscribe'=>$_SESSION['user_info']['subscribe'],
                'nickname'=>$_SESSION['user_info']['nickname']
            );

            $re=Userinfo::get(array('open_id'=>$data['open_id']));
            //$id = 0;
            if($re){
              $re->open_id = $data['open_id'];
              $re->img_url = $data['img_url'];
              $re->subscribe = $data['subscribe'];
              $re->nickname = $data['nickname'];
              $id =$re->save();

            }else{
                $userinfo = new Userinfo;
                $userinfo->open_id = $data['open_id'];
                $userinfo->img_url = $data['img_url'];
                $userinfo->subscribe = $data['subscribe'];
                $userinfo->nickname = $data['nickname'];
                $userinfo->save();
            }

            echo $id;
        }



    }


    public function read($id,$name){

        echo $id.'_'.$name;

        //var_dump($_GET);
        //var_dump( $_GET['id']);
       // return '11';
        //$this->assign();
        return $this->fetch('read');
    }

    public function editl($id){
        echo $id;
        //return $id;
        return $this->fetch('editl');
    }

    public function detail(){

        return $this->fetch('detail');

    }

}
