<?php
namespace app\index\controller;
use app\index\controller\Start;
use app\index\model\Empuser;
use app\index\model\Category;
use app\index\model\Article;
use app\index\model\Slider;
use app\index\model\Customer;
use app\index\model\Cases;
use app\index\model\Attachment;
use app\index\model\Login;

class Trash extends Start{
	public function common(){
		if(request()->isAjax()){
			$error_num = 0;
			$param = request()->param();
			switch($param['cate']){
				case 'empuser':
					$obj = new Empuser();
					break;
				case 'category':
					$obj = new Category();
					break;
				case 'article':
					$obj = new Article();
					return $this->dec_attachment($obj,$param['cate'],$param['ids']);
					break;
				case 'slider':
					$obj = new Slider();
					return $this->dec_attachment($obj,$param['cate'],$param['ids']);
					break;
				case 'customer':
					$obj = new Customer();
					return $this->dec_attachment($obj,$param['cate'],$param['ids']);
					break;
				case 'cases':
					$obj = new Cases();
					return $this->dec_attachment($obj,$param['cate'],$param['ids']);
					break;
				case 'attachment':
					$obj = new Attachment();
					// var_dump($param);exit;
					return $this->dec_attachment($obj,$param['cate'],$param['ids']);
					break;
				case 'login':
					$obj = new Login();
					break;
				default:
					return [102,'操作错误'];
					break;
			}
			$id_str = implode(",",$param['ids']);
			if($obj->where("id in ({$id_str})")->delete()){
				return [200,'删除成功'];	
			}else{
				return [103,'删除失败'];
			}
		}else{
			$this->redirect('/','非法访问');
		}
	}

	public function dec_attachment($obj,$cate,$ids){
		$error_num = 0;
		$cate_arr = array(
			'article' => array('pic_id'),
			'slider' => array('backpic_id','pic_id'),
			'customer' => array('logo_id'),
			'cases' => array('pic_id','w730h730pic_id','w730h1004pic_id','ewm_id')
		);
		foreach ($ids as $v) {
			$info = $obj->find($v);
			if($cate=='attachment'){
				if($info['use_num']>0){
					$error_num += 1;
					break;
				}
				if(file_exists('.'.$info['file_path'])){
					if(!unlink('.'.$info['file_path'])){
						$error_num += 1;
						break;
					}	
				}
				if(!$obj->where("id={$v}")->delete($v)){
					$error_num += 1;
				}
			}else{
				$att_error_num = 0;
				foreach($cate_arr[$cate] as $val) {
					if(Attachment::get($info[$val])){
						if(!Attachment::where('id', $info[$val])->setDec('use_num')){
							$att_error_num += 1;
						}
					}	
				}
				if($att_error_num==0){
					if(!$obj->where("id={$v}")->delete($v)){
						$error_num += 1;
					}
				}else{
					$error_num += 1;
				}
			}
		}
		if($error_num==0){
			return [200,'删除成功'];	
		}elseif($error_num<count($ids)){
			return [102,'部分删除失败'];	
		}else{
			return [103,'删除失败'];
		}
	}
}