<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Website;
use app\index\model\Empuser;
use app\index\model\Category;
use app\index\model\Article;
use app\index\model\Slider;
use app\index\model\Customer;
use app\index\model\Cases;
use app\index\model\Attachment;

class Sub extends Start{
	public function website(){
		if(request()->isPost()){
			$Website = new Website();
			if($_FILES['wx_ewm']['name']!=""){
				$weixin_id = $this->upload_file('wx_ewm','微信公众号');
				request()->post(['weixin_id'=>$weixin_id]);
			}
			if($_FILES['website_ico']['name']!=""){
				$icon_id = $this->upload_file('website_ico','公司icon图标');
				request()->post(['icon_id'=>$icon_id]);
			}
			if($_FILES['website_logo']['name']!=""){
				$logo_id = $this->upload_file('website_logo','公司logo');
				request()->post(['logo_id'=>$logo_id]);
			}
			if($Website->find()){
				$info = $Website->find();
				if(request()->has('weixin_id','post')){
					$att_id = $info['weixin_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				if(request()->has('icon_id','post')){
					$att_id = $info['icon_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				if(request()->has('logo_id','post')){
					$att_id = $info['logo_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				$rs = $Website->save(request()->post(),['id' => 1]);
			}else{
				$rs = $Website->save(request()->post());
			}
		}
		$this->redirect(url('Index/website'));
	}
	public function add_empuser(){
		if(request()->isPost()){
			$Empuser = new Empuser();
			$Empuser->save(request()->post());
		}
		$this->redirect(url('Empuser/index'));
	}
	public function edit_password(){
		if(request()->isPost()){
			$Empuser = new Empuser();
			$Empuser->save(request()->post(),['id'=>session('empuser_id')]);
		}
		$this->redirect(url('Empuser/index'));
	}
	public function edit_category($id=0){
		$id = (int)$id;
		if(request()->isPost()){
			$Category = new Category();
			if($id!=0){
				$Category->save(request()->post(),['id'=>$id]);
			}else{
				$Category->save(request()->post());
			}
		}
		$this->redirect(url('Article/category'),'非法操作');
	}
	public function edit_article($id=0){
		$id = (int)$id;
		if(request()->isPost()){
			$Article = new Article();
			if($_FILES['pic']['name']!=""){
				$pic_id = $this->upload_file('pic','文章封面');
				request()->post(['pic_id'=>$pic_id]);
			}
			if($id!=0){
				$info = $Article->find($id);
				if(request()->has('pic_id','post')){
					$att_id = $info['pic_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				$Article->save(request()->post(),['id'=>$id]);
			}else{
				$Article->save(request()->post());
			}
		}
		$this->redirect(url('Article/index'),'非法操作');
	}
	public function edit_slider($id=0){
		$id = (int)$id;
		if(request()->isPost()){
			$Slider = new Slider();
			if($_FILES['pic']['name']!=""){
				$pic_id = $this->upload_file('pic','渐入图片');
				request()->post(['pic_id'=>$pic_id]);
			}
			if($_FILES['backpic']['name']!=""){
				$backpic_id = $this->upload_file('backpic','背景图片');
				request()->post(['backpic_id'=>$backpic_id]);
			}
			if($id!=0){
				$info = $Slider->find($id);
				if(request()->has('pic_id','post')){
					$att_id = $info['pic_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				if(request()->has('backpic_id','post')){
					$att_id = $info['backpic_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				$Slider->save(request()->post(),['id'=>$id]);
			}else{
				$Slider->save(request()->post());
			}
		}
		$this->redirect(url('Slider/index'),'非法操作');
	}
	public function edit_customer($id=0){
		$id = (int)$id;
		if(request()->isPost()){
			$Customer = new Customer();
			if($_FILES['logo']['name']!=""){
				$logo_id = $this->upload_file('logo','合作客户logo');
				request()->post(['logo_id'=>$logo_id]);
			}
			if($id!=0){
				$info = $Customer->find($id);
				if(request()->has('logo_id','post')){
					$att_id = $info['logo_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				$Customer->save(request()->post(),['id'=>$id]);
			}else{
				$Customer->save(request()->post());
			}
		}
		$this->redirect(url('Cases/customer'),'非法操作');
	}
	public function edit_cases($type,$id=0){
		$id = (int)$id;
		if(request()->isPost()){
			$Cases = new Cases();
			if($type!='web'){
				if($_FILES['ewm']['name']!=""){
					$ewm_id = $this->upload_file('ewm','案例二维码');
					request()->post(['ewm_id'=>$ewm_id]);
				}
			}
			if($_FILES['pic']['name']!=""){
				$pic_id = $this->upload_file('pic','案例图片');
				request()->post(['pic_id'=>$pic_id]);
			}
			if($_FILES['w730h730pic']['name']!=""){
				$w730h730pic_id = $this->upload_file('w730h730pic','案例图片');
				request()->post(['w730h730pic_id'=>$w730h730pic_id]);
			}
			if($_FILES['w730h1004pic']['name']!=""){
				$w730h1004pic_id = $this->upload_file('w730h1004pic','案例图片');
				request()->post(['w730h1004pic_id'=>$w730h1004pic_id]);
			}
			request()->post(['type'=>$type]);
			if($id!=0){
				$info = $Cases->find($id);
				if(request()->has('ewm_id','post')){
					$att_id = $info['ewm_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				if(request()->has('pic_id','post')){
					$att_id = $info['pic_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				if(request()->has('w730h730pic_id','post')){
					$att_id = $info['w730h730pic_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				if(request()->has('w730h1004pic_id','post')){
					$att_id = $info['w730h1004pic_id'];
					Attachment::where('id', $att_id)->setDec('use_num');
				}
				$Cases->save(request()->post(),['id'=>$id]);
			}else{
				$Cases->save(request()->post());
			}
		}
		$this->redirect(url("Cases/show","type=".$type),'非法操作');
	}
}