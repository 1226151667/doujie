<?php
namespace app\index\validate;
use think\Validate;
class Empuser extends Validate{
	protected $rule = [
		'uname' => 'require|between:5,12',
		'password' => 'require|min:6',
	];
    protected $message  =   [
        'uname.require' => '账号必须',
        'uname.between'     => '账号长度在5~12之间',
        'password.require'   => '密码必须',
        'password.min'  => '密码长度最小6位',
    ];
    
    protected $scene = [
        'edit'  =>  ['uname','password'],
    ];
    
}