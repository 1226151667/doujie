<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Attachment;

class Att extends Start{
	public function show($type){
		$type_arr = array('img'=>'图片列表');
		$page_title = $type_arr[$type];
		$s_w = $this->get_search('att',url('Att/show','type='.$type));
        $Attachment = new Attachment();
        $list = $Attachment->where("type='{$type}' and {$s_w['where']}")->order('create_time desc')->paginate(10);
        return $this->fetch('show', ['list'=>$list, 'type'=>$type, 'page_title'=>$page_title, 'search'=>$s_w['search'], 'empty'=>'<tr><td>没有数据</td></tr>']);
	}
}