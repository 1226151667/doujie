<?php
namespace app\index\model;
use app\index\model\Start;
class Empuser extends Start{
	protected $auto = ['password', 'power' => 0];
	protected $insert = ['ip','address'];  
	protected function setIpAttr(){
		return request()->ip();
	}
	protected function setPasswordAttr($value){
		return $this->set_password($value);
	}
	protected function setAddressAttr($value){
		return $this->set_address($value);
	}
}