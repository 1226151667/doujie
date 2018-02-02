<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Website;

class Nolapi extends Controller{
	public function login_status(){
		$callback = request()->get('callback');
		header('Content-Type:text/javascript;charset=utf-8');
		if(!session('?empuser_uname') || !session('?empuser_id')){
			// return $callback.'(100)';exit;
			return 'alert(100)';
		}		
		return 'alert(101)';
		// return $callback.'(100)';exit;
	}
}