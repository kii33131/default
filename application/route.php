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
    //
    Route::get('detail','index/Index/detail');
    Route::get('editl/:id','index/Index/editl');
    Route::get('common/header','index/common/header');
    Route::get('common/floor','index/common/floor');
    Route::get('common/top','index/common/top');
    Route::get('common/search_front','index/common/search_front');
    Route::get('login','index/login/index');
    Route::get('yzm','index/login/yzm');

});

Route::any('dologin','index/login/dologin');
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