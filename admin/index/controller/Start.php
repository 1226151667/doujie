<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Attachment;
use app\index\model\Website;

class Start extends Controller{
	public function _initialize(){
		if(!session('?empuser_uname') || !session('?empuser_id')){
			session(null);
			$this->redirect(url("Login/index"),'请先登录！');
		}		
		$Website = new Website();
		$website_info = $Website->alias('w')->field("at.file_path icon_path,att.file_path logo_path")
			->join('attachment at','at.id=w.icon_id','LEFT')
			->join('attachment att','att.id=w.logo_id','LEFT')
			->find();
		session('icon_path',$website_info['icon_path']);
		session('logo_path',$website_info['logo_path']);
	}

	/*--func：上传文件，并且存入附件表Attachment内
	*@file_name:string, input框的name值
	*@sign：string, 上传文件的标注
	*@type：sting, 文件类型, 可选值['img'=>'图片'](以后可扩展)
	*--return：num, 上传成功时返回的值, [0=>'上传失败'，其它=>'成功插入后返回的附件id']
	*/
	public function upload_file($file_name,$sign,$type='img'){
		$file = request()->file($file_name);
		// 移动到框架应用根目录/public/uploads/ 目录下
		$uploads = ROOT_PATH . 'public' . DS . 'uploads';
		$info = $file->rule('uniqid')->validate(['ext'=>'jpg,png,gif,ico'])->move($uploads);
		if($info){
			$Attachment = new Attachment();
			$data = array(
				'type'=>$type,
				'sign'=>$sign,
				'file_path'=>'/uploads/'.$info->getSaveName()
			);
			$Attachment->save($data);
			return $Attachment->id;
		}else{
			// 上传失败返回0
			return 0;
		}    
	}

	/*--func: 生成缩略图
	*@file_path: string, 原图片相对路径
	*@w: int, 缩略图的宽度
	*@h: int, 缩略图的高度
	*return: string, 返回缩略图的路径, 缩略图生成失败返回空字符串''
	*/
	public function thumb_pic($file_path,$w,$h){
		$file_arr = explode(".",$file_path);
		$extension = end($file_arr);
		$file_name = '/uploads/'.md5(time()+1).'.'.$extension;
		$image = \think\Image::open($file_path);
		if( $image->thumb($w,$h,\think\Image::THUMB_CENTER)->save('.'.$file_name) ){
			return $file_name;
		}else{
			return '';
		}

	}

	/*--func: 缩略图生成后存入附件表Attachment内
	*@type: string, 提供值$value的类型, 可选值['id', 'file_name']
	*@value: string|int, 要生成缩略图的相对路径|Attachment的id, 若type为id，则value为string；若type为file_name，则value为int
	*@w: int, 缩略图的宽度
	*@h: int, 缩略图的高度
	*@sign: int, Attachment的sign字段
	*return: string, 返回Attachment的id, [0=>'上传失败'，其它=>'成功插入后返回的附件id']
	*/
	public function get_thumb_id($type,$value,$w,$h,$sign){
		switch($type){
			case 'id':
				$Attachment = new Attachment();
				$info = $Attachment->find($value);
				$file_path = $info['file_path'];		
				// $file_arr = explode(".",$file_path);
				// $extension = end($file_arr);
				$save_path = $this->thumb_pic('.'.$file_path,$w,$h);
				break;
			case 'file_name':
				// $file_arr = explode(".",$value);		
				// $extension = end($file_arr);
				$save_path = $this->thumb_pic('.'.$value,$w,$h);
				break;
			default:
				return 0;
				break;
		}
		if($save_path!=''){
			$data = array(
				'type' => 'img',
				'sign' => $sign,
				'file_path' => $save_path
			);
			if($Attachment->save($data)){
				return $Attachment->id;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	/*--fun: 验证是不是一个合格的时间格式
	*@str: string, 要检验的时间
	*/
	public function check_datetime($str){
		if(date("Y-m-d", strtotime($str))==$str){
			return true;
		}else{
			return false;
		}
	}

	public function get_search($page,$url){
		$where = '';
		$search = array();
		$date_page = array(
			'att'=>'create_time',
			'article' => 'a.create_time'
		);
		$use_page = array(
			'att'=>'use_num'
		);
		$category_page = array(
			'article'=>'a.category_id'
		);
		$q_page = array(
			'att'=>'sign',
			'article' => 'a.title'
		);
		
		if(isset($date_page[$page])){
	        $st = date("Y-m-d",strtotime("-29 day"));
	        $et = date("Y-m-d",strtotime("+1 day"));
	        if(request()->has('st','param') & request()->has('et','param')){
	            $st = request()->param('st');
	            $et = request()->param('et');
	            if(!($this->check_datetime($st)) || !($this->check_datetime($et))){
	                $this->redirect($url);
	            }
	        }
	        $search['st'] = $st;
	        $search['et'] = $et;
	        $is_where = ($where=='')?'':' and ';
	        $where .= "{$is_where}'{$st}'< {$date_page[$page]} and {$date_page[$page]} <'{$et}'";
	    }

        if(isset($q_page[$page])){
        	$search['q'] = '';
	        if(request()->has('q','param')){
	            $q = request()->param('q');
	            $search['q'] = $q;
	            $is_where = ($where=='')?'':' and ';
	            $where .= "{$is_where}{$q_page[$page]} like '%{$q}%'";
	        }
	    }

        if(isset($use_page[$page])){
        	$search['use'] = 'all';
	        if(request()->has('use','param')){
	            $use = request()->param('use');
	            $search['use'] = $use;
	            $is_where = ($where=='')?'':' and ';
	            switch ($use) {
	            	case 'yes':
	            		$where .= "{$is_where}{$use_page[$page]}>0";		
	            		break;
	            	case 'no':
	            		$where .= "{$is_where}{$use_page[$page]}=0";		
	            		break;
	            }
	        }
	    }

	    if(isset($category_page[$page])){
        	$search['c_id'] = 0;
	        if(request()->has('c_id','param')){
	            $c_id = request()->param('c_id');
	            $search['c_id'] = $c_id;
	            $is_where = ($where=='')?'':' and ';
	            if($c_id>0){
	            	$where .= "{$is_where}{$category_page[$page]}={$c_id}";		
	            }
	        }
	    }

        return array(
        	'search' => $search,
        	'where' => $where
        );
	}	
}