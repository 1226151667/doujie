<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Empuser as EmpuserModel;
use app\index\model\Login;

class Empuser extends Start{
	public function index(){
        $Empuser = new EmpuserModel();
        $list = $Empuser->order('create_time desc')->paginate(10);
        return $this->fetch('index', ['list'=>$list, 'empty'=>'<tr><td>没有数据</td></tr>']);
    }
    public function add_empuser(){
        return view();    
    }
    public function edit_password(){
        return view();    
    }
    public function login(){
        $Login = new Login();
        $list = $Login->alias("l")->field("l.*,e.note,e.uname")->join('empuser e','e.id=l.empuser_id','LEFT')->order("l.create_time desc")->paginate(10);
        return $this->fetch('login', ['list'=>$list, 'empty'=>'<tr><td>没有数据</td></tr>']);
    }
}