<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Article as ArticleModel;
use app\index\model\Category;

class Article extends Start{
	public function index(){
		$s_w = $this->get_search('article',url('Article/index'));
        $Article = new ArticleModel();
        $Category = new Category();
        $category_list = $Category->select();
        $list = $Article->alias("a")->field("a.*,c.name category_name,att.file_path pic_path")
        		->join('attachment att','att.id=a.pic_id','LEFT')
        		->join('category c','c.id=a.category_id','LEFT')
        		->where($s_w['where'])->order("a.create_time desc")->paginate(10);
        return $this->fetch('index', ['list'=>$list, 'category_list'=>$category_list, 'search'=>$s_w['search'], 'empty'=>'<tr><td>没有数据</td></tr>']);
	}
	public function category(){
        $Category = new Category();
        $list = $Category->paginate(10);
        return $this->fetch('category', ['list'=>$list, 'empty'=>'<tr><td>没有数据</td></tr>']);
	}
	public function edit_category($id=0){
		$category_data = array();
		if($id){
			$Category = new Category();
			$category_data = $Category->find($id);
			if(!$category_data){
				$this->redirect(url('Article/category'),'参数有误');
			}
			$category_data['page_title'] = '修改类别';
		}else{
			$category_data['page_title'] = '添加类别';
		}
		$category_data['id'] = $id;
        return $this->fetch('edit_category', ['category_data'=>$category_data]);
	}
	public function edit_article($id=0){
		$article_data = array();
		if($id){
			$Article = new ArticleModel();
			$article_data = $Article->alias("a")->field("a.*,att.file_path pic_path")
        		->join('attachment att','att.id=a.pic_id','LEFT')
				->find($id);
			if(!$article_data){
				$this->redirect(url('Article/index'),'参数有误');
			}
			$article_data['page_title'] = '修改文章';
		}else{
			$article_data['page_title'] = '添加文章';
			$article_data['category_id'] = 0;
		}
		$article_data['id'] = $id;
		$Category = new Category();
		$category_list = $Category->select();
        return $this->fetch('edit_article', ['category_list'=>$category_list, 'article_data'=>$article_data]);
	}
}