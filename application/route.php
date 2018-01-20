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
    Route::get('read/:id/:name','index/Index/read');

    Route::get('detail','index/Index/detail');
    Route::get('editl/:id','index/Index/editl');
});



/*
Route::group(['method' => 'get', 'ext' => 'html'], function () {
    Route::rule('read/:id', 'index/Index/read');
    Route::rule('test/:name', 'index/test');
})->pattern(['id' => '\d+', 'name' => '\w+']);
*/