<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Customer;
use app\index\model\Cases as CasesModel;

class Cases extends Start{
	public function show($type){
		$type_arr = array('web'=>'网站开发', 'wx'=>'微信定制', 'app'=>'APP应用');
		$page_title = $type_arr[$type];
        $Cases = new CasesModel();
        $list = $Cases->alias("c")->field("c.*,
        			a.file_path ewm_path,
					at.file_path pic_path,
					att.file_path w730h730pic_path,
					attt.file_path w730h1004pic_path
        		")
        		->join('attachment a','a.id=c.ewm_id','LEFT')
        		->join('attachment at','at.id=c.pic_id','LEFT')
        		->join('attachment att','att.id=c.w730h730pic_id','LEFT')
        		->join('attachment attt','attt.id=c.w730h1004pic_id','LEFT')
        		->where("c.type='{$type}'")->order("c.create_time desc")->paginate(10);
        return $this->fetch('show', ['list'=>$list, 'type'=>$type, 'page_title'=>$page_title, 'empty'=>'<tr><td>没有数据</td></tr>']);
	}
	public function edit_cases($type,$id=0){
		$cases_data = array();
		if($id){
			$Cases = new CasesModel();
			$cases_data = $Cases->alias("c")->field("c.*,
					a.file_path ewm_path,
					at.file_path pic_path,
					att.file_path w730h730pic_path,
					attt.file_path w730h1004pic_path
				")
        		->join('attachment a','a.id=c.ewm_id','LEFT')
        		->join('attachment at','at.id=c.pic_id','LEFT')
        		->join('attachment att','att.id=c.w730h730pic_id','LEFT')
        		->join('attachment attt','attt.id=c.w730h1004pic_id','LEFT')
				->find($id);
			if(!$cases_data){
				$this->redirect(url("Cases/{$type}"),'参数有误');
			}
			$cases_data['page_title'] = '修改案例';
		}else{
			$cases_data['page_title'] = '添加案例';
		}
		$cases_data['id'] = $id;
		$cases_data['type'] = $type;
        return $this->fetch('edit_cases', ['cases_data'=>$cases_data, 'type'=>$type]);
	}
	public function customer(){
        $Customer = new Customer();
        $list = $Customer->alias("c")->field("c.*,att.file_path logo_path")
        		->join('attachment att','att.id=c.logo_id','LEFT')->order("c.create_time desc")
        		->paginate(10);
        return $this->fetch('customer', ['list'=>$list, 'empty'=>'<tr><td>没有数据</td></tr>']);
	}
	public function edit_customer($id=0){
		$customer_data = array();
		if($id){
			$Customer = new Customer();
			$customer_data = $Customer->alias("c")->field("c.*,att.file_path logo_path")
        		->join('attachment att','att.id=c.logo_id','LEFT')
				->find($id);
			if(!$customer_data){
				$this->redirect(url('Cases/customer'),'参数有误');
			}
			$customer_data['page_title'] = '修改合作客户';
		}else{
			$customer_data['page_title'] = '添加合作客户';
		}
		$customer_data['id'] = $id;
        return $this->fetch('edit_customer', ['customer_data'=>$customer_data]);
	}
}