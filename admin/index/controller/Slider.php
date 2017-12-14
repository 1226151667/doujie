<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Slider as SliderModel;

class Slider extends Start{
	public function index(){
        $Slider = new SliderModel();
        $list = $Slider->alias("s")->field("s.*,att.file_path pic_path,at.file_path backpic_path")
        		->join('attachment at','at.id=s.backpic_id','LEFT')
        		->join('attachment att','att.id=s.pic_id','LEFT')
        		->order('sort')->paginate(10);
        return $this->fetch('index', ['list'=>$list, 'empty'=>'<tr><td>没有数据</td></tr>']);
	}
	public function edit_slider($id=0){
		$slider_data = array();
		if($id){
			$Slider = new SliderModel();
			$slider_data = $Slider->alias("s")->field("s.*,att.file_path pic_path,at.file_path backpic_path")
				->join('attachment at','at.id=s.backpic_id','LEFT')
        		->join('attachment att','att.id=s.pic_id','LEFT')
				->find($id);
			if(!$slider_data){
				$this->redirect(url('Slider/index'),'参数有误');
			}
			$slider_data['page_title'] = '修改轮播';
		}else{
			$slider_data['page_title'] = '添加轮播';
		}
		$slider_data['id'] = $id;
        return $this->fetch('edit_slider', ['slider_data'=>$slider_data]);
	}
}