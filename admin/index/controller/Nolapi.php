<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Website;

class Nolapi extends Controller{
	public function login_status(){
		$callback = request()->get('callback');
		if(!session('?empuser_uname') || !session('?empuser_id')){
			echo $callback.'("no");';exit;
		}		
		echo $callback.'("ok",'.md5(session('empuser_id')).');';
	}
}