<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Website;

class Nolapi extends Controller{
	public function login_status(){
		$callback = request()->get('callback');
		if(!session('?empuser_uname') || !session('?empuser_id')){
			return $callback.'("error","no login")';exit;
		}		
		return $callback.'("success","'.md5(session('empuser_id').date("HmdYi")).'")';exit;
	}
}