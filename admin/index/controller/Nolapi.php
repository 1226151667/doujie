<?php
namespace app\index\controller;
use think\Controller;

class Nolapi extends Controller{
	public function login_status(){
		if(!session('?empuser_uname') || !session('?empuser_id')){
			// return {'status':'no', 'error': 'no login'};
			return 2222;
		}		
		$Website = new Website();
		$website_info = $Website->alias('w')->field("at.file_path icon_path,att.file_path logo_path")
			->join('attachment at','at.id=w.icon_id','LEFT')
			->join('attachment att','att.id=w.logo_id','LEFT')
			->find();
		return 1111;
	}
}