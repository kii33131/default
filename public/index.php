<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
include '../LaneWeChat/lanewechat.php';
/**
 * 自定义菜单11
 */
//设置菜单

if (strpos($user_agent, 'MicroMessenger') === false) {

}else{
    $menuList = array(
        array('id'=>'1', 'pid'=>'',  'name'=>'常规', 'type'=>'', 'code'=>'key_1'),
        array('id'=>'2', 'pid'=>'1',  'name'=>'点击', 'type'=>'click', 'code'=>'key_2'),
        array('id'=>'3', 'pid'=>'1',  'name'=>'浏览', 'type'=>'view', 'code'=>'http://www.myinterestis.com/'),

    );

    \LaneWeChat\Core\Menu::setMenu($menuList);
//获取菜单
    \LaneWeChat\Core\Menu::getMenu();


}

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件

require __DIR__ . '/../thinkphp/start.php';
