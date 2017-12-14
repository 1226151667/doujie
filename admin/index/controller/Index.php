<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Website;
use app\index\model\Empuser;

class Index extends Start{
    public function index(){
        return view();
    }
    public function website()
    {
        $Website = new Website();
        $data = $Website->alias('w')
            ->field("w.*, a.file_path icon_path, at.file_path logo_path, att.file_path weixin_path")
            ->join('attachment a','a.id=w.icon_id','LEFT')
            ->join('attachment at','at.id=w.logo_id','LEFT')
            ->join('attachment att','att.id=w.weixin_id','LEFT')
            ->find();
        return $this->fetch('website', ['data'=>$data]);
    }
    public function empuser(){
        $Empuser = new Empuser();
        $list = $Empuser->paginate(10);
        return $this->fetch('empuser', ['list'=>$list, 'empty'=>'<tr><td>没有数据</td></tr>']);
    }
    public function add_empuser(){
        return view();    
    }
    public function edit_empuser(){
        return view();    
    }
    public function logout(){
        session(null);
        $this->redirect(url('Login/index'));
    }
}	
