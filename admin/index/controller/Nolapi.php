<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Website;

class Nolapi extends Controller{
	public function login_status(){
		$callback = request()->get('callback');
		if(!session('?empuser_uname') || !session('?empuser_id')){
			// return $callback.'(100)';exit;
			return 'alert(100);';
		}		
		return 'alert(1011);';
		// return $callback.'(100)';exit;
	}
}