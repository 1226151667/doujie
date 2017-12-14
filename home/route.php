<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/Index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/Index/hello', ['method' => 'post']],
//     ],

// ];

use \think\Route;
Route::bind('index/Index');

Route::rule('news_c<id?>', 'index/Index/news', 'GET',[], ['id'=>'\d+']);
Route::rule('new_detail_1<id>', 'index/Index/new_detail', 'GET',[], ['id'=>'\d+']);

Route::rule('cases_t<type?>', 'index/Index/cases', 'GET',[], ['type'=>'\w+']);
Route::rule('case_detail_1<id>', 'index/Index/case_detail', 'GET',[], ['id'=>'\d+']);