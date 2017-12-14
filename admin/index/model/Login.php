<?php
namespace app\index\model;
use app\index\model\Start;

class Login extends Start{
	protected $updateTime = false;
	protected $insert = ['ip','address'];
	protected function setIpAttr(){
		return request()->ip();
	}
	protected function setAddressAttr($value){
		return $this->set_address($value);
	}
}