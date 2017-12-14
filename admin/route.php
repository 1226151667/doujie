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
Route::bind('index');

Route::rule('Article/index/[:st]/[:et]/[:c_id]/[:q]', 'index/Article/index', 'GET',[], [
	'st'=>'\d{4}-\d{2}-\d{2}', 'et'=>'\d{4}-\d{2}-\d{2}', 'q'=>'.*?', 'c_id'=>'\d+'
]);
Route::rule('Article/edit_article/[:id]', 'index/Article/edit_article', 'GET',[], ['id'=>'\d+']);
Route::rule('Sub/edit_article/[:id]', 'index/Sub/edit_article', 'POST',[], ['id'=>'\d+']);

Route::rule('Article/edit_category/[:id]', 'index/Article/edit_category', 'GET',[], ['id'=>'\d+']);
Route::rule('Sub/edit_category/[:id]', 'index/Sub/edit_category', 'POST',[], ['id'=>'\d+']);

Route::rule('Slider/edit_slider/[:id]', 'index/Slider/edit_slider', 'GET',[], ['id'=>'\d+']);
Route::rule('Sub/edit_slider/[:id]', 'index/Sub/edit_slider', 'POST',[], ['id'=>'\d+']);

Route::rule('Cases/edit_customer/[:id]', 'index/Cases/edit_customer', 'GET',[], ['id'=>'\d+']);
Route::rule('Sub/edit_customer/[:id]', 'index/Sub/edit_customer', 'POST',[], ['id'=>'\d+']);

Route::rule('Cases/show/:type', 'index/Cases/show', 'GET',[], ['type'=>'\w+']);
Route::rule('Cases/edit_cases/:type/[:id]', 'index/Cases/edit_cases', 'GET',[], ['type'=>'\w+', 'id'=>'\d+']);
Route::rule('Sub/edit_cases/:type/[:id]', 'index/Sub/edit_cases', 'POST',[], ['type'=>'\w+', 'id'=>'\d+']);

Route::rule('Att/show/:type/[:st]/[:et]/[:use]/[:q]', 'index/Att/show', 'GET',[], [
	'type'=>'\w+', 'st'=>'\d{4}-\d{2}-\d{2}', 'et'=>'\d{4}-\d{2}-\d{2}', 'q'=>'.*?', 'use'=>'\w+'
]);


// Route::alias('sub','index/Sub');
// Route::alias('trash','index/Trash');
// Route::alias('login','index/Login');
// Route::rule('website','index/Index/website');
// Route::rule('logout','index/Index/logout');
// Route::rule('add_empuser','index/Index/add_empuser');
// Route::rule('empuser','index/Index/empuser');
// Route::rule('edit_empuser','index/Index/edit_empuser');