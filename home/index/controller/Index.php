<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Article;
use app\index\model\Category;
use app\index\model\Slider;
use app\index\model\Cases;
use app\index\model\Website;
use app\index\model\Customer;

class Index extends Controller{
	public function _initialize(){
		$Website = new Website();
		$website_info = $Website->alias('w')->field("w.*,at.file_path icon_path,att.file_path logo_path,attt.file_path weixin_path")
			->join('attachment at','at.id=w.icon_id','LEFT')
			->join('attachment att','att.id=w.logo_id','LEFT')
			->join('attachment attt','attt.id=w.weixin_id','LEFT')
			->find();
		$GLOBALS['website'] = $website_info;
	}
	public function index(){
		$Slider = new Slider();
		$Cases = new Cases();
		$Customer = new Customer();
		$Article = new Article();
        $slider_list = $Slider->alias("s")->field("s.*,att.file_path pic_path,at.file_path backpic_path")
        		->join('attachment at','at.id=s.backpic_id','LEFT')
        		->join('attachment att','att.id=s.pic_id','LEFT')
        		->order('sort')->select();
        $cases_list = $Cases->alias("c")->field("c.*,att.file_path as w730h730pic_path,at.file_path as w730h1004pic_path")
			->join('attachment at','at.id=c.w730h1004pic_id','LEFT')
			->join('attachment att','att.id=c.w730h730pic_id','LEFT')
			->order('c.create_time', 'asc')->limit(12)->select();
		$customer_list = $Customer->alias("c")->field("c.*,att.file_path as logo_path")
			->join('attachment att','att.id=c.logo_id','LEFT')
			->order('c.create_time', 'asc')->limit(12)->select();
		$article_list = $Article->alias("a")->field("a.*,
			att.file_path as pic_path,
			extract(year from a.create_time) as create_y,
			extract(month from a.create_time) as create_m,
			extract(day from a.create_time) as create_d
			")->join('attachment att','att.id=a.pic_id','LEFT')->order('a.create_time', 'asc')->paginate(9);
		return $this->fetch('index',[
			'website' => $GLOBALS['website'],
			'slider_list'=>$slider_list,
			'cases_list'=>$cases_list,
			'customer_list'=>$customer_list,
			'article_list'=>$article_list,
			'style_css'=>''
		]);
	}
	public function about(){
		return $this->fetch('about',[
			'website' => $GLOBALS['website'],
			'style' => '.dg,.main{display:none;}'
		]);
	}
	public function cases($type=''){
		$cases_where = $type?"c.type='{$type}'":"";
		$Cases = new Cases();
		$list = $Cases->alias("c")->field("c.*,att.file_path as w730h730pic_path")
			->join('attachment att','att.id=c.w730h730pic_id','LEFT')->where($cases_where)->order('c.create_time', 'asc')->paginate(9);
        return $this->fetch('cases', [
        	'website' => $GLOBALS['website'],
        	'type'=>$type, 
        	'list'=>$list, 
        	'empty'=>'<li>没有数据</li>'
        ]);
	}
	public function case_detail($id){
		$Cases = new Cases();
		$info = $Cases->alias("c")->field("c.*,att.file_path pic_path,at.file_path ewm_path")
			->join('attachment at','at.id=c.ewm_id','LEFT')
			->join('attachment att','att.id=c.pic_id','LEFT')
			->find($id);
		$previous_id = $Cases->field("id")->where("id<{$id} and type='{$info['type']}'")->order('id desc')->find();
		$next_id = $Cases->field("id")->where("id>{$id} and type='{$info['type']}'")->order('id')->find();
		return $this->fetch('case_detail', [
			'website' => $GLOBALS['website'],
			'info'=>$info, 
			'previous_id'=>$previous_id['id'], 
			'next_id'=>$next_id['id']
		]);
	}
	public function contact(){
		$qqs = explode(";",$GLOBALS['website']['qq']);
		$emails = explode(";",$GLOBALS['website']['email']);
		$phones = explode(";",$GLOBALS['website']['phone']);
		return $this->fetch('contact',[
			'website' => $GLOBALS['website'],
			'qqs' => $qqs,
			'emails' => $emails,
			'phones' => $phones
		]);
	}
	public function design(){
		return $this->fetch('design',[
			'website' => $GLOBALS['website']
		]);
	}
	public function marketing(){
		return $this->fetch('marketing',[
			'website' => $GLOBALS['website']
		]);
	}
	public function news($id=0){
		$article_where = "";
		if($id!=0) $article_where = "a.category_id={$id}";
		$Article = new Article();
		$Category = new Category();
		$category_list = $Category->order('sort')->select();
		$list = $Article->alias("a")->field("a.*,
    			att.file_path as pic_path,
    			extract(year from a.create_time) as create_y,
    			extract(month from a.create_time) as create_m,
    			extract(day from a.create_time) as create_d
    			")->join('attachment att','att.id=a.pic_id','LEFT')->where($article_where)->order('a.create_time', 'asc')->paginate(9);
        return $this->fetch('news', [
        	'website' => $GLOBALS['website'],
        	'c_id'=>$id, 
        	'list'=>$list, 
        	'category_list'=>$category_list, 
        	'empty'=>'<li>没有数据</li>'
        ]);
	}
	public function new_detail($id){
		$Category = new Category();
		$category_list = $Category->order('sort')->select();
		$Article = new Article();
		$Article->where("id={$id}")->setInc('view_num');
		$info = $Article->alias("a")->field("a.*,att.file_path as pic_path")->join('attachment att','att.id=a.pic_id','LEFT')->find($id);
		$previous_id = $Article->field("id")->where("id<{$id} and category_id={$info['category_id']}")->order('id desc')->find();
		$next_id = $Article->field("id")->where("id>{$id} and category_id={$info['category_id']}")->order('id')->find();
		$article_about = $Article->alias("a")->field("a.*,att.file_path as pic_path")
			->join('attachment att','att.id=a.pic_id','LEFT')->order('rand()')->where("a.category_id={$info['category_id']}")->limit(2)->select();
		return $this->fetch('new_detail', [
			'website' => $GLOBALS['website'],
			'info'=>$info, 
			'article_about'=>$article_about, 
			'previous_id'=>$previous_id['id'], 
			'next_id'=>$next_id['id'], 
			'category_list'=>$category_list
		]);
	}
	public function service(){
		return $this->fetch('service',[
			'website' => $GLOBALS['website']
		]);
	}
	public function error_400(){
		return $this->fetch("error/404}",[
			'website' => $GLOBALS['website']
		]);
	}
}