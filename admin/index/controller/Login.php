<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Empuser;
use app\index\model\Login as LoginModel;

class Login extends Controller{
    protected $beforeActionList = [
        'is_login' =>  ['only'=>'index'],
    ];
    
    protected function is_login(){
        if(session('?empuser_id') && session('?empuser_uname')){
            $this->redirect('/admin.php','已登录');
        }
    }
    public function index(){
        return view();
    }
    public function login_check(){
		if(request()->isAjax()){
			$Empuser = new Empuser();
			$uname = request()->post('uname');
			$empuser_find = $Empuser->where('uname',$uname)->find();
			if($empuser_find){
				$password = request()->post('password');
				if($empuser_find['password']==($Empuser->set_password($password))){
					session('empuser_uname', $uname);
					session('empuser_id', $empuser_find['id']);
					$Login = new LoginModel();
					$Login->empuser_id = $empuser_find['id'];
					$Login->save();
					return [200,'登录成功'];
				}else{
					return [102,'密码错误'];
				}
			}else{
				return [103,'账号不存在'];
			}
		}else{
			$this->redirect('/','非法访问');
		}
	}
    public function out(){
        session(null);
        $this->redirect(url('Login/index'));
    }
}