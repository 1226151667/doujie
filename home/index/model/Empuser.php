<?php
namespace app\index\model;
use app\index\model\Start;
class Empuser extends Start{
	protected $updateTime = false;
	protected $auto = ['password', 'power' => 0];
	protected $insert = ['ip'];  
	protected function setIpAttr(){
		return request()->ip();
	}
	protected function setPasswordAttr($value){
		return $this->set_password($value);
	}
}