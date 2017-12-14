<?php
namespace app\index\model;
use app\index\model\Start;

class Article extends Start{
	protected $updateTime = false;
	protected $insert = ['view_num'=>0];
	public function category(){
        return $this->belongsTo('Category');
    }
}