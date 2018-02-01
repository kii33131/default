<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Uploadfile extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {date_default_timezone_set("Asia/Chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");
        //echo __DIR__;exit;
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(__DIR__."/php/config.json")), true);

        //var_dump($CONFIG);exit;
        $action = $_GET['action'];

        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = include("action_upload.php");
                break;

            /* 列出图片 */
            case 'listimage':
                $result = include("action_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include("action_list.php");
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = include("action_crawler.php");
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }


}
