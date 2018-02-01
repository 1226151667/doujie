<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Website;

class Nolapi extends Controller{
	public function login_status(){
		$callback = request()->get('callback');
		if(!session('?empuser_uname') || !session('?empuser_id')){
			echo $callback.'(100)';exit;
			// return json_encode(['status'=>'no', 'error'=>'no login']);
		}		
		$Website = new Website();
		$website_info = $Website->alias('w')->field("at.file_path icon_path,att.file_path logo_path")
			->join('attachment at','at.id=w.icon_id','LEFT')
			->join('attachment att','att.id=w.logo_id','LEFT')
			->find();
		echo $callback.'(100)';exit;
		return json_encode(['status'=>'ok', 'data'=>[
				'empuser_id'=>session('empuser_id'), 
				'empuser_uname'=>session('empuser_uname'),
				'icon_path'=>$website_info['icon_path'],
				'logo_path'=>$website_info['logo_path']
			]
		]);
	}
}