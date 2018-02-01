<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
/*
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

]; */

use think\Route;
//Route::get('read/:id/:name','index/Index/read');

Route::group(['method' => 'get', 'ext' => 'html'], function () {
    Route::get('home/:id/:name','index/Index/home');
    Route::get('category','index/Index/category');
    Route::get('introduce','index/Index/introduce');
    Route::get('cart','index/Cart/index');
    Route::get('order','index/Order/index');
    Route::get('address','index/Address/index');
    Route::get('addresslist','index/Address/addresslist');
    Route::get('goods/:cate_1/:cate_2/','index/Goods/index');
    Route::get('detail/:id','index/Goods/detail');

    Route::get('pay/:id','index/Pay/index');
    //detail
    Route::get('detail','index/Index/detail');
    Route::get('editl/:id','index/Index/editl');
    Route::get('common/header','index/common/header');
    Route::get('common/floor','index/common/floor');
    Route::get('common/top','index/common/top');
    Route::get('common/othertop','index/common/othertop');
    Route::get('common/search_front','index/common/search_front');
    Route::get('login','index/login/index');
    Route::get('yzm','index/login/yzm');

});

Route::group(['method' => 'get', 'ext' => 'html'], function () {
    Route::get('admin/index','admin/Index/index');
    Route::get('admin/main','admin/Index/main');
    Route::get('admin/login','admin/Login/index');
    Route::get('admin/floor','admin/Index/floor');
    Route::get('admin/header','admin/Index/header');
    Route::get('admin/goods','admin/Goods/index');
    Route::get('admin/goodsadd','admin/Goods/add');
    Route::get('admin/category','admin/Category/index');
    Route::get('admin/addcategory','admin/Category/addcategory');
    Route::get('admin/banner','admin/Banner/index');
    Route::get('admin/banneradd','admin/Banner/add');
    Route::get('admin/place','admin/Place/index');
    Route::get('admin/placeadd','admin/Place/add');
    Route::get('admin/guanliangoods','admin/Place/guanliangoods');
    Route::get('admin/introduce','admin/Introduce/index');
    //guanliangoods




});



Route::any('dologin','index/login/dologin');
Route::any('getcity','index/Address/getcity');
Route::any('addaddress','index/Address/addaddress');
Route::get('moregoods','index/Goods/moregoods');
Route::any('addcart','index/Cart/addcart');
Route::any('delete','index/Cart/delete');
Route::any('checked','index/Cart/checked');
Route::any('checkedall','index/Cart/checkedall');
Route::any('suborder','index/Order/suborder');
Route::any('admin/dologin','admin/Login/dologin');
Route::any('admin/logout','admin/Login/logout');

Route::any('admin/getlist','admin/Goods/getlist');
Route::any('admin/addgoods','admin/Goods/addgoods');
//addgoods
Route::any('admin/getcateson','admin/Goods/getcateson');
//getcateson
Route::any('admin/cateson','admin/Category/cateson');
Route::any('admin/doaddcate','admin/Category/doaddcate');
Route::any('admin/cdelete','admin/Category/delete');
Route::any('admin/dobanneradd','admin/Banner/dobanneradd');
Route::any('admin/getbanner','admin/Banner/getbanner');

Route::any('banner/bannerdelete','admin/Banner/bannerdelete');
Route::any('admin/getplace','admin/Place/getplace');
Route::any('admin/doplace','admin/Place/doplace');
Route::any('admin/placedelete','admin/Place/placedelete');
Route::any('admin/doedit','admin/Introduce/doedit');
//doedit

//Introduce
//placedelete
//doplace
///admin/banner.html
//getbanner
//dobanneradd
//delete
//doaddcate
//logout
//suborder
//checkedall
//checked
//delete
Route::any('changecart','index/Cart/changecart');
//changecart

//addcart
//moregoods
/*
Route::get('public/:name/:s',function($name,$s){

    //return __DIR__;
    //return 'Hello';
    return file_get_contents(str_replace('/application','',__DIR__.'/public/'.$name.'/'.$s))  ;
    return __DIR__.'/public/'.$name.'/'.$s;


});*/
/*
Route::group(['method' => 'get', 'ext' => 'html'], function () {
    Route::rule('read/:id', 'index/Index/read');
    Route::rule('test/:name', 'index/test');
})->pattern(['id' => '\d+', 'name' => '\w+']);
*/